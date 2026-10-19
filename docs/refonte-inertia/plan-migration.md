# Plan de migration Blade → Inertia (bcpme)

Décision : migrer tous les écrans vers Inertia, puis supprimer en une fois les vues d'écran Blade.
On conserve les vues de documents (PDF, exports Excel), les e-mails et la vue racine `inertia/app.blade.php`.

## Inventaire

| Catégorie | Vues | Sort |
|---|---|---|
| Écrans routés | 132 | Migrés vers environ 40 pages Inertia, puis supprimés |
| Partials d'écran | 55 | Supprimés avec leurs écrans |
| Layouts et composants Blade | 17 | Remplacés par `AppLayout.vue`, puis supprimés |
| Vues jamais atteintes | 71 | Supprimées au lot 7 (après une dernière vérification) |
| PDF et exports | 4 et plus (`*_pdf`, `Exports/*`) | Conservés |
| E-mails | `emails/*` | Conservés |

## Méthode

- **Migration sur place.** La route existante garde son nom et son URL ; le contrôleur renvoie `Inertia::render`
  au lieu de `view()`. Les liens, favoris et redirections restent valables.
- **Une page Vue par fonction, pas par profil.** Les écarts entre profils sont décrits côté serveur (presenter) :
  colonnes, filtres, actions, périmètre.
- **Les règles d'accès existantes sont reprises telles quelles** (requêtes de périmètre, middlewares de profil).
- Chaque lot est vérifié en lecture seule sur des données réelles (rendu par profil, comparaison avec l'écran Blade),
  puis validé en recette sur review avant le push sur `bcpme` (le push déploie automatiquement).

## Lots

| Lot | Contenu | Vues Blade remplacées | Pages Inertia |
|---|---|---|---|
| 0 ✅ | Socle, layout, menu par profil, fiche dossier (pilote v2) | — | `Dossiers/Show` |
| 1 ✅ | Espaces centraux (RoleSpace) : tableau de bord, listes entreprises / prospects / dossiers, fiche entreprise, pièces, bascule de la fiche dossier sur place | 9 RoleSpace + 4 tableaux de bord analystes + juridique / conformité | `Dashboard/Index`, `Entreprises/Index`, `Entreprises/Show`, `Entreprises/Pieces`, `Dossiers/Index` |
| 2 | Agence : gestionnaire (prospects, fiche, sites, équipe, tiers, questionnaire, pièces exigibles, analyse critique), chef d'agence (validations), responsable régional | environ 50 (Gestionnaire, Ca, Regional) | `Entreprises/Form`, `Entreprises/Tiers`, `Entreprises/Questionnaire`, `Workflow/Validation`, réutilisation des pages du lot 1 |
| 3 | Chef de filière (structuration, dossier d'instruction) et analyste financier (grille de notation, DSF, budgets, avis) | environ 25 (ChefFiliere, Analyste) | `Structuration/Show`, `Dossiers/Create`, `Dossiers/Workspace` |
| 4 | Programmes : catalogue, fiche, création et édition | 18 (6 profils) | `Programmes/Index`, `Programmes/Show`, `Programmes/Form` |
| 5 | Administration : utilisateurs, agences, territoire, référentiels, pièces exigibles, types de fichiers, modèles de documents, délégation de pouvoir | environ 25 (Admin) | `Admin/Users`, `Admin/Referentiel` (gabarit CRUD générique), `Admin/Delegation`, `Territoire/Index` |
| 6 | Transverses : connexion, profil, pilotage (tdb), grille des engagements, bibliothèque de documents, avis juridique / conformité sur prospects, instruction détaillée et analyse critique | environ 12 | `Auth/Login`, `Profile/Edit`, `Pilotage/Index`, `Engagements/Show`, `Documents/Index`, `Prospects/Avis`, `Dossiers/Instruction`, `Dossiers/AnalyseCritique` |
| 7 | Suppression : vues d'écran, partials, layouts, vues mortes, contrôleurs dupliqués, middleware de cohabitation | 275 environ | — |

## Avancement

### Lot 1 (terminé, non commité)

Pages Inertia en place (migration sur place, mêmes URL) pour les 16 espaces centraux :
tableau de bord (RoleSpace, juridique, conformité, analystes crédit / juridique / risques / conformité),
listes entreprises / clients / prospects, fiche entreprise, pièces exigibles, liste et fiche des dossiers
(avec gestion des pièces jointes), avis juridique / conformité sur les prospects (liste, dossiers traités, fiche + saisie d'avis).

Restent en Blade dans ces espaces, et sont prévus au lot 6 : l'instruction détaillée et les deux écrans d'analyse critique.

Point d'attention pour le lot 7 : les vues `Prospects/review_*` figuraient à tort parmi les vues « mortes »
(elles sont appelées par un contrôleur hérité). La liste des vues mortes sera revérifiée une à une avant toute suppression.
