# Procès-verbal de recette opérationnelle (RO)

| Élément | Valeur |
|--------|--------|
| **Éditeur** | ANGARA SOLUTIONS |
| **Client** | BCPME — Banque Camerounaise des Petites et Moyennes Entreprises |
| **Référence document** | **ANG-BCPME-REC-008** |
| **Indice** | A |
| **Registre** | `NUMEROTATION.md` |
| **Objet** | Validation de la **mise en production opérationnelle** de la solution ANGARA (RUN, organisation, supervision, SUPPORT) |
| **Environnement cible** | **Production** (ou préciser) |
| **Date** | [JJ/MM/AAAA] |

---

## Présences

| Nom | Organisation | Fonction | Signature |
|-----|--------------|----------|-----------|
| | BCPME — SI / exploitation | | |
| | BCPME — Métier | | |
| | ANGARA SOLUTIONS | | |

---

## 1. Prérequis RUN vérifiés

| Thème | Contrôle | Conforme (Oui / Non / N/A) | Preuve / référence |
|-------|----------|----------------------------|---------------------|
| Exploitabilité | Procédures d’exploitation (démarrage / arrêt / sauvegarde) | | |
| Sauvegardes | Plan de sauvegarde BDD + fichiers + test de restauration | | |
| Supervision | Supervison disque, CPU, services HTTP, erreurs applicatives | | |
| Sécurité | Durcissement serveur, comptes de service, rotation secrets | | |
| Journalisation | Conservation et accès aux logs conformes politique BCPME | | |
| PRA / continuité | RTO/RPO ou équivalent documenté (si applicable) | | |
| Support | Canaux, horaires, niveaux d’engagement (contrat support) | | |

---

## 2. Essai opérationnel en conditions réelles (extrait)

| Scénario | Date / heure | Résultat | Observation |
|----------|--------------|----------|-------------|
| Pic d’usage représentatif | | OK / KO | |
| Exécution planificateur / batchs | | OK / KO | |
| Restauration partielle testée | | OK / KO | |

---

## 3. Formalités contractuelles associées

| Élément | Statut |
|---------|--------|
| PV recette version cible signé | Oui / Non — réf. **ANG-BCPME-REC-007** |
| Transfert compétences / formation exploitants | Réalisée le [ ] / N/A |
| Glissement éventuel vers TMA | [référence contrat] |

---

## 4. Réserves opérationnelles

[« Néant » ou détail — ex. renfort supervision à prévoir.]

---

## 5. Conclusion

☐ **La recette opérationnelle est acceptée sans réserve** — la solution peut être considérée comme **en service** au sens opérationnel.  
☐ **Acceptée avec réserve** (section 4).  
☐ **Refusée** (motifs : [ ]).

---

*Fait à [lieu], le [date], en [nombre] exemplaires originaux.*

**Pour BCPME**  
Nom : _________________________  
Fonction : _________________________  
Signature :

**Pour ANGARA SOLUTIONS**  
Nom : _________________________  
Fonction : _________________________  
Signature :
