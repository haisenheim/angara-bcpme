# ingest_scoring_excel.py
"""
Ingestion de l'Excel ANGARA vers MySQL :
- Feuille 1  -> scoring_case, criteria_catalog, case_criterion_value, case_risk_item
- Feuille 2  -> criteria_rules (interprétations)
- Feuille 4  -> notation_scale, notation_grade (système de notation)

Prérequis:
  pip install pandas SQLAlchemy PyMySQL openpyxl python-slugify

Usage:
  export MYSQL_URL="mysql+pymysql://USER:PASS@HOST:3306/angara?charset=utf8mb4"
  python ingest_scoring_excel.py /chemin/GRILLE_SCORING_EXPLOITATIONS_CACAO.xlsx --case C-2025-001
"""

import argparse, re, json, unicodedata, sys
from typing import Optional, Any, Dict, List, Tuple
import pandas as pd
from sqlalchemy import create_engine, text
from slugify import slugify as _slugify

def slugify(s: str) -> str:
    # slug sans accents, '_' pour séparateur
    return _slugify(s, lowercase=True, separator="_")

def parse_number(v: Any) -> Optional[float]:
    if v is None: return None
    s = str(v).strip().replace(",", ".")
    try:
        return float(s)
    except:
        return None

RANGE_PAT = re.compile(r"^([\[\(])\s*([^;]+)\s*;\s*([^\]\)]+)\s*([\]\)])$")
LT_PAT    = re.compile(r"^\(<\s*([0-9]+(?:[.,][0-9]+)?)\)$")
GT_PAT    = re.compile(r"^\(>\s*([0-9]+(?:[.,][0-9]+)?)\)$")

def parse_range_label(lbl: str):
    if lbl is None: return None
    lbl = str(lbl).strip().replace(",", ".")
    m = RANGE_PAT.match(lbl)
    if m:
        return dict(
            min=parse_number(m.group(2)),
            max=parse_number(m.group(3)),
            include_min=1 if m.group(1) == "[" else 0,
            include_max=1 if m.group(4) == "]" else 0,
            label=lbl
        )
    m = LT_PAT.match(lbl)
    if m:
        return dict(min=None, max=parse_number(m.group(1)), include_min=1, include_max=0, label=lbl)
    m = GT_PAT.match(lbl)
    if m:
        return dict(min=parse_number(m.group(1)), max=None, include_min=0, include_max=1, label=lbl)
    return None

def find_header_row_sheet1(df: pd.DataFrame) -> Optional[int]:
    idx = df.index[df.iloc[:,0].astype(str).str.contains("Critères de notation", na=False)]
    return int(idx[0]) if len(idx) else None

def ingest_sheet1(engine, xlsx_path: str, case_ref: str) -> int:
    df0 = pd.read_excel(xlsx_path, sheet_name=0, header=None)
    hdr = find_header_row_sheet1(df0)
    if hdr is None:
        raise RuntimeError("Entête 'Critères de notation' introuvable en Feuille 1.")
    table = df0.iloc[hdr+1:, :5].copy()
    table.columns = ["critere","donnees_collectees","poids","score","scoring10"]
    table = table.dropna(how="all")
    table = table[~table["critere"].isna()]

    # Entête du dossier (avant la table)
    header_block = df0.iloc[:hdr, :2].fillna("")
    header_map = {}
    for i, row in header_block.iterrows():
        key = str(row[0]).strip()
        val = str(row[1]).strip()
        if key and val:
            header_map[key] = val

    # Création du dossier
    with engine.begin() as cx:
        cx.execute(text("""
            INSERT INTO scoring_case(case_ref, promoter_name, location_text, area_ha, culture_year, gps_or_polygon, meta_json)
            VALUES(:ref, :prom, :loc, :area, :yr, :gps, :meta)
            ON DUPLICATE KEY UPDATE promoter_name=VALUES(promoter_name),
                location_text=VALUES(location_text), area_ha=VALUES(area_ha),
                culture_year=VALUES(culture_year), gps_or_polygon=VALUES(gps_or_polygon),
                meta_json=VALUES(meta_json)
        """), dict(
            ref=case_ref,
            prom=header_map.get("Nom du promoteur"),
            loc=header_map.get("Localisation de l’exploitation") or header_map.get("Localisation de l'exploitation"),
            area=parse_number(header_map.get("Superficie exploitée")),
            yr=parse_number(header_map.get("Année de mise en culture")),
            gps=header_map.get("Coordonnées GPS de la ferme (< 4 ha) ou polygone (> 4 ha)"),
            meta=json.dumps(header_map, ensure_ascii=False)
        ))
        case_id = cx.execute(text("SELECT id FROM scoring_case WHERE case_ref=:ref"), dict(ref=case_ref)).scalar()

        # Parcours des lignes de la table des critères
        section = None
        display_order = 0
        for _, r in table.iterrows():
            crit = str(r["critere"]).strip()
            if re.match(r"^\d+\.\s", crit):  # section ex: "1. Stratégie et gestion"
                section = crit
                continue
            if crit.lower() in ("risques","total"):
                continue
            display_order += 1
            crit_key = slugify(crit)

            # upsert catalogue
            import math
            poids_val = r.get("poids")
            try:
                poids_val = float(poids_val)
                if math.isnan(poids_val):
                    poids_val = None
            except Exception:
                poids_val = None
            cx.execute(text("""
                INSERT INTO criteria_catalog(crit_key, crit_label, section_label, display_order, default_weight, active)
                VALUES(:k,:l,:s,:o,:w,1)
                ON DUPLICATE KEY UPDATE
                    crit_label=VALUES(crit_label),
                    section_label=VALUES(section_label),
                    display_order=VALUES(display_order),
                    default_weight=VALUES(default_weight)
            """), dict(k=crit_key, l=crit, s=section, o=display_order, w=poids_val))

            criterion_id = cx.execute(text("SELECT id FROM criteria_catalog WHERE crit_key=:k"), dict(k=crit_key)).scalar()

            raw_val = r.get("donnees_collectees")
            # If raw_val is nan, set to None
            if isinstance(raw_val, float) and math.isnan(raw_val):
                raw_val = None
            elif isinstance(raw_val, str) and raw_val.strip().lower() == "nan":
                raw_val = None
            num_val = parse_number(raw_val)
            if isinstance(num_val, float) and math.isnan(num_val):
                num_val = None
            score_10 = None if pd.isna(r.get("score")) else int(float(r.get("score")))
            scoring10 = None if pd.isna(r.get("scoring10")) else float(r.get("scoring10"))
            poids_raw = r.get("poids")
            try:
                weight = float(poids_raw)
                if math.isnan(weight):
                    weight = None
            except Exception:
                weight = None

            cx.execute(text("""
                INSERT INTO case_criterion_value(case_id, criterion_id, raw_value, numeric_value, weight, score_10, scoring10, evidence)
                VALUES(:case_id, :crit_id, :raw, :num, :w, :s10, :sc10, :ev)
                ON DUPLICATE KEY UPDATE raw_value=VALUES(raw_value),
                    numeric_value=VALUES(numeric_value),
                    weight=VALUES(weight),
                    score_10=VALUES(score_10),
                    scoring10=VALUES(scoring10),
                    evidence=VALUES(evidence)
            """), dict(
                case_id=case_id, crit_id=criterion_id, raw=str(raw_val) if raw_val is not None else None,
                num=num_val, w=weight, s10=score_10, sc10=scoring10, ev=str(raw_val) if raw_val is not None else None
            ))

        # Bloc risques
        risks_started = False
        for _, r in table.iterrows():
            label = str(r["critere"]).strip()
            if label.lower() == "risques":
                risks_started = True
                continue
            if not risks_started:
                continue
            if label.lower() in ("total",):
                break
            mitigation = r.get("donnees_collectees")
            weight = float(r.get("poids") or 0.0)
            score_10 = None if pd.isna(r.get("score")) else int(float(r.get("score")))
            scoring10 = None if pd.isna(r.get("scoring10")) else float(r.get("scoring10"))
            cx.execute(text("""
                INSERT INTO case_risk_item(case_id, risk_label, mitigation, weight, score_10, scoring10)
                VALUES(:case_id, :lab, :mit, :w, :s10, :sc10)
            """), dict(case_id=case_id, lab=label, mit=str(mitigation) if mitigation is not None else None,
                       w=weight, s10=score_10, sc10=scoring10))

    return 1

def ingest_sheet2_rules(engine, xlsx_path: str) -> int:
    df = pd.read_excel(xlsx_path, sheet_name=1, header=None)
    total = 0
    current_crit = None
    with engine.begin() as cx:
        for i in range(len(df)):
            head = df.iat[i, 0]
            if pd.isna(head):
                continue
            head_str = str(head).strip()
            current_crit = slugify(head_str)
            for j in range(1, df.shape[1]):
                cell = df.iat[i, j]
                if pd.isna(cell):
                    continue
                cell_str = str(cell).strip()
                rng = parse_range_label(cell_str)
                score_val = None

                if rng:
                    for k in range(i + 1, min(i + 4, len(df))):
                        val = df.iat[k, j]
                        if not pd.isna(val) and str(val).strip().isdigit():
                            sval = int(str(val).strip())
                            if 1 <= sval <= 10:
                                score_val = sval
                                break
                    cx.execute(text("""
                        INSERT INTO criteria_rules(crit_key, rule_label, min_value, max_value, include_min, include_max, categorical_value, score_10)
                        VALUES(:k,:rl,:minv,:maxv,:imin,:imax,:cat,:sc)
                    """), dict(k=current_crit, rl=cell_str, minv=rng["min"], maxv=rng["max"],
                               imin=rng["include_min"], imax=rng["include_max"], cat=None, sc=score_val or 5))
                    total += 1
                else:
                    if len(cell_str) <= 80 and not any(ch in cell_str for ch in "[]();"):
                        near = df.iat[i, j+1] if j+1 < df.shape[1] else None
                        sval = None
                        if near is not None and str(near).strip().isdigit():
                            sval = int(str(near).strip())
                            if not (1 <= sval <= 10):
                                sval = None
                        cx.execute(text("""
                            INSERT INTO criteria_rules(crit_key, rule_label, min_value, max_value, include_min, include_max, categorical_value, score_10)
                            VALUES(:k,:rl,NULL,NULL,1,1,:cat,:sc)
                        """), dict(k=current_crit, rl=cell_str, cat=cell_str.lower(), sc=sval or 5))
                        total += 1
    return total

def ingest_sheet4_notation(engine, xlsx_path: str, scale_code="ANGARA_SME_V1", scale_label=None) -> int:
    df = pd.read_excel(xlsx_path, sheet_name=3, header=None)
    if scale_label is None:
        scale_label = "Notation ANGARA PME (Feuille 4)"
    with engine.begin() as cx:
        cx.execute(text("""
            INSERT INTO notation_scale(code, label, description, is_active)
            VALUES(:code,:label,:desc,1)
            ON DUPLICATE KEY UPDATE label=VALUES(label), description=VALUES(description), is_active=1
        """), dict(code=scale_code, label=scale_label, desc="Import auto Feuille 4"))
        scale_id = cx.execute(text("SELECT id FROM notation_scale WHERE code=:c"), dict(c=scale_code)).scalar()

        count = 0
        for i in range(len(df)):
            for j in range(min(6, df.shape[1])):
                cell = df.iat[i, j]
                if pd.isna(cell):
                    continue
                s = str(cell)
                if "SME" in s and "Score" in s and "/10" in s:
                    mcode = re.search(r"(SME[\d-]+)", s)
                    mscore = re.search(r"Score\s+(\d+)\s*/\s*10", s)
                    grade_code = mcode.group(1) if mcode else None
                    score10 = int(mscore.group(1)) if mscore else None
                    lines = s.strip().split("\n")
                    title = lines[0].strip()
                    desc  = "\n".join(lines[1:]).strip() if len(lines) > 1 else None
                    label = title.replace(grade_code or "", "").replace("(Score", "").strip(" -:)\n")
                    if score10 is not None:
                        if score10 <= 3: risk = 'faible'
                        elif score10 <= 6: risk = 'moyen'
                        else: risk = 'élevé'
                        default_decision = 'ACCEPTER' if risk=='faible' else ('AJOURNER' if risk=='moyen' else 'REFUSER')
                    else:
                        risk, default_decision = None, None

                    if grade_code and score10:
                        cx.execute(text("""
                            INSERT INTO notation_grade(scale_id, score_10, grade_code, grade_label, description, risk_bucket, default_decision, display_order)
                            VALUES(:sid,:s10,:gcode,:glabel,:desc,:rb,:dec,:ord)
                            ON DUPLICATE KEY UPDATE grade_label=VALUES(grade_label), description=VALUES(description),
                                risk_bucket=VALUES(risk_bucket), default_decision=VALUES(default_decision)
                        """), dict(sid=scale_id, s10=score10, gcode=grade_code, glabel=label or grade_code,
                                   desc=desc, rb=risk, dec=default_decision, ord=score10))
                        count += 1
        return count

def main():
    import os
    ap = argparse.ArgumentParser()
    ap.add_argument("xlsx", help="Chemin du classeur Excel")
    ap.add_argument("--case", dest="case_ref", required=True, help="Référence du dossier (ex: C-2025-001)")
    ap.add_argument("--mysql", dest="mysql_url", default=None, help="URL SQLAlchemy MySQL (sinon MYSQL_URL env)")
    ap.add_argument("--scale-code", default="ANGARA_SME_V1")
    args = ap.parse_args()

    mysql_url = args.mysql_url or os.environ.get("MYSQL_URL")
    if not mysql_url:
        print("Erreur: précisez --mysql ou l'env MYSQL_URL", file=sys.stderr)
        sys.exit(2)

    engine = create_engine(mysql_url)

    print(">> Feuille 1 (grille + entête + risques)")
    ingest_sheet1(engine, args.xlsx, args.case_ref)

    print(">> Feuille 2 (règles d'interprétation)")
    n_rules = ingest_sheet2_rules(engine, args.xlsx)
    print(f"   {n_rules} règles insérées (heuristique).")

    print(">> Feuille 4 (notation SME)")
    n_grades = ingest_sheet4_notation(engine, args.xlsx, scale_code=args.scale_code)
    print(f"   {n_grades} grades insérés/mis à jour.")

    print("Terminé.")

if __name__ == "__main__":
    main()
