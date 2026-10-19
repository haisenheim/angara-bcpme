# Interface Inertia de bcpme

Tous les écrans de l'application sont des pages Inertia (Vue 3 + Tailwind). Les seules vues Blade
restantes sont les documents générés (PDF, Excel), les e-mails, les pages d'erreur et la vue racine
`resources/views/inertia/app.blade.php`. Les conventions de développement sont dans `conventions.md`.

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

## Sécurité

Le HTML saisi par les utilisateurs (avis, analyses) est affiché via `SafeHtml.vue` (DOMPurify), jamais en `v-html` brut.

## Ajouter un écran

1. Récupérer le HTML de l'écran dans Stitch (MCP `get_screen` → `htmlCode`).
2. Créer la page `resources/js/inertia/Pages/<Domaine>/<Ecran>.vue` en réutilisant `Components/ui` et `Components/workflow`.
3. Préparer les props dans un presenter (`app/Services/Inertia/...`), qui réutilise les règles d'accès existantes.
4. Déclarer la route (le middleware Inertia est dans le groupe `web`) et ajouter l'entrée de menu dans `WorkspaceNavigation`.
