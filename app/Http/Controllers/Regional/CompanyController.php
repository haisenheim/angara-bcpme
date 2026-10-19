<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Concerns\AppliesEntrepriseListIndexFilters;
use App\Http\Controllers\Concerns\AppliesProspectListIndexFilters;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Critere;
use App\Models\DossierEntreeRelation;
use App\Models\Agence;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Person;
use App\Models\Region;
use App\Models\Programme;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Tier;
use App\Models\User;
use App\Services\ClientEntrepriseTableExportService;
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

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Même périmètre (direction régionale) que fetchAll et mêmes filtres que exportEntreprisesClients.
        $scope = fn () => Entreprise::query()->where('prospect', 0)->where('representation_id', auth()->user()->representation_id);
        $query = $scope();
        $this->applyPromuAgenceGestionnaireFiltersToQuery($query, $this->parsePromuClientAndAgenceGestionnaireFilters($request, true), true);
        $structurationStatus = DossierEntreeRelation::normalizeClientStructurationFilter($request->query('client_structuration_status'));
        if ($structurationStatus) {
            $query->whereClientStructurationStatus($structurationStatus);
        }
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Entreprises',
            'subtitle' => 'Entreprises clientes de votre direction régionale.',
            'countLabel' => 'entreprise(s)',
            'showRoute' => 'regional.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'capital', 'agence', 'structuration'],
            'filters' => ['client_structuration_status', 'agence_id', 'gestionnaire_id', 'promu_client'],
            'orderBy' => ['name', 'asc'],
            'exportRoute' => 'regional.entreprises.export',
            'exportSearch' => false,
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('regional.dashboard')],
                ['label' => 'Entreprises'],
            ],
        ] + $list->agenceGestionnaireOptions($scope()));
    }

    public function getProspects(Request $request)
    {
        // Même périmètre et mêmes filtres que fetchProspectsPaginated / fetchProspectsStats.
        $query = $this->applyProspectIndexFilters($this->prospectsQuery(), $this->parseProspectIndexFilters($request), []);
        $list = app(EntrepriseListPresenter::class);

        return $list->render($request, $query, [
            'title' => 'Prospects',
            'subtitle' => 'Prospects soumis — visibilité nationale (brouillons exclus).',
            'countLabel' => 'prospect(s)',
            'showRoute' => 'regional.entreprises.show',
            'columns' => ['entreprise', 'identifiants', 'dirigeant', 'localisation', 'taille', 'capital', 'agence', 'statut'],
            'filters' => ['region_id', 'taille', 'forme_id', 'caractere', 'agence_id', 'gestionnaire_id', 'submission', 'created'],
            'exportRoute' => 'regional.prospects.export',
            'stats' => $list->standardStats($this->fetchProspectsStats($request)->getData(true), 'Total prospects'),
            'empty' => 'Aucun prospect trouvé.',
            'breadcrumb' => [
                ['label' => 'Tableau de bord', 'href' => route('regional.dashboard')],
                ['label' => 'Prospects'],
            ],
        ] + $list->agenceGestionnaireOptions($this->prospectsQuery()));
    }

    public function fetchAll()
    {
        $items = Entreprise::where('prospect', 0)
            ->where('representation_id', auth()->user()->representation_id)
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
            ->where('representation_id', auth()->user()->representation_id)
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
            'regional-clients',
            'Régional — entreprises clientes',
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
            'regional-prospects',
            'Régional — liste des prospects',
            'Prospects soumis — visibilité nationale (brouillons exclus)',
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
        $sheet = $page->sheet($item, ['routePrefix' => 'regional']);

        return $page->render($item, 'regional', $sheet, [
            $page->simulationLink($item),
        ]);
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

        return redirect(route('ca.entreprises.show', $token));
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
        $entreprise = Entreprise::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();
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

        return redirect(route('ca.entreprises.show', $token));
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
