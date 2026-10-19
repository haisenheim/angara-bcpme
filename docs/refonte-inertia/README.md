# Refonte Inertia de bcpme — socle technique

Branche : `refonte/inertia` (issue de `bcpme`). Les écrans Blade restent en place ; les pages Inertia
sont ajoutées à côté et remplacent les écrans Blade un par un.

## Pile

- Laravel 10 + `inertiajs/inertia-laravel` 2, Vue 3, `@inertiajs/vue3` 2, Tailwind CSS 4.
- Build Vite dans la sortie historique `public/js/modules` (manifest `public/js/modules/manifest.json`,
  versionné comme avant). Commande : `npm run build`.
- Maquettes : projet Google Stitch « ANGARA BCPME — Refonte Inertia », design system « BCPME — Banque sobre ».
  Les tokens Tailwind de `resources/js/inertia/app.css` reprennent les noms du HTML Stitch
  (`bg-secondary`, `bg-primary-container`, `text-on-surface-variant`…). Le HTML Stitch se transpose donc tel quel.

## Où faire évoluer quoi

| Besoin | Fichier |
|---|---|
| Menu latéral (tous profils) | `app/Support/Navigation/WorkspaceNavigation.php` |
| Données partagées (utilisateur, profil, flash) | `app/Http/Middleware/HandleInertiaRequests.php` |
| Layout (sidebar, topbar, footer) | `resources/js/inertia/Layouts/AppLayout.vue`, `Components/layout/*` |
| Composants d'interface | `resources/js/inertia/Components/ui/*` |
| Fiche dossier : pôles visibles, actions par profil | `app/Services/Inertia/DossierInstructionPagePresenter.php` |
| Fiche dossier : rendu | `resources/js/inertia/Pages/Dossiers/Show.vue`, `Components/workflow/*` |

Les règles métier restent côté serveur. Une action de workflow est décrite par le presenter
(libellé, champs, URL du POST existant) ; `ActionDialog.vue` l'affiche sans connaître le métier.

## Cohabitation Blade / Inertia

- Les pages Inertia portent le middleware `inertia` route par route. Exemple : `{espace}/v2/dossiers/{token}`,
  déclarée dans les 16 espaces qui utilisent `RoleSpace\PortfolioController::dossierShow`.
- Les actions POST sont les routes existantes. Elles redirigent vers la fiche Blade ;
  `RedirectLegacyDossierToInertia` (groupe `web`) renvoie vers la fiche Inertia, uniquement pour les
  requêtes Inertia et si une page v2 existe pour l'espace concerné.
- La fiche Blade affiche un lien « Nouvelle interface (aperçu) ». La fiche Inertia affiche un lien « Ancienne interface ».
- Le HTML saisi par les utilisateurs (avis) est affiché via `SafeHtml.vue` (DOMPurify), jamais en `v-html` brut.

## Ajouter un écran

1. Récupérer le HTML de l'écran dans Stitch (MCP `get_screen` → `htmlCode`).
2. Créer la page `resources/js/inertia/Pages/<Domaine>/<Ecran>.vue` en réutilisant `Components/ui` et `Components/workflow`.
3. Préparer les props dans un presenter (`app/Services/Inertia/...`), qui réutilise les règles d'accès existantes.
4. Déclarer la route avec `->middleware('inertia')` et ajouter l'entrée de menu dans `WorkspaceNavigation`.

## Limites du pilote

- Analyste financier (espace `analyste`) et chef d'agence (`ca`) : leur fiche dossier passe par d'autres
  contrôleurs et n'a pas encore de version Inertia.
- Dépôt de pièces, grille de notation, analyse critique et grille des engagements : liens vers les écrans existants.
- Le portefeuille juridique en consultation (`juridique/portefeuille/dossiers/...`) reste en Blade.
