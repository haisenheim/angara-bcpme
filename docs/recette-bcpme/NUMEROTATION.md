# Référentiel de numérotation — Lot recette BCPME / ANGARA

**Éditeur :** ANGARA SOLUTIONS  
**Client :** BCPME — Banque Camerounaise des Petites et Moyennes Entreprises  
**Projet :** ANGARA — déploiement et recette

---

## 1. Règles générales

| Règle | Détail |
|-------|--------|
| **Préfixe** | `ANG-BCPME-REC-` — identifie le *lot recette* pour le marché BCPME. |
| **Corps (numéro)** | `001` à `099` réservé au périmètre recette / livraison (extensions futures : `100+`). |
| **Référence complète** | `ANG-BCPME-REC-` + **numéro sur trois chiffres** — ex. `ANG-BCPME-REC-005`. |
| **Indice de document** | Lettre `A`, `B`, `C`… — révision *du modèle / du texte* du document (hors version logicielle). |
| **Version applicative** | Indiquée à part (tag, semver, nom de lot) dans le corps du PV ou de la procédure ; elle ne remplace pas le numéro de document. |
| **Références croisées** | Toujours citer l’**identifiant complet** (`ANG-BCPME-REC-xxx`, indice si pertinent). |

En cas de création d’un **nouveau type** de document dans le même lot, attribuer le **prochain numéro libre** en bas de tableau (section 2), documenter la date d’attribution et mettre à jour ce fichier.

---

## 2. Registre des documents (attribution officielle)

| N° | Référence | Intitulé | Fichier source |
|----|-----------|----------|----------------|
| 1 | **ANG-BCPME-REC-001** | Procédures d’installation | `01-procedures-installation.md` |
| 2 | **ANG-BCPME-REC-002** | Modèle d’en-tête commun (PV) | `MODELE-PV-base.md` |
| 3 | **ANG-BCPME-REC-003** | PV d’installation — version standard | `02-pv-installation-version-standard.md` |
| 4 | **ANG-BCPME-REC-004** | PV de livraison — version cible | `03-pv-livraison-version-cible.md` |
| 5 | **ANG-BCPME-REC-005** | PV d’installation — version cible | `04-pv-installation-version-cible.md` |
| 6 | **ANG-BCPME-REC-006** | PV de validation des paramétrages | `05-pv-validation-parametrages.md` |
| 7 | **ANG-BCPME-REC-007** | PV de recette — version cible | `06-pv-recette-version-cible.md` |
| 8 | **ANG-BCPME-REC-008** | PV de recette opérationnelle | `07-pv-recette-operationnelle.md` |
| 9 | **ANG-BCPME-REC-009** | PV de recette provisoire | `08-pv-recette-provisoire.md` |

---

## 3. Ordre chronologique type du cycle (référence processus)

1. **REC-001** — installation (procédure de référence).  
2. **REC-003** — installation version standard *(si applicable)*.  
3. **REC-004** — livraison version cible.  
4. **REC-005** — installation version cible.  
5. **REC-006** — validation des paramétrages.  
6. **REC-009** — recette provisoire *(si phase intermédiaire)*.  
7. **REC-007** — recette version cible.  
8. **REC-008** — recette opérationnelle.

---

## 4. Historique du référentiel de numérotation

| Indice | Date | Modifications |
|--------|------|----------------|
| A | 13/05/2026 | Création du registre et attribution REC-001 à REC-009 |
| B | 13/05/2026 | Précision : le document **ANG-BCPME-REC-001** passe en indice **B** (stack cible Ubuntu LTS, PHP 8.3+, MySQL 8+, SSH `sudo`, microservice `alliages/angara-instruction`) |

---

*Document de gestion documentaire — ANGARA SOLUTIONS.*
