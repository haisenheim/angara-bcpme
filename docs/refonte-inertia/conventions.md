# Conventions de migration Blade → Inertia

À lire avant de migrer un écran. Exemples de référence : `RoleSpace\PortfolioController`
(`entreprisesIndex`, `entrepriseShow`, `dossiersIndex`, `dossierShow`) et `Prospect\ProspectReviewController`.

## Principes

1. **Migration sur place.** On garde la route existante (nom et URL). Le contrôleur renvoie
   `Inertia::render('Domaine/Page', $props)` au lieu de `view(...)`. Pas de nouvelle route sauf nécessité absolue.
2. **Une page Vue par fonction, pas par profil.** Les écarts entre profils (colonnes, filtres, actions, liens)
   sont calculés côté serveur et passés en props. Les règles métier ne vont jamais dans le Vue.
3. **Règles d'accès inchangées.** Requêtes de périmètre, middlewares de profil et contrôles d'autorisation existants sont repris tels quels.
4. **Les actions POST/PUT/DELETE existantes sont réutilisées.** Elles redirigent souvent vers une route nommée :
   comme cette route est migrée sur place, la redirection aboutit sur la page Inertia. Les erreurs de validation
   (`withErrors`) et les messages flash (`success`, `error`, `info`) sont affichés automatiquement.
5. **Le HTML saisi par les utilisateurs** s'affiche avec `<SafeHtml>` (DOMPurify), jamais avec un `v-html` brut.
6. **Montants** en FCFA (`number_format($v, 0, ',', ' ').' FCFA'`), dates en `d/m/Y` ou `d/m/Y H:i`, formatées côté serveur.
   Attention : beaucoup de colonnes de date ne sont pas castées, il faut passer par `Carbon::parse($v)`.

## Briques disponibles

### Serveur
- `App\Support\Inertia\DataTable` : tableau paginé (colonnes, lignes, recherche, filtres select ou date, exports, état vide).
  Cellules possibles : texte, `['title' => …, 'subtitle' => …]`, `['badge' => …, 'variant' => …]`.
- `App\Support\Inertia\Badge` : `variant()` et `fromPresentation()` convertissent les variantes Bootstrap
  (`badge_variant` des modèles) vers `neutral | info | success | warning | danger`.
- `App\Services\Inertia\EntrepriseSheetPresenter` : fiche entreprise complète (`present()`, `checklist()`, `RELATIONS`).
- `App\Services\Inertia\DossierInstructionPagePresenter` : fiche dossier d'instruction (pôles, actions, pièces).
- `App\Http\Controllers\Concerns\RendersInertiaDashboard` : tableau de bord générique (indicateurs + tâches).
- `App\Support\Navigation\WorkspaceNavigation` : menu latéral par profil (les routes candidates absentes sont ignorées).
- Breadcrumb : prop `breadcrumb` = `[['label' => …, 'href' => …], …, ['label' => 'page courante']]`.

### Vue (`resources/js/inertia`)
- `Layouts/AppLayout.vue` est appliqué automatiquement : sidebar, topbar, messages flash et erreurs.
- `Components/ui` : `Button` (variantes primary, secondary, danger, success, ghost-danger ; prop `href` pour un lien),
  `Card`, `StatusBadge`, `Modal`, `Icon` (Material Symbols), `PageHeader`, `DataTable`, `RichTextEditor`, `SafeHtml`.
- `Components/workflow` : `ActionDialog` (formulaire d'action décrit par le serveur), `ActionPanel`, `PoleCard`,
  `WorkflowStepper`, `Timeline`, `RejectionBanner`, `PiecesManager`.
- Pages existantes : `Dashboard/Index`, `Entreprises/Index|Show|Pieces`, `Dossiers/Index|Show`.

### Contrat des actions (`ActionDialog`)
```php
[
  'key' => 'avis', 'label' => 'Saisir mon avis', 'icon' => 'rate_review', 'variant' => 'primary',
  'title' => 'Titre du dialogue', 'description' => 'Texte d'aide (optionnel)', 'wide' => false,
  'fields' => [[ 'name' => 'champ_post', 'label' => 'Libellé', 'required' => true, 'value' => 'valeur initiale',
                 'type' => 'text|number|date|email|tel|textarea|richtext|select|radio|checkbox|file|hidden', 'accept' => '.pdf,.png'
                 'options' => [['value' => 1, 'label' => '…']] ]],
  'submits' => [[ 'label' => 'Enregistrer', 'url' => route(...), 'variant' => 'primary', 'confirm' => '…', 'method' => 'post|put|delete' ]],
]
```

## Design (Stitch « BCPME — Banque sobre »)

Tokens Tailwind de `resources/js/inertia/app.css`, avec les noms Stitch :
- `bg-primary-container` (bouton principal anthracite), `bg-secondary` (vert sidebar), `brand` (vert logo, accents),
  `error` (rouge, uniquement pour les rejets et alertes) ;
- surfaces `bg-surface-container-lowest` (cartes blanches), `border-surface-container-highest` ;
- textes `text-on-surface` et `text-on-surface-variant` ;
- cartes `rounded-xl border … shadow-xs p-5`, titres de carte `text-xs font-semibold uppercase tracking-wider`,
  corps de texte en `text-xs` ou `text-sm`. Pas de bordeaux, pas de dégradé.

## Vérification (obligatoire, lecture seule)

La base locale `angara_demo_db` ne doit **jamais** être modifiée. Rendu d'une page en lecture seule :
passer la requête par le kernel HTTP, entre `DB::connection(...)->beginTransaction()` et `rollBack()`,
pour les connexions `mysql` et `central_app_mysql`, avec les en-têtes `X-Inertia: true` et
`X-Inertia-Version: md5_file(public_path('js/modules/manifest.json'))`. Vérifier le code 200, le composant
et les props pour chaque profil concerné. Ne jamais exécuter `tests/Feature/ExampleTest.php` (RefreshDatabase).
Compilation : `npx vite build` doit passer.
