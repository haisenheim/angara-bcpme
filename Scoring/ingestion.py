# ingest_scoring_excel.py
import re, json, math, unicodedata
import pandas as pd
from sqlalchemy import create_engine, text

EXCEL_PATH = "./fichier.xlsx"
MYSQL_URL  = "mysql+pymysql://root:root@localhost:3306/scoring_db?charset=utf8mb4"

engine = create_engine(MYSQL_URL)

def slugify(s: str) -> str:
    s = unicodedata.normalize("NFKD", s).encode("ascii", "ignore").decode("ascii")
    s = re.sub(r"[^a-zA-Z0-9]+", "_", s).strip("_").lower()
    return s

def find_header_row(df0):
    idx = df0.index[df0.iloc[:,0].astype(str).str.contains("Critères de notation", na=False)]
    return int(idx[0]) if len(idx) else None

def parse_sheet1_criteria_table(xls_path):
    full = pd.read_excel(xls_path, sheet_name=0, header=None)
    hdr = find_header_row(full)
    if hdr is None:
        raise RuntimeError("Entête 'Critères de notation' introuvable en Feuille 1.")
    tbl = full.iloc[hdr+1:, :5].copy()
    tbl.columns = ["critere","donnees_collectees","poids","score","scoring10"]
    tbl = tbl.dropna(how="all")
    tbl = tbl[~tbl["critere"].isna()]
    # en déduire section/ordre : si ligne a un pattern "d. xxx" → section
    section = None
    ordre = 0
    rows = []
    for _, r in tbl.iterrows():
        crit = str(r["critere"]).strip()
        if re.match(r"^\d+\.\s", crit):  # ex: "1. Stratégie et gestion"
            section = crit
            continue
        if crit.lower() in ("risques","total"):
            continue
        ordre += 1
        poids_val = r.get("poids")
        try:
            poids_val = float(poids_val)
            if math.isnan(poids_val):
                poids_val = None
        except Exception:
            poids_val = None
        rows.append({
            "crit_label": crit,
            "crit_key": slugify(crit),
            "donnee": r.get("donnees_collectees"),
            "poids": poids_val,
            "score": None if pd.isna(r.get("score")) else float(r.get("score")),
            "scoring10": None if pd.isna(r.get("scoring10")) else float(r.get("scoring10")),
            "section": section,
            "ordre": ordre
        })
    return rows

# Parsing des règles en Feuille 2
RANGE_PAT = re.compile(r"^([\[\(])\s*([^;]+)\s*;\s*([^\]\)]+)\s*([\]\)])$")
LT_PAT    = re.compile(r"^\(<\s*([0-9]+(?:[.,][0-9]+)?)\)$")
GT_PAT    = re.compile(r"^\(>\s*([0-9]+(?:[.,][0-9]+)?)\)$")

def parse_number(s):
    if s is None: return None
    s = str(s).strip().replace(",", ".")
    try: return float(s)
    except: return None

def parse_range_label(lbl):
    lbl = str(lbl).strip().replace(",", ".")
    m = RANGE_PAT.match(lbl)
    if m:
        inc_min = (m.group(1) == "[")
        inc_max = (m.group(4) == "]")
        lo = parse_number(m.group(2))
        hi = parse_number(m.group(3))
        return {"min": lo, "max": hi, "include_min": inc_min, "include_max": inc_max}
    m = LT_PAT.match(lbl)
    if m:
        hi = parse_number(m.group(1))
        return {"min": None, "max": hi, "include_min": True, "include_max": False}
    m = GT_PAT.match(lbl)
    if m:
        lo = parse_number(m.group(1))
        return {"min": lo, "max": None, "include_min": False, "include_max": True}
    return None

def parse_sheet2_rules(xls_path):
    # Feuille 2 est hétérogène. On adopte une heuristique:
    # - Col0: libellé du sous-critère OU du critère parent
    # - Dans la même ligne: colonnes contenant des labels de plages ("]a ; b]") et,
    #   en dessous (ou dans une colonne voisine), une note (1..10).
    # - On capture aussi les catégories textuelles exactes.
    df = pd.read_excel(xls_path, sheet_name=1, header=None)
    rules_by_crit = {}
    current_crit = None

    for i in range(len(df)):
        row = df.iloc[i, :].tolist()
        head = row[0]
        if pd.isna(head):
            continue

        head_str = str(head).strip()
        # Détecter un "nouveau critère" : heuristique simple (présence de % ou d'un intitulé de mesure)
        if any(k in head_str.lower() for k in ["%", "superficie", "âge", "age", "taux", "revenu", "coût", "cout", "charges", "annuité", "annuite", "dscr", "statut", "pratiques", "sécurité", "securite", "qualité", "qualite", "traçabilité", "tracabilite", "morale", "garantie"]):
            current_crit = slugify(head_str)
            rules_by_crit.setdefault(current_crit, [])
            # Chercher dans la ligne les colonnes "buckets" et notes possibles
            for j, cell in enumerate(row[1:], start=1):
                if pd.isna(cell):
                    continue
                cell_str = str(cell).strip()
                rng = parse_range_label(cell_str)
                if rng:
                    # Scruter les lignes suivantes à la même colonne pour trouver une note 1..10
                    score = None
                    for k in range(i+1, min(i+4, len(df))):
                        val = df.iat[k, j]
                        if not pd.isna(val) and str(val).strip().isdigit():
                            sv = int(str(val).strip())
                            if 1 <= sv <= 10:
                                score = sv
                                break
                    rules_by_crit[current_crit].append({
                        "rule_label": cell_str,
                        "min_value": rng["min"],
                        "max_value": rng["max"],
                        "include_min": 1 if rng["include_min"] else 0,
                        "include_max": 1 if rng["include_max"] else 0,
                        "categorical_value": None,
                        "score": score if score is not None else 5
                    })
                else:
                    # Valeur catégorielle potentielle
                    if len(cell_str) <= 60 and not any(ch in cell_str for ch in "[]();"):
                        # Essayer de récupérer une note à proximité (même ligne col+1)
                        score = None
                        if j+1 < df.shape[1]:
                            near = df.iat[i, j+1]
                            if not pd.isna(near) and str(near).strip().isdigit():
                                sv = int(str(near).strip())
                                if 1 <= sv <= 10:
                                    score = sv
                        rules_by_crit[current_crit].append({
                            "rule_label": cell_str,
                            "min_value": None, "max_value": None,
                            "include_min": 1, "include_max": 1,
                            "categorical_value": cell_str.lower(),
                            "score": score if score is not None else 5
                        })
    return rules_by_crit

def upsert_criteria(rows):
    import math
    with engine.begin() as cx:
        for r in rows:
            poids = r["poids"]
            if isinstance(poids, float) and math.isnan(poids):
                poids = None
            cx.execute(text("""
            INSERT INTO criteria(crit_key, crit_label, poids, section, ordre)
            VALUES(:k,:l,:w,:s,:o)
            ON DUPLICATE KEY UPDATE
              crit_label=VALUES(crit_label),
              poids=VALUES(poids),
              section=VALUES(section),
              ordre=VALUES(ordre)
            """), dict(k=r["crit_key"], l=r["crit_label"], w=poids, s=r["section"], o=r["ordre"]))

def upsert_rules(rules_by_crit):
    with engine.begin() as cx:
        for ck, arr in rules_by_crit.items():
            # on fait simple: on supprime et on réinsère
            cx.execute(text("DELETE FROM criteria_rules WHERE crit_key=:k"), dict(k=ck))
            for item in arr:
                cx.execute(text("""
                INSERT INTO criteria_rules
                  (crit_key, rule_label, min_value, max_value, include_min, include_max, categorical_value, score)
                VALUES
                  (:k, :rl, :minv, :maxv, :imin, :imax, :cat, :sc)
                """), dict(
                    k=ck, rl=item["rule_label"],
                    minv=item["min_value"], maxv=item["max_value"],
                    imin=item["include_min"], imax=item["include_max"],
                    cat=item["categorical_value"], sc=int(item["score"])
                ))

if __name__ == "__main__":
    print("Lecture Feuille 1…")
    crit_rows = parse_sheet1_criteria_table(EXCEL_PATH)
    print(f"{len(crit_rows)} lignes critères détectées.")

    print("Upsert criteria…")
    upsert_criteria(crit_rows)

    print("Lecture Feuille 2…")
    rules = parse_sheet2_rules(EXCEL_PATH)
    print(f"{sum(len(v) for v in rules.values())} règles détectées (heuristique).")

    print("Upsert rules…")
    upsert_rules(rules)

    print("Terminé.")
