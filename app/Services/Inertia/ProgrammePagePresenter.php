<?php

namespace App\Services\Inertia;

use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Indicateur;
use App\Models\Organisme;
use App\Models\Produit;
use App\Models\Programme;
use App\Models\ProgrammeOrgamisme;
use App\Models\Service;
use App\Services\ProgrammeInstructionBudgetConsumptionService;
use App\Support\Inertia\DataTable;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Pages Inertia des programmes d'appui (Programmes/Index, Programmes/Show, Programmes/Form),
 * communes aux profils admin, analyste financier, gestionnaire, chef d'agence, DG / DGA,
 * chef de filière et responsable régional.
 *
 * Les écarts entre profils (onglet dossiers, consommation budgétaire, actions d'édition) sont
 * décidés par le contrôleur appelant et passés en options ; la page Vue ne connaît pas le métier.
 */
class ProgrammePagePresenter
{
    /** Relations chargées pour la fiche programme. */
    public const SHOW_RELATIONS = [
        'produits.filiere',
        'produits.branche',
        'appuis.type',
        'composantes',
        'resultats.indicateur',
        'entreprises',
    ];

    /** Bénéficiaires personnes morales (valeurs enregistrées => libellés), repris du formulaire Blade. */
    public const TYPES_ENTREPRISE = [
        'GRANDE' => 'Grande', 'MOYENNE' => 'Moyenne', 'PETITE' => 'Petite',
        'TRES PETITE' => 'Très petite', 'COOPERATIVE' => 'Coopérative', 'ASSOCIATION' => 'Association',
    ];

    /** Bénéficiaires personnes physiques. */
    public const TYPES_PERSONNE = [
        'Homme' => 'Hommes', 'Femme' => 'Femmes', 'Jeune' => 'Jeunes', 'Personnes vulnérables' => 'Personnes vulnérables',
    ];

    /**
     * Relations à charger quand la fiche affiche l'onglet « Dossiers d'instruction ».
     */
    public static function relationsWithDossiers(): array
    {
        return array_merge(self::SHOW_RELATIONS, [
            'dossiers' => function ($q) {
                $q->with(['entreprise', 'gestionnaire', 'analyste', 'agence', 'indicateurs', 'reponses'])
                    ->orderByDesc('created_at');
            },
        ]);
    }

    /**
     * Liste paginée : même périmètre et mêmes filtres que les endpoints fetchPaginated / fetchStats
     * (tous les programmes, filtre signataire, recherche sur désignation, convention, signataire, bénéficiaires).
     *
     * @param  Closure(): array  $stats  calcul existant (fetchStats) rendu en différé
     * @param  iterable<int, string>  $signataires  options du filtre (fetchFilterOptions)
     */
    public function index(Request $request, string $space, Closure $stats, iterable $signataires, bool $canCreate = false): Response
    {
        $query = Programme::query();

        $signataire = (string) $request->query('signataire_filter', '');
        if ($signataire !== '') {
            $query->where('signataire', $signataire);
        }

        $search = trim((string) $request->query('q', ''));
        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('convention', 'like', "%{$search}%")
                    ->orWhere('signataire', 'like', "%{$search}%")
                    ->orWhere('type_pp', 'like', "%{$search}%")
                    ->orWhere('type_pm', 'like', "%{$search}%");
            });
        }

        $programmes = $query->orderBy('name')->paginate(15)->withQueryString();

        $options = [];
        foreach ($signataires as $s) {
            $options[$s] = $s;
        }

        $table = DataTable::make($programmes, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Désignation'],
                ['key' => 'convention', 'label' => 'Convention'],
                ['key' => 'signataire', 'label' => 'Signataire'],
                ['key' => 'dt_sig_conv', 'label' => 'Date de signature'],
                ['key' => 'budget', 'label' => 'Budget', 'align' => 'right'],
                ['key' => 'type_pp', 'label' => 'Bénéficiaires PP'],
                ['key' => 'type_pm', 'label' => 'Bénéficiaires PM'],
            ])
            ->row(fn (Programme $p) => [
                'href' => route("$space.programmes.show", $p->token),
                'cells' => [
                    'name' => ['title' => $p->name ?: '—', 'subtitle' => $p->active === null ? null : ($p->active ? 'Actif' : 'Inactif')],
                    'convention' => $p->convention ?: null,
                    'signataire' => $p->signataire ?: null,
                    'dt_sig_conv' => self::date($p->dt_sig_conv),
                    'budget' => self::money($p->budget),
                    'type_pp' => $p->type_pp ?: null,
                    'type_pm' => $p->type_pm ?: null,
                ],
            ])
            ->search('Désignation, convention, signataire, bénéficiaires…')
            ->select('signataire_filter', 'Signataire', $options)
            ->empty('Aucun programme trouvé.');

        $actions = [];
        if ($canCreate && Route::has("$space.programmes.create")) {
            $actions[] = ['label' => 'Nouveau programme', 'icon' => 'add', 'href' => route("$space.programmes.create")];
        }

        return Inertia::render('Programmes/Index', [
            'title' => 'Programmes d\'appui',
            'subtitle' => 'Conventions, enveloppes budgétaires et périmètre des programmes d\'appui aux PME.',
            'table' => $table->toArray(),
            'stats' => Inertia::defer($stats),
            'actions' => $actions,
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => self::routeIfExists("$space.dashboard")],
                ['label' => 'Programmes'],
            ],
        ]);
    }

    /**
     * Fiche programme.
     *
     * @param  array{dossiers?: bool, budgetConsumption?: bool, editable?: bool}  $options
     */
    public function show(Programme $item, string $space, array $options = []): Response
    {
        $withDossiers = (bool) ($options['dossiers'] ?? false);
        $editable = (bool) ($options['editable'] ?? false);

        $kpis = [
            ['label' => 'Budget total', 'value' => self::money($item->budget) ?? '—', 'tone' => 'brand'],
        ];
        if ($withDossiers) {
            $kpis[] = ['label' => 'Dossiers d\'instruction', 'value' => (string) $item->dossiers->count()];
        }
        $kpis[] = ['label' => 'Entreprises liées', 'value' => (string) $item->entreprises->count()];
        $kpis[] = ['label' => 'Secteurs (produits)', 'value' => (string) $item->produits->count()];

        $tabs = [];
        if ($withDossiers) {
            $dossierRoute = Route::has("$space.dossiers.show") ? "$space.dossiers.show" : null;
            $tabs[] = [
                'key' => 'dossiers',
                'label' => 'Dossiers d\'instruction',
                'hint' => 'Dossiers d\'instruction rattachés à ce programme (les plus récents en premier).',
                'columns' => [
                    ['key' => 'entreprise', 'label' => 'Entreprise'],
                    ['key' => 'agence', 'label' => 'Agence'],
                    ['key' => 'gestionnaire', 'label' => 'Gestionnaire'],
                    ['key' => 'analyste', 'label' => 'Analyste'],
                    ['key' => 'statut', 'label' => 'Statut'],
                    ['key' => 'created_at', 'label' => 'Créé le'],
                ],
                'rows' => $item->dossiers->map(function (Dossier $d) use ($dossierRoute) {
                    $st = $d->status;

                    return [
                        'href' => $dossierRoute && $d->token ? route($dossierRoute, $d->token) : null,
                        'cells' => [
                            'entreprise' => ['title' => $d->entreprise?->name ?? '—'],
                            'agence' => $d->agence?->name,
                            'gestionnaire' => $d->gestionnaire?->name,
                            'analyste' => $d->analyste?->name,
                            'statut' => ['badge' => $st['name'] ?? '—', 'variant' => ($st['code'] ?? 0) == 1 ? 'info' : 'warning'],
                            'created_at' => self::date($d->created_at, 'd/m/Y H:i'),
                        ],
                    ];
                })->values()->all(),
                'empty' => 'Aucun dossier d\'instruction pour ce programme.',
            ];
        }

        $tabs[] = [
            'key' => 'secteurs',
            'label' => 'Secteurs cibles',
            'columns' => [
                ['key' => 'produit', 'label' => 'Produit'],
                ['key' => 'filiere', 'label' => 'Filière'],
                ['key' => 'branche', 'label' => 'Branche'],
            ],
            'rows' => $item->produits->map(fn ($p) => ['cells' => [
                'produit' => $p->name,
                'filiere' => $p->filiere?->name,
                'branche' => $p->branche?->name,
            ]])->values()->all(),
            'empty' => 'Aucun secteur cible.',
        ];
        $tabs[] = [
            'key' => 'appuis',
            'label' => 'Appuis proposés',
            'columns' => [
                ['key' => 'service', 'label' => 'Service'],
                ['key' => 'type', 'label' => 'Type'],
                ['key' => 'nature', 'label' => 'Nature'],
            ],
            'rows' => $item->appuis->map(fn ($s) => ['cells' => [
                'service' => $s->name,
                'type' => $s->type?->name,
                'nature' => ['badge' => $s->financier ? 'Financier' : 'Non financier', 'variant' => $s->financier ? 'success' : 'info'],
            ]])->values()->all(),
            'empty' => 'Aucun appui paramétré.',
        ];
        $tabs[] = [
            'key' => 'composantes',
            'label' => 'Composantes',
            'columns' => [
                ['key' => 'entite', 'label' => 'Entité'],
                ['key' => 'nature', 'label' => 'Nature'],
            ],
            'rows' => $item->composantes->map(fn ($c) => ['cells' => [
                'entite' => $c->name,
                'nature' => $c->type,
            ]])->values()->all(),
            'empty' => 'Aucune composante.',
        ];
        $tabs[] = [
            'key' => 'resultats',
            'label' => 'Résultats attendus',
            'columns' => [
                ['key' => 'indicateur', 'label' => 'Indicateur'],
                ['key' => 'attente', 'label' => 'Attentes'],
            ],
            'rows' => $item->resultats->map(fn ($r) => ['cells' => [
                'indicateur' => $r->indicateur?->name,
                'attente' => $r->attente,
            ]])->values()->all(),
            'empty' => 'Aucun résultat attendu.',
        ];
        $tabs[] = [
            'key' => 'entreprises',
            'label' => 'Entreprises',
            'columns' => [
                ['key' => 'entreprise', 'label' => 'Entreprise'],
                ['key' => 'localite', 'label' => 'Localité'],
                ['key' => 'taille', 'label' => 'Taille'],
            ],
            'rows' => $item->entreprises->map(fn ($e) => ['cells' => [
                'entreprise' => $e->name,
                'localite' => $e->localite,
                'taille' => $e->taille,
            ]])->values()->all(),
            'empty' => 'Aucune entreprise liée.',
        ];

        return Inertia::render('Programmes/Show', [
            'programme' => [
                'name' => $item->name,
                'token' => $item->token,
                'active' => $item->active === null ? null : (bool) $item->active,
                'convention' => $item->convention,
                'signataire' => $item->signataire,
            ],
            'details' => [
                ['label' => 'Désignation', 'value' => $item->name],
                ['label' => 'Réf. convention cadre', 'value' => $item->convention],
                ['label' => 'Date de signature', 'value' => self::date($item->dt_sig_conv)],
                ['label' => 'Institution signataire', 'value' => $item->signataire],
                ['label' => 'Budget appuis financiers', 'value' => self::money($item->budget_af)],
                ['label' => 'Budget appuis non financiers', 'value' => self::money($item->budget_anf)],
                ['label' => 'Budget coordination', 'value' => self::money($item->budget_coord)],
                ['label' => 'Budget total', 'value' => self::money($item->budget), 'strong' => true],
                ['label' => 'Bénéficiaires PM', 'value' => $item->type_pm],
                ['label' => 'Bénéficiaires PP', 'value' => $item->type_pp],
                ['label' => 'Début des activités', 'value' => self::date($item->dt_start)],
                ['label' => 'Contact', 'value' => $item->contact],
            ],
            'kpis' => $kpis,
            'tabs' => $tabs,
            'budget' => ($options['budgetConsumption'] ?? false) ? $this->budgetConsumption($item, $space) : null,
            'actions' => $editable ? $this->adminActions($item, $space) : [],
            'editUrl' => $editable && Route::has("$space.programmes.edit") ? route("$space.programmes.edit", $item->token) : null,
            'indexUrl' => route("$space.programmes.index"),
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => self::routeIfExists("$space.dashboard")],
                ['label' => 'Programmes', 'href' => route("$space.programmes.index")],
                ['label' => (string) str($item->name)->limit(48)],
            ],
        ]);
    }

    /**
     * Formulaire de création / modification (admin). Champs et noms POST identiques au formulaire Blade
     * (routes existantes {space}.programmes.store et {space}.programmes.save).
     */
    public function form(?Programme $item, string $space): Response
    {
        $ids = ['produits' => [], 'bailleurs' => [], 'appuisf' => [], 'appuisnf' => []];
        if ($item) {
            $item->loadMissing(['produits', 'appuis']);
            $ids['bailleurs'] = ProgrammeOrgamisme::query()
                ->where('programme_id', $item->id)
                ->pluck('organisme_id')
                ->map(fn ($id) => (int) $id)
                ->filter(fn (int $id) => $id > 0)
                ->values()->all();
            $ids['produits'] = $item->produits->pluck('id')->map(fn ($id) => (int) $id)->values()->all();
            foreach ($item->appuis as $svc) {
                if ((int) $svc->id > 0) {
                    $ids[! empty($svc->financier) ? 'appuisf' : 'appuisnf'][] = (int) $svc->id;
                }
            }
        }

        $services = Service::query()->with('type')->orderBy('name')->get();
        $serviceOptions = fn (bool $financier) => $services
            ->filter(fn ($s) => (bool) $s->financier === $financier)
            ->map(fn ($s) => ['value' => (int) $s->id, 'label' => $s->name, 'hint' => $s->type?->name])
            ->values()->all();

        $split = fn (?string $v) => array_values(array_filter(explode('-', (string) $v)));
        $dateValue = fn ($v) => $v ? Carbon::parse($v)->format('Y-m-d') : '';

        $title = $item ? 'Modifier le programme' : 'Nouveau programme';

        return Inertia::render('Programmes/Form', [
            'title' => $title,
            'mode' => $item ? 'edit' : 'create',
            'submitUrl' => $item ? route("$space.programmes.save") : route("$space.programmes.store"),
            'cancelUrl' => $item ? route("$space.programmes.show", $item->token) : route("$space.programmes.index"),
            'values' => [
                'id' => $item?->id,
                'name' => $item?->name ?? '',
                'convention' => $item?->convention ?? '',
                'dt_sig_conv' => $dateValue($item?->dt_sig_conv),
                'signataire' => $item?->signataire ?? '',
                'budget_af' => $item?->budget_af ?? 0,
                'budget_anf' => $item?->budget_anf ?? 0,
                'budget_coord' => $item?->budget_coord ?? 0,
                'type_entreprise' => $item ? array_values(array_intersect($split($item->type_pm), array_keys(self::TYPES_ENTREPRISE))) : [],
                'type_personne' => $item ? array_values(array_intersect($split($item->type_pp), array_keys(self::TYPES_PERSONNE))) : [],
                'dt_start' => $dateValue($item?->dt_start),
                'contact' => $item?->contact ?? '',
                'bailleurs' => $ids['bailleurs'],
                'produits' => $ids['produits'],
                'appuisf' => $ids['appuisf'],
                'appuisnf' => $ids['appuisnf'],
            ],
            'options' => [
                'typesEntreprise' => self::pairs(self::TYPES_ENTREPRISE),
                'typesPersonne' => self::pairs(self::TYPES_PERSONNE),
                'organismes' => Organisme::query()->orderBy('name')->get(['id', 'name'])
                    ->map(fn ($o) => ['value' => (int) $o->id, 'label' => $o->name])->values()->all(),
                'produits' => $this->produitTree(),
                'afs' => $serviceOptions(true),
                'anfs' => $serviceOptions(false),
            ],
            'breadcrumb' => array_values(array_filter([
                ['label' => 'Tableau de bord', 'href' => self::routeIfExists("$space.dashboard")],
                ['label' => 'Programmes', 'href' => route("$space.programmes.index")],
                $item ? ['label' => (string) str($item->name)->limit(40), 'href' => route("$space.programmes.show", $item->token)] : null,
                ['label' => $item ? 'Modifier' : 'Nouveau programme'],
            ])),
        ]);
    }

    /**
     * Actions d'édition de la fiche (admin) : mêmes formulaires que les modales Blade,
     * envoyés aux routes existantes. Les champs cachés (token, programme_id) passent en query string.
     */
    private function adminActions(Programme $item, string $space): array
    {
        $hidden = ['token' => $item->token, 'programme_id' => $item->id];
        $opts = fn ($rows) => collect($rows)->map(fn ($r) => ['value' => (int) $r->id, 'label' => $r->name])->values()->all();
        $actions = [];

        if (Route::has("$space.programme.composante.save")) {
            $actions[] = [
                'key' => 'composante', 'label' => 'Ajouter une composante', 'icon' => 'add_business', 'variant' => 'secondary',
                'title' => 'Nouvelle composante',
                'description' => 'Choisissez un bailleur de fonds ou une banque, puis l\'axe d\'intervention.',
                'fields' => [
                    ['name' => 'name', 'label' => 'Nom de la composante', 'type' => 'text', 'required' => true],
                    ['name' => 'organisme_id', 'label' => 'Organisme (bailleur de fonds)', 'type' => 'select', 'value' => 0,
                        'options' => array_merge([['value' => 0, 'label' => 'Aucun']], $opts(Organisme::query()->orderBy('name')->get(['id', 'name'])))],
                    ['name' => 'banque_id', 'label' => 'Banque', 'type' => 'select', 'value' => 0,
                        'options' => array_merge([['value' => 0, 'label' => 'Aucune']], $opts(Banque::query()->orderBy('name')->get(['id', 'name'])))],
                    ['name' => 'type', 'label' => 'Axe d\'intervention', 'type' => 'select', 'value' => 'Coordination', 'required' => true,
                        'options' => collect(['Coordination', 'Appuis financiers', 'Appuis non financiers', 'Formation technique et professionnelle'])
                            ->map(fn ($v) => ['value' => $v, 'label' => $v])->all()],
                ],
                'submits' => [['label' => 'Enregistrer', 'url' => route("$space.programme.composante.save", $hidden), 'variant' => 'primary']],
            ];
        }
        if (Route::has("$space.programme.resultat.save")) {
            $actions[] = [
                'key' => 'resultat', 'label' => 'Ajouter un objectif', 'icon' => 'flag', 'variant' => 'secondary',
                'title' => 'Nouvel objectif / résultat attendu',
                'fields' => [
                    ['name' => 'indicateur_id', 'label' => 'Indicateur', 'type' => 'select', 'required' => true,
                        'options' => $opts(Indicateur::query()->orderBy('name')->get(['id', 'name']))],
                    ['name' => 'attente', 'label' => 'Objectif attendu', 'type' => 'text', 'required' => true],
                ],
                'submits' => [['label' => 'Enregistrer', 'url' => route("$space.programme.resultat.save", $hidden), 'variant' => 'primary']],
            ];
        }
        if (Route::has("$space.programme.appui.save")) {
            $actions[] = [
                'key' => 'appui', 'label' => 'Ajouter un appui', 'icon' => 'volunteer_activism', 'variant' => 'secondary',
                'title' => 'Ajouter un appui au programme',
                'fields' => [
                    ['name' => 'service_id', 'label' => 'Service', 'type' => 'select', 'required' => true,
                        'options' => Service::query()->with('type')->orderBy('name')->get()
                            ->map(fn ($s) => ['value' => (int) $s->id, 'label' => $s->name.($s->type ? ' ('.$s->type->name.')' : '')])->values()->all()],
                ],
                'submits' => [['label' => 'Enregistrer', 'url' => route("$space.programme.appui.save", $hidden), 'variant' => 'primary']],
            ];
        }
        if (Route::has("$space.programme.produit.save")) {
            $actions[] = [
                'key' => 'produit', 'label' => 'Ajouter un secteur', 'icon' => 'category', 'variant' => 'secondary',
                'title' => 'Ajouter un secteur / produit',
                'fields' => [
                    ['name' => 'produit_id', 'label' => 'Produit', 'type' => 'select', 'required' => true,
                        'options' => collect($this->produitTree())->map(fn ($o) => [
                            'value' => $o['value'],
                            'label' => str_repeat('— ', $o['depth']).$o['label'],
                        ])->all()],
                ],
                'submits' => [['label' => 'Enregistrer', 'url' => route("$space.programme.produit.save", $hidden), 'variant' => 'primary']],
            ];
        }

        return $actions;
    }

    /**
     * Consommation des budgets d'appui (DG / DGA) : reprise des calculs de la vue Blade Ca/Programmes/show
     * à partir de ProgrammeInstructionBudgetConsumptionService::summarizeValidatedAgence.
     */
    private function budgetConsumption(Programme $item, string $space): array
    {
        $ibc = app(ProgrammeInstructionBudgetConsumptionService::class)->summarizeValidatedAgence($item);

        $bAf = (float) ($item->budget_af ?? 0);
        $bAnf = (float) ($item->budget_anf ?? 0);
        $bCoord = (float) ($item->budget_coord ?? 0);
        $eFin = (float) $ibc['engaged_financier'];
        $eNf = (float) $ibc['engaged_non_financier'];
        $eCoord = 0.0;
        $bTotal = $bAf + $bAnf + $bCoord;
        $eTotal = $eFin + $eNf;

        $rate = fn (float $env, float $eng) => $env > 0 ? round($eng / $env * 100, 1) : ($eng > 0 ? 100.0 : 0.0);
        $pctFin = $rate($bAf, $eFin);
        $pctNf = $rate($bAnf, $eNf);
        $pctCoord = $bCoord > 0 ? round($eCoord / $bCoord * 100, 1) : 0.0;
        $pctTotal = $rate($bTotal, $eTotal);

        $amount = fn (float $v) => number_format($v, 0, ',', ' ');
        $pct = fn (float $v, int $d = 1) => number_format($v, $d, ',', ' ').' %';

        $card = fn (string $label, float $env, float $eng, float $p) => [
            'label' => $label,
            'envelope' => $amount($env).' FCFA',
            'engaged' => $amount($eng).' FCFA',
            'rate' => $pct($p),
            'bar' => min(100, max(0, $p)),
            'remaining' => $amount(max(0, $env - $eng)).' FCFA',
            'overflow' => $eng > $env && $env > 0,
        ];

        $finAppuis = $item->appuis->where('financier', true);
        $nfAppuis = $item->appuis->where('financier', false);
        $nFin = $finAppuis->count();
        $nNf = $nfAppuis->count();
        $shareFinEnv = $nFin > 0 ? $bAf / $nFin : $bAf;
        $shareFinEng = $nFin > 0 ? $eFin / $nFin : $eFin;
        $shareNfEnv = $nNf > 0 ? $bAnf / $nNf : $bAnf;
        $shareNfEng = $nNf > 0 ? $eNf / $nNf : $eNf;

        $synthRow = fn (string $poste, float $env, float $eng, float $p, bool $strong = false) => ['cells' => [
            'poste' => $strong ? ['title' => $poste] : $poste,
            'enveloppe' => $amount($env),
            'engage' => $amount($eng),
            'taux' => $pct($p),
            'reste' => $amount(max(0, $env - $eng)),
        ]];

        $entrepriseRoute = Route::has("$space.entreprises.show") ? "$space.entreprises.show" : null;
        $dossierRoute = Route::has("$space.dossiers.show") ? "$space.dossiers.show" : null;

        return [
            'cards' => [
                $card('Appuis financiers', $bAf, $eFin, $pctFin),
                $card('Appuis non financiers', $bAnf, $eNf, $pctNf),
            ],
            'coordination' => 'Coordination : enveloppe programme de '.$amount($bCoord).' FCFA — taux de consommation suivi : '.$pct($pctCoord)
                .' (aucune saisie au niveau dossier pour cette ligne). La ventilation par dossier porte sur les appuis financiers et non financiers.',
            'synthese' => [
                'columns' => [
                    ['key' => 'poste', 'label' => 'Poste'],
                    ['key' => 'enveloppe', 'label' => 'Enveloppe (FCFA)', 'align' => 'right'],
                    ['key' => 'engage', 'label' => 'Engagé dossiers validés (FCFA)', 'align' => 'right'],
                    ['key' => 'taux', 'label' => 'Taux', 'align' => 'right'],
                    ['key' => 'reste', 'label' => 'Reste indicatif (FCFA)', 'align' => 'right'],
                ],
                'rows' => [
                    $synthRow('Appuis financiers', $bAf, $eFin, $pctFin),
                    $synthRow('Appuis non financiers', $bAnf, $eNf, $pctNf),
                    $synthRow('Coordination', $bCoord, $eCoord, $pctCoord),
                    $synthRow('Total budgets programme', $bTotal, $eTotal, $pctTotal, true),
                ],
                'empty' => '—',
            ],
            'appuis' => $item->appuis->isEmpty() ? null : [
                'columns' => [
                    ['key' => 'service', 'label' => 'Service / appui'],
                    ['key' => 'type', 'label' => 'Type'],
                    ['key' => 'nature', 'label' => 'Nature'],
                    ['key' => 'enveloppe', 'label' => 'Enveloppe ind. (FCFA)', 'align' => 'right'],
                    ['key' => 'engage', 'label' => 'Engagé ind. (FCFA)', 'align' => 'right'],
                    ['key' => 'taux', 'label' => 'Taux nature', 'align' => 'right'],
                ],
                'rows' => $item->appuis->map(function ($s) use ($shareFinEnv, $shareFinEng, $shareNfEnv, $shareNfEng, $pctFin, $pctNf, $amount, $pct) {
                    $fin = (bool) $s->financier;

                    return ['cells' => [
                        'service' => $s->name,
                        'type' => $s->type?->name,
                        'nature' => ['badge' => $fin ? 'Financier' : 'Non financier', 'variant' => $fin ? 'success' : 'info'],
                        'enveloppe' => $amount($fin ? $shareFinEnv : $shareNfEnv),
                        'engage' => $amount($fin ? $shareFinEng : $shareNfEng),
                        'taux' => $pct($fin ? $pctFin : $pctNf),
                    ]];
                })->values()->all(),
                'empty' => '—',
            ],
            'lignes' => [
                'columns' => [
                    ['key' => 'validation', 'label' => 'Validation agence'],
                    ['key' => 'client', 'label' => 'Client'],
                    ['key' => 'agence', 'label' => 'Agence'],
                    ['key' => 'fin', 'label' => 'Appui fin.', 'align' => 'right'],
                    ['key' => 'pct_fin', 'label' => '% env. fin.', 'align' => 'right'],
                    ['key' => 'nf', 'label' => 'Appui non fin.', 'align' => 'right'],
                    ['key' => 'pct_nf', 'label' => '% env. non fin.', 'align' => 'right'],
                    ['key' => 'total', 'label' => 'Sous-total', 'align' => 'right'],
                ],
                'rows' => $ibc['lignes']->map(function ($l) use ($bAf, $bAnf, $amount, $pct, $entrepriseRoute, $dossierRoute) {
                    $pf = $bAf > 0 ? round($l->budget_appui_financier / $bAf * 100, 2) : ($l->budget_appui_financier > 0 ? 100.0 : 0.0);
                    $pn = $bAnf > 0 ? round($l->budget_appui_non_financier / $bAnf * 100, 2) : ($l->budget_appui_non_financier > 0 ? 100.0 : 0.0);

                    return [
                        'href' => $dossierRoute && $l->dossier_token ? route($dossierRoute, $l->dossier_token) : null,
                        'cells' => [
                            'validation' => self::date($l->instruction_agence_validated_at, 'd/m/Y H:i'),
                            'client' => ['title' => $l->entreprise_name ?? '—',
                                'href' => $entrepriseRoute && $l->entreprise_token ? route($entrepriseRoute, $l->entreprise_token) : null],
                            'agence' => $l->agence_name,
                            'fin' => $amount($l->budget_appui_financier),
                            'pct_fin' => $pct($pf, 2),
                            'nf' => $amount($l->budget_appui_non_financier),
                            'pct_nf' => $pct($pn, 2),
                            'total' => $amount($l->budget_appui_financier + $l->budget_appui_non_financier),
                        ],
                    ];
                })->values()->all(),
                'empty' => 'Aucune affectation enregistrée pour ce programme sur des dossiers validés par le chef d\'agence.',
            ],
        ];
    }

    /**
     * Arborescence des produits (secteurs) aplatie : [{value, label, depth}], ordre parent puis enfants.
     */
    private function produitTree(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $byParent = Produit::query()->orderBy('code')->orderBy('name')->get(['id', 'name', 'code', 'parent_id'])
            ->groupBy(fn ($p) => (int) $p->parent_id);

        $out = [];
        $walk = function (int $parentId, int $depth) use (&$walk, &$out, $byParent) {
            foreach ($byParent->get($parentId, collect()) as $p) {
                $out[] = ['value' => (int) $p->id, 'label' => trim(($p->code ? $p->code.' ' : '').$p->name), 'depth' => $depth];
                if ($depth < 6) {
                    $walk((int) $p->id, $depth + 1);
                }
            }
        };
        $walk(0, 0);

        return $cache = $out;
    }

    private static function pairs(array $map): array
    {
        return collect($map)->map(fn ($label, $value) => ['value' => $value, 'label' => $label])->values()->all();
    }

    public static function money($v): ?string
    {
        return $v !== null && $v !== '' ? number_format((float) $v, 0, ',', ' ').' FCFA' : null;
    }

    public static function date($v, string $format = 'd/m/Y'): ?string
    {
        if ($v === null || $v === '') {
            return null;
        }
        try {
            return Carbon::parse($v)->format($format);
        } catch (\Throwable) {
            return null;
        }
    }

    private static function routeIfExists(string $name): ?string
    {
        return Route::has($name) ? route($name) : null;
    }
}
