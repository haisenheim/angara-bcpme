<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Concerns\AppliesProspectListIndexFilters;
use App\Http\Controllers\Concerns\RendersEntrepriseFormPages;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Critere;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Agence;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseProduit;
use App\Models\Forme;
use App\Models\Person;
use App\Models\Region;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Tier;
use App\Models\User;
use App\Services\ClientEntrepriseTableExportService;
use App\Services\EntrepriseDuplicateGuard;
use App\Services\Inertia\EntrepriseListPresenter;
use App\Services\Inertia\EntrepriseShowPage;
use App\Services\ProspectEntrepriseTableExportService;
use App\Services\TableDocumentExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    use AppliesEntrepriseListIndexFilters;
    use AppliesProspectListIndexFilters;
    use RendersEntrepriseFormPages;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Même périmètre que fetchAll et mêmes filtres que exportEntreprisesClients.
        $query = $this->clientsListQuery($request);
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Entreprises',
            'subtitle' => 'Liste de toutes les entreprises clientes.',
            'countLabel' => 'entreprise(s)',
            'showRoute' => 'admin.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'capital', 'agence', 'structuration'],
            'filters' => ['client_structuration_status', 'agence_id', 'gestionnaire_id', 'promu_client'],
            'orderBy' => ['name', 'asc'],
            'exportRoute' => 'admin.entreprises.export',
            'exportSearch' => false,
            'actions' => [['label' => 'Ajouter', 'icon' => 'add', 'href' => route('admin.entreprises.create')]],
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('admin.dashboard')],
                ['label' => 'Entreprises'],
            ],
        ] + $list->agenceGestionnaireOptions(Entreprise::query()->where('prospect', 0)));
    }

    /**
     * Clients (prospect = 0) avec les filtres de l'export (structuration, promotion, agence, gestionnaire).
     */
    private function clientsListQuery(Request $request)
    {
        $query = Entreprise::query()->where('prospect', 0);
        $filters = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $filters, true);
        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        if ($structurationStatus) {
            $query->whereClientStructurationStatus($structurationStatus);
        }

        return $query;
    }

    public function getProspects(Request $request)
    {
        // Même périmètre et mêmes filtres que fetchProspectsPaginated / fetchProspectsStats.
        $query = $this->applyProspectIndexFilters($this->prospectsQuery(), $this->parseProspectIndexFilters($request), []);
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Prospects',
            'subtitle' => 'Vue nationale des dossiers prospect (brouillons exclus).',
            'countLabel' => 'prospect(s)',
            'showRoute' => 'admin.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'taille', 'capital', 'agence', 'statut'],
            'filters' => ['region_id', 'taille', 'forme_id', 'caractere', 'agence_id', 'gestionnaire_id', 'submission', 'created'],
            'exportRoute' => 'admin.prospects.export',
            'stats' => $list->standardStats($this->fetchProspectsStats($request)->getData(true), 'Total prospects'),
            'actions' => [['label' => 'Nouvelle entreprise', 'icon' => 'add', 'href' => route('admin.entreprises.create')]],
            'empty' => 'Aucun prospect trouvé.',
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('admin.dashboard')],
                ['label' => 'Prospects'],
            ],
        ] + $list->agenceGestionnaireOptions($this->prospectsQuery()));
    }

    public function fetchAll()
    {
        $items = Entreprise::where('prospect', 0)
            ->with(['dossierEntreeRelation', 'agence', 'gestionnaire'])
            ->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    public function exportEntreprisesClients(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $query = Entreprise::query()
            ->where('prospect', 0)
            ->with(['dossierEntreeRelation', 'agence', 'gestionnaire', 'region', 'departement']);

        $filters = $this->parsePromuClientAndAgenceGestionnaireFilters($request, true);
        $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $filters, true);

        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        if ($structurationStatus) {
            $query->whereClientStructurationStatus($structurationStatus);
        }

        $items = $query->orderBy('name')->get();
        $rows = ClientEntrepriseTableExportService::rowsAdminRegional($items);

        return ClientEntrepriseTableExportService::download(
            $rows,
            ClientEntrepriseTableExportService::headersAdminRegional(),
            $format,
            'admin-clients',
            'Administration — entreprises clientes',
        );
    }

    public function fetchProspects()
    {
        $items = $this->prospectsQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    private function prospectsQuery()
    {
        return Entreprise::query()->submittedProspect();
    }

    public function fetchProspectsStats(Request $request)
    {
        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, []);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
        ];

        return response()->json($stats);
    }

    public function fetchProspectsPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim((string) $request->input('search.value', ''));

        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, []);

        $recordsTotal = $this->prospectsQuery()->count();
        $recordsFiltered = $query->count();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->orderByDesc('created_at')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function exportProspects(Request $request)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $search = trim((string) $request->input('search.value', ''));
        $base = $this->prospectsQuery();
        $filters = $this->parseProspectIndexFilters($request);
        $query = $this->applyProspectIndexFilters(clone $base, $filters, []);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $items = $query->with(['agence', 'gestionnaire', 'user', 'region', 'departement', 'arrondissement'])
            ->orderByDesc('created_at')
            ->get();

        $rows = ProspectEntrepriseTableExportService::rowsAdminRegional($items);

        return TableDocumentExportService::downloadFormatted(
            $rows,
            ProspectEntrepriseTableExportService::headersAdminRegional(),
            $format,
            'admin-prospects',
            'Administration — liste des prospects',
            'Vue nationale — prospects soumis (brouillons exclus)',
        );
    }

    public function fetchProspectsFilterOptions()
    {
        $p = $this->prospectsQuery();
        $agenceIds = (clone $p)->whereNotNull('agence_id')->distinct()->pluck('agence_id');
        $gestionnaireIds = (clone $p)->whereNotNull('gestionnaire_id')->distinct()->pluck('gestionnaire_id');

        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'agences' => Agence::query()->whereIn('id', $agenceIds)->orderBy('name')->get(['id', 'name']),
            'gestionnaires' => User::query()->whereIn('id', $gestionnaireIds)->orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $formes = Forme::all();

        return $this->entrepriseCreatePage('admin');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token', 'appuisnf', 'appuisf', 'autres', 'type_personnel');
        $anfs = explode(',', $request->appuisnf);
        $afs = explode(',', $request->appuisf);
        $produits = explode(',', $request->autres);
        $type_personnel = $request->type_personnel;
        $data['token'] = sha1(time().rand(0, 99));
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $data['personnel_'.$type_personnel] = 1;
        $entreprise = Entreprise::create($data);
        foreach ($afs as $a) {
            EntrepriseAppui::create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($anfs as $a) {
            EntrepriseAppui::create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($produits as $a) {
            EntrepriseProduit::create([
                'entreprise_id' => $entreprise->id,
                'produit_id' => $a,
            ]);
        }

        // dd($data);
        return redirect(route('admin.entreprises.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $page = app(EntrepriseShowPage::class);
        $sheet = $page->sheet($item, ['routePrefix' => 'admin']);

        return $page->render($item, 'admin', $sheet, [
            $page->link('admin.entreprise.questionnaire', $item->token, 'Questionnaire', 'quiz'),
            $page->link('admin.entreprise.physique.create', $item->token, 'Tiers personne physique', 'person_add'),
            $page->link('admin.entreprise.morale.create', $item->token, 'Tiers personne morale', 'domain_add'),
            $page->simulationLink($item),
        ]);
    }

    public function saveProgramme(Request $request)
    {
        // $token = $request->token;
        // dd($request->all());
        $data = $request->all();
        $data['token'] = sha1(time().rand(1, 100));
        $data['gestionnaire_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;

        Dossier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'programme_id' => $request->programme_id,
            ],
            $data
        );
        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    public function createTiersPhysique(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }

        return $this->tiersPhysiquePage('admin', $item);
    }

    public function createTiersMorale(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $formes = Forme::all();

        return $this->tiersMoralePage('admin', $item);
    }

    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu', $data['niu'])->where('phone', $data['phone'])->first();
        if (! $person) {
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('admin.entreprises.show', $token));
    }

    public function saveTiersMorale(Request $request)
    {
        // dd($request->all());
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = app(EntrepriseDuplicateGuard::class)->findForLinking($data, (int) $request->entreprise_id);
        if (! $entreprise) {
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('admin.entreprises.show', $token));
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();
        $isOpen = false; // Pour contrôller l'ouverture de l'accordeon

        return $this->questionnairePage('admin', $item);
    }

    public function saveQuestionnaire(Request $request)
    {
        // dd($request->choices[1]);
        $choices = $request->choices;
        foreach ($choices as $choice) {
            QuestionAnswer::updateOrCreate([
                'entreprise_id' => $choice['entreprise_id'],
                'choice_id' => $choice['choice_id'],
            ], $choice);
        }

        // Session::flash('success','Enregistrement effectué avec succès!');
        return response()->json('ok');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
