<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Banque;
use App\Models\Departement;
use App\Models\Dossier;
use App\Models\ElementConstitutif;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseElementConstitutif;
use App\Models\EntrepriseProduit;
use App\Models\Forme;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use App\Models\Person;
use App\Models\Produit;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Region;
use App\Models\Service;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class CompanyController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Gestionnaire/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Gestionnaire/Companies/prospects');
    }

    /**
     * Formulaire de création d'un prospect (entreprise non validée).
     */
    public function createProspect()
    {
        return view('Gestionnaire.Companies.create_prospect', $this->prospectFormContext());
    }

    /**
     * Enregistre un prospect (prospect = 1), champs hérités du formulaire entreprise en grande partie facultatifs.
     */
    public function storeProspect(Request $request)
    {
        $validated = $this->validateProspectFields($request);
        $data = $this->mapValidatedToProspectRow($validated, $request);
        $data['token'] = sha1(time().rand(0, 99));
        $data['prospect'] = true;
        $data['prospect_submitted_at'] = null;
        $data['user_id'] = auth()->id();
        $data['gestionnaire_id'] = auth()->id();
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        if (empty($data['systeme'])) {
            $data['systeme'] = 'Normal';
        }

        $entreprise = Entreprise::create($data);
        $this->syncAppuisEtProduits($entreprise, $request);

        Session::flash('success', 'Brouillon prospect enregistré. Complétez les informations puis soumettez pour avis juridique et conformité.');

        return redirect()->route('gestionnaire.entreprises.prospects');
    }

    /**
     * Mise à jour d'un brouillon prospect (avant soumission explicite).
     */
    public function updateProspectDraft(Request $request, Entreprise $entreprise): \Illuminate\Http\RedirectResponse
    {
        $uid = auth()->id();
        if ((int) $entreprise->gestionnaire_id !== (int) $uid && (int) $entreprise->user_id !== (int) $uid) {
            abort(403);
        }

        $validated = $this->validateProspectFields($request);
        $data = $this->mapValidatedToProspectRow($validated, $request);
        unset($data['token'], $data['prospect'], $data['prospect_submitted_at']);
        $entreprise->fill($data);
        $entreprise->save();
        $this->syncAppuisEtProduits($entreprise, $request);

        Session::flash('success', 'Fiche prospect mise à jour.');

        return redirect()->route('gestionnaire.entreprises.show', $entreprise->token);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProspectFields(Request $request): array
    {
        $today = now()->toDateString();
        $adultLimitDate = now()->subYears(18)->toDateString();

        $request->merge([
            'rccm' => $this->normalizeIdentifier($request->input('rccm')),
            'niu' => $this->normalizeIdentifier($request->input('niu')),
            'phone' => $this->normalizeCameroonPhone($request->input('phone')),
            'mm_phone' => $this->normalizeCameroonPhone($request->input('mm_phone')),
            'forme_id' => $request->input('forme_id') === '' ? null : $request->input('forme_id'),
            'arrondissement_id' => $request->input('arrondissement_id') === '' ? null : $request->input('arrondissement_id'),
            'produit_id' => $request->input('produit_id') === '' ? null : $request->input('produit_id'),
        ]);

        return $request->validate([
            'name' => 'nullable|string|max:255',
            'arrondissement_id' => ['nullable', 'integer', Rule::exists(Arrondissement::class, 'id')],
            'village_ou_quartier' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:100',
            'longitude' => 'nullable|string|max:100',
            'taille' => 'nullable|string|max:30',
            'caractere' => 'nullable|in:Formel,Informel',
            'rccm' => ['nullable', 'string', 'max:100', 'regex:/^RC\/[A-Z0-9-]+\/\d{4}\/[A-Z0-9]+\/\d+$/i'],
            'niu' => ['nullable', 'string', 'max:100', 'regex:/^[A-Z][A-Z0-9]{10,19}$/i'],
            'cnps' => 'nullable|string|max:100',
            'mm_phone' => ['nullable', 'string', 'max:50', 'regex:/^(?:\+237)?6\d{8}$/'],
            'manager' => 'nullable|string|max:255',
            'phone' => ['nullable', 'string', 'max:50', 'regex:/^(?:\+237)?(?:2|6)\d{8}$/'],
            'email' => 'nullable|email|max:100',
            'forme_id' => 'nullable|integer|min:0',
            'systeme' => 'nullable|in:Normal,Minimal',
            'capital' => 'nullable|numeric',
            'chiffre_affaire' => 'nullable|numeric',
            'dt_creation' => 'nullable|date|before_or_equal:'.$today,
            'dt_start' => 'nullable|date|before_or_equal:'.$today,
            'ressources_propres' => 'nullable|numeric',
            'total_actif' => 'nullable|numeric',
            'nb_personnel' => 'nullable|integer|min:0',
            'nb_personnel_permanent' => 'nullable|integer|min:0',
            'nb_personnel_saisonier' => 'nullable|integer|min:0',
            'manager_sexe' => 'nullable|in:Homme,Femme',
            'manager_contact' => 'nullable|string|max:100',
            'manager_niveau' => 'nullable|string|max:50',
            'manager_dtn' => 'nullable|date|before_or_equal:'.$adultLimitDate,
            'manager_promoteur' => 'nullable|in:0,1',
            'produit_id' => 'nullable|integer|min:0',
            'produit_year_start' => 'nullable|integer|min:0',
            'type_personnel' => 'nullable|in:permanent,saisonier,mixte',
            'autres' => 'nullable|array',
            'autres.*' => 'integer|min:1',
            'appuisf' => 'nullable|array',
            'appuisf.*' => 'integer|min:1',
            'appuisnf' => 'nullable|array',
            'appuisnf.*' => 'integer|min:1',
        ], [
            'rccm.regex' => 'Le RCCM doit respecter un format camerounais valide, par exemple RC/YAO/2024/B/123.',
            'niu.regex' => 'Le NIU doit respecter un format camerounais valide, par exemple M123456789012A.',
            'phone.regex' => 'Le telephone doit suivre la numerotation camerounaise, par exemple 6XXXXXXXX ou +2376XXXXXXXX.',
            'mm_phone.regex' => 'Le numero Mobile Money doit suivre la numerotation camerounaise mobile, par exemple 6XXXXXXXX ou +2376XXXXXXXX.',
            'dt_creation.before_or_equal' => 'La date de creation formelle ne peut pas etre dans le futur.',
            'dt_start.before_or_equal' => 'La date de debut des activites ne peut pas etre dans le futur.',
            'manager_dtn.before_or_equal' => 'La date de naissance du dirigeant doit etre coherente: le dirigeant doit etre majeur.',
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     * @return array<string, mixed>
     */
    private function mapValidatedToProspectRow(array $validated, Request $request): array
    {
        $name = trim((string) ($validated['name'] ?? ''));
        if ($name === '') {
            $name = 'Prospect (à compléter)';
        }

        $arrId = isset($validated['arrondissement_id']) && $validated['arrondissement_id'] !== null
            ? (int) $validated['arrondissement_id']
            : null;
        $depId = null;
        $regId = null;
        if ($arrId !== null && $arrId > 0) {
            $ar = Arrondissement::with('departement')->find($arrId);
            if ($ar) {
                $depId = $ar->departement_id;
                $regId = $ar->departement->region_id;
            }
        }

        $formeRaw = $request->input('forme_id', $validated['forme_id'] ?? null);
        $formeId = ($formeRaw === '' || $formeRaw === null) ? null : (int) $formeRaw;

        $taille = $validated['taille'] ?? null;
        if ($taille === '') {
            $taille = null;
        }

        $personnelPermanent = false;
        $personnelSaisonier = false;
        $personnelMixte = false;
        $tp = $validated['type_personnel'] ?? $request->input('type_personnel');
        if ($tp === 'saisonier') {
            $personnelSaisonier = true;
        } elseif ($tp === 'mixte') {
            $personnelMixte = true;
        } elseif ($tp === 'permanent') {
            $personnelPermanent = true;
        }

        return $this->appendOptionalProspectColumns([
            'name' => $name,
            'arrondissement_id' => $arrId,
            'departement_id' => $depId,
            'region_id' => $regId,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'forme_id' => $formeId,
            'taille' => $taille,
            'caractere' => $validated['caractere'] ?? null,
            'rccm' => $validated['rccm'] ?? null,
            'niu' => $validated['niu'] ?? null,
            'cnps' => $validated['cnps'] ?? null,
            'mm_phone' => $validated['mm_phone'] ?? null,
            'manager' => $validated['manager'] ?? null,
            'phone' => $validated['phone'] ?? null,
            'email' => $validated['email'] ?? null,
            'systeme' => $validated['systeme'] ?? null,
            'capital' => $validated['capital'] ?? null,
            'chiffre_affaire' => $validated['chiffre_affaire'] ?? null,
            'dt_creation' => $validated['dt_creation'] ?? null,
            'dt_start' => $validated['dt_start'] ?? null,
            'ressources_propres' => $validated['ressources_propres'] ?? null,
            'total_actif' => $validated['total_actif'] ?? null,
            'nb_personnel' => $validated['nb_personnel'] ?? null,
            'nb_personnel_permanent' => $validated['nb_personnel_permanent'] ?? null,
            'nb_personnel_saisonier' => $validated['nb_personnel_saisonier'] ?? null,
            'manager_sexe' => $validated['manager_sexe'] ?? null,
            'manager_contact' => $validated['manager_contact'] ?? null,
            'manager_niveau' => $validated['manager_niveau'] ?? null,
            'manager_dtn' => $validated['manager_dtn'] ?? null,
            'manager_promoteur' => isset($validated['manager_promoteur'])
                ? (bool) (int) $validated['manager_promoteur']
                : null,
            'produit_id' => array_key_exists('produit_id', $validated) && $validated['produit_id'] !== null
                ? (int) $validated['produit_id']
                : null,
            'produit_year_start' => $validated['produit_year_start'] ?? null,
            'personnel_permanent' => $personnelPermanent,
            'personnel_saisonier' => $personnelSaisonier,
            'personnel_mixte' => $personnelMixte,
        ], $validated);
    }

    /**
     * @return array<string, mixed>
     */
    private function appendOptionalProspectColumns(array $payload, array $validated): array
    {
        if (Schema::connection('central_app_mysql')->hasColumn('entreprises', 'village_ou_quartier')) {
            $payload['village_ou_quartier'] = $validated['village_ou_quartier'] ?? null;
        }

        return $payload;
    }

    private function syncAppuisEtProduits(Entreprise $entreprise, Request $request): void
    {
        $anfs = $this->normalizeSelectionInput($request->input('appuisnf', []));
        $afs = $this->normalizeSelectionInput($request->input('appuisf', []));
        $produits = $this->normalizeSelectionInput($request->input('autres', []));

        EntrepriseAppui::where('entreprise_id', $entreprise->id)->delete();
        EntrepriseProduit::where('entreprise_id', $entreprise->id)->delete();

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
    }

    /**
     * Soumission explicite du dossier prospect (horodatée) pour instruction juridique / conformité.
     */
    public function submitProspect(string $token)
    {
        $item = Entreprise::where('token', $token)->where('prospect', true)->first();
        if (! $item) {
            abort(404);
        }
        $uid = auth()->id();
        if ((int) $item->gestionnaire_id !== (int) $uid && (int) $item->user_id !== (int) $uid) {
            abort(403);
        }
        if ($item->prospect_submitted_at !== null) {
            Session::flash('info', 'Ce prospect a déjà été soumis.');

            return redirect()->route('gestionnaire.entreprises.show', $token);
        }

        $item->prospect_submitted_at = now();
        $item->save();

        Session::flash('success', 'Prospect soumis le '.$item->prospect_submitted_at->format('d/m/Y \à H:i').'.');

        return redirect()->route('gestionnaire.entreprises.show', $token);
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->orderBy('created_at', 'DESC')->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    /**
     * Base query for gestionnaire's enterprises (prospect=0, user's or gestionnaire's)
     */
    private function baseQuery()
    {
        $userId = auth()->user()->id;

        return Entreprise::where('prospect', 0)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)->orWhere('gestionnaire_id', $userId);
            });
    }

    /**
     * Stats for the dashboard cards (AJAX)
     */
    public function fetchStats(Request $request)
    {
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);

        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
            'par_region' => (clone $query)->leftJoin('regions', 'entreprises.region_id', '=', 'regions.id')
                ->select('regions.name', DB::raw('count(*) as count'))
                ->groupBy('regions.id', 'regions.name')
                ->pluck('count', 'name')->toArray(),
        ];

        return response()->json($stats);
    }

    /**
     * Paginated list with filters (DataTables server-side format)
     */
    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $recordsTotal = $this->baseQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
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

        $items = $query->orderBy('created_at', 'DESC')
            ->skip($start)
            ->take($length)
            ->get();

        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function parseFilters(Request $request): array
    {
        return [
            'region_id' => $request->input('region_id'),
            'taille' => $request->input('taille'),
            'forme_id' => $request->input('forme_id'),
            'caractere' => $request->input('caractere'),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (! empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (! empty($filters['taille'])) {
            $query->where('taille', $filters['taille']);
        }
        if (! empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }
        if (! empty($filters['caractere'])) {
            $query->where('caractere', $filters['caractere']);
        }

        return $query;
    }

    /**
     * Filter options for dropdowns (regions, formes)
     */
    public function fetchFilterOptions()
    {
        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'tailles' => ['GRANDE', 'MOYENNE', 'PETITE', 'TRES PETITE', 'COOPERATIVE', 'ASSOCIATION'],
            'caracteres' => ['Formel', 'Informel'],
        ]);
    }

    public function fetchProspects()
    {
        $items = $this->prospectsQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    /**
     * Base query for prospects (prospect=1)
     */
    private function prospectsQuery()
    {
        $uid = auth()->id();

        return Entreprise::where('prospect', 1)
            ->where(function ($q) use ($uid) {
                $q->where('gestionnaire_id', $uid)->orWhere('user_id', $uid);
            });
    }

    /**
     * Stats for prospects (AJAX)
     */
    public function fetchProspectsStats(Request $request)
    {
        $base = $this->prospectsQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
            'par_region' => (clone $query)->leftJoin('regions', 'entreprises.region_id', '=', 'regions.id')
                ->select('regions.name', DB::raw('count(*) as count'))
                ->groupBy('regions.id', 'regions.name')
                ->pluck('count', 'name')->toArray(),
        ];

        return response()->json($stats);
    }

    /**
     * Paginated prospects (DataTables server-side)
     */
    public function fetchProspectsPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->prospectsQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $recordsTotal = $this->prospectsQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
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

        $items = $query->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Toute création passe par le parcours prospect (alignement cahier des charges BC-PME).
     */
    public function create()
    {
        return redirect()->route('gestionnaire.entreprises.prospects.create');
    }

    /**
     * Ancien formulaire multi-étapes : même traitement que l'enregistrement prospect (brouillon).
     */
    public function store(Request $request)
    {
        return $this->storeProspect($request);
    }

    public function save(Request $request)
    {
        $token = $request->input('token');
        $item = $token ? Entreprise::where('token', $token)->first() : null;
        if ($item && $item->prospect) {
            if ($item->prospect_submitted_at !== null) {
                Session::flash('info', 'Ce prospect est déjà soumis pour avis ; la fiche n’est plus modifiable par ce formulaire.');

                return redirect()->route('gestionnaire.entreprises.show', $token);
            }

            return $this->updateProspectDraft($request, $item);
        }

        $data = $request->except('_token', 'type_personnel');
        $type_personnel = $request->type_personnel;
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['personnel_'.$type_personnel] = 1;
        Entreprise::updateOrCreate(['token' => $data['token']], $data);

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('gestionnaire.entreprises.index'));
    }

    private function parse($eng, $id)
    {

        $data = [
            'id' => $eng->id,
            'name' => $eng->name,
            'montant' => $eng->montant ?? 0,
            'encours_montant' => $eng->encours_montant ?? 0,
            'encours_impaye' => $eng->encours_impaye ?? 0,
            'sollicite_montant' => $eng->sollicite_montant ?? 0,
            'variation' => $eng->variation,
            'parent_id' => $eng->parent_id,
            'is_title' => $eng->is_title,
            'is_leaf' => $eng->is_leaf,
            'niveau' => $eng->niveau,
        ];
        if ($data['is_leaf']) {
            $elts = EngagementEntreprise::with('banque')->where('engagement_id', $eng->id)->where('entreprise_id', $id)->get();
            $data['encours_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_montant ?? 0);
            }, 0);
            $data['sollicite_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->sollicite_montant ?? 0);
            }, 0);
            $data['encours_impaye'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_impaye ?? 0);
            }, 0);
            $data['elts'] = $elts->map(function ($elt) {
                return [
                    'banque_name' => $elt->banque?->name ?? '—',
                    'encours_montant' => $elt->encours_montant ?? 0,
                    'encours_impaye' => $elt->encours_impaye ?? 0,
                    'encours_dt_validite' => $elt->encours_dt_validite ?? '—',
                    'sollicite_montant' => $elt->sollicite_montant ?? 0,
                    'sollicite_dt_validite' => $elt->sollicite_dt_validite ?? '—',
                ];
            })->values()->toArray();
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];

        } else {
            $data['children'] = $eng->children->map(function ($child) use ($id) {
                return $this->parse($child, $id);
            });
            foreach ($data['children'] as $child) {
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }

        return $data;
    }

    public function getEngagementReport($token)
    {
        $entreprise = Entreprise::where('token', $token)->first();
        if ($entreprise) {
            $engagements = Engagement::where('parent_id', 0)->get();
            $data = [];
            foreach ($engagements as $eng) {
                $data[] = $this->parse($eng, $entreprise->id);
            }
            // dd($data);

            $engagements = $data;
            $banques = Banque::all();

            // $engagements = EngagementEntreprise::where('entreprise_id',$entreprise->id)->get();
            return view('Gestionnaire.Companies.engagement_report', compact('engagements', 'entreprise', 'banques'));
        } else {
            return back();
        }

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
        $item->load(['promuClientUser', 'prospectRejectedUser']);
        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();
        if ($item->prospect) {
            $item->load([
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'arrondissement',
                'departement',
                'region',
                'forme',
                'agence.representation',
                'produit',
                'produits',
                'appuis.type',
                'reponses.question',
                'reponses.choice',
            ]);

            return view('Gestionnaire.Companies.show_prospect', compact('item', 'mr', 'checklist'));
        }
        // dd($item);
        $appuis = Service::all();
        $elements = ElementConstitutif::where('active', 1)->get();

        $item->load([
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
        ]);

        return view('/Gestionnaire/Companies/show', compact('item', 'mr', 'appuis', 'elements', 'checklist'));

    }

    private function buildQuestionnaireResults(Entreprise $item)
    {
        $reponses = $item->reponses()->with(['question', 'choice'])->get();

        return $reponses->groupBy('critere_id')->map(function ($items, $critereId) {
            $critere = InstructionCritere::find($critereId);

            return [
                'critere' => $critere,
                'items' => $items->groupBy('sous_critere_id')->map(function ($group, $sousCritereId) {
                    $sousCritere = QuestionSousCritere::find($sousCritereId);

                    return [
                        'sous_critere' => $sousCritere,
                        'items' => $group,
                    ];
                }),
            ];
        });
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

    public function saveAppui(Request $request)
    {

        EntrepriseAppui::create([
            'entreprise_id' => $request->entreprise_id,
            'service_id' => $request->appui_id,
        ]);

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    public function addElement(Request $request)
    {
        $token = sha1(time().auth()->user()->id);
        EntrepriseElementConstitutif::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'type_id' => $request->type_id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'type_id' => $request->type_id,
                'uri' => $this->entityDocumentCreate($request->fichier, 'elements_constitutifs', $token),
                'token' => $token,
            ]
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

        return view('/Gestionnaire/Companies/tiers_physique', compact('item'));
    }

    public function createTiersMorale(string $token)
    {
        $parentEntreprise = Entreprise::where('token', $token)->first();
        if (! $parentEntreprise) {
            return back();
        }

        return view('Gestionnaire.Companies.tiers_morale', array_merge(
            ['parentEntreprise' => $parentEntreprise],
            $this->prospectFormContext()
        ));
    }

    public function searchTierMoralePortfolio(Request $request)
    {
        $query = trim((string) $request->input('q', ''));
        $excludeId = (int) $request->input('exclude_id', 0);

        if (mb_strlen($query) < 2) {
            return response()->json(['data' => []]);
        }

        $items = Entreprise::query()
            ->with(['agence:id,name', 'representation:id,name'])
            ->when($excludeId > 0, fn ($builder) => $builder->where('id', '!=', $excludeId))
            ->where(function ($builder) use ($query) {
                $builder->where('name', 'like', "%{$query}%")
                    ->orWhere('rccm', 'like', "%{$query}%")
                    ->orWhere('niu', 'like', "%{$query}%")
                    ->orWhere('manager', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%");
            })
            ->orderByRaw('case when prospect = 1 then 0 else 1 end')
            ->orderBy('name')
            ->limit(12)
            ->get([
                'id',
                'name',
                'token',
                'prospect',
                'rccm',
                'niu',
                'email',
                'phone',
                'manager',
            ]);

        return response()->json([
            'data' => $items->map(fn (Entreprise $entreprise) => [
                'id' => $entreprise->id,
                'name' => $entreprise->name ?: 'Denomination non renseignee',
                'token' => $entreprise->token,
                'prospect' => (bool) $entreprise->prospect,
                'status_label' => $entreprise->prospect ? 'Prospect' : 'Client',
                'rccm' => $entreprise->rccm,
                'niu' => $entreprise->niu,
                'email' => $entreprise->email,
                'phone' => $entreprise->phone,
                'manager' => $entreprise->manager,
                'agence' => $entreprise->agence?->name,
                'representation' => $entreprise->representation?->name,
                'show_url' => route('gestionnaire.entreprises.show', $entreprise->token),
            ])->values(),
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

        return redirect(route('gestionnaire.entreprises.show', $token));
    }

    public function saveTiersMorale(Request $request)
    {
        $meta = $request->validate([
            'entreprise_id' => ['required', 'integer'],
            'token' => ['required', 'string'],
            'tier_mode' => ['required', 'in:existing,new'],
            'lien' => ['required', 'string', 'max:255'],
            'commentaire' => ['nullable', 'string', 'max:1000'],
        ], [
            'lien.required' => 'Le lien entre les deux entreprises est obligatoire.',
        ]);

        $parentEntreprise = Entreprise::where('id', $meta['entreprise_id'])
            ->where('token', $meta['token'])
            ->firstOrFail();

        if ($meta['tier_mode'] === 'existing') {
            $existing = $request->validate([
                'selected_company_id' => ['required', 'integer', Rule::exists(Entreprise::class, 'id')],
            ], [
                'selected_company_id.required' => 'Selectionnez un prospect ou un client existant a lier.',
            ]);

            $entreprise = Entreprise::findOrFail((int) $existing['selected_company_id']);

            if ((int) $entreprise->id === (int) $parentEntreprise->id) {
                return back()
                    ->withErrors(['selected_company_id' => 'Vous ne pouvez pas lier une entreprise a elle-meme.'])
                    ->withInput();
            }

            Tier::updateOrCreate(
                [
                    'entreprise_id' => $parentEntreprise->id,
                    'company_id' => $entreprise->id,
                ],
                $this->tierMoraleRelationPayload($parentEntreprise->id, $entreprise->id, $meta)
            );

            Session::flash('success', 'Le tiers personne morale existant a ete lie a la fiche avec succes.');

            return redirect(route('gestionnaire.entreprises.show', $parentEntreprise->token));
        }

        $validated = $this->validateProspectFields($request);
        $prospectData = $this->mapValidatedToProspectRow($validated, $request);
        if (empty($prospectData['systeme'])) {
            $prospectData['systeme'] = 'Normal';
        }

        $linkedExisting = false;

        DB::transaction(function () use ($prospectData, $request, $meta, $parentEntreprise, &$linkedExisting) {
            $entreprise = Entreprise::query()
                ->where('id', '!=', $parentEntreprise->id)
                ->where(function ($query) use ($prospectData) {
                    $hasIdentifier = false;

                    foreach (['rccm', 'niu', 'email', 'phone'] as $field) {
                        if (! empty($prospectData[$field])) {
                            $query->orWhere($field, $prospectData[$field]);
                            $hasIdentifier = true;
                        }
                    }

                    if (! $hasIdentifier) {
                        $query->whereRaw('1 = 0');
                    }
                })
                ->first();

            if ($entreprise) {
                $linkedExisting = true;

                if ($entreprise->prospect && ((int) $entreprise->gestionnaire_id === (int) auth()->id() || (int) $entreprise->user_id === (int) auth()->id())) {
                    $entreprise->fill($prospectData);
                    $entreprise->save();
                    $this->syncAppuisEtProduits($entreprise, $request);
                }
            } else {
                $entreprise = Entreprise::create(array_merge($prospectData, [
                    'token' => sha1(time().rand(1, 9999)),
                    'prospect' => true,
                    'prospect_submitted_at' => null,
                    'user_id' => auth()->id(),
                    'gestionnaire_id' => auth()->id(),
                    'agence_id' => auth()->user()->agence_id,
                    'representation_id' => auth()->user()->representation_id,
                ]));

                $this->syncAppuisEtProduits($entreprise, $request);
            }

            Tier::updateOrCreate(
                [
                    'entreprise_id' => $parentEntreprise->id,
                    'company_id' => $entreprise->id,
                ],
                $this->tierMoraleRelationPayload($parentEntreprise->id, $entreprise->id, $meta)
            );
        });

        Session::flash(
            'success',
            $linkedExisting
                ? 'Un tiers deja present dans le portefeuille a ete detecte puis lie a la fiche pour eviter un doublon.'
                : 'Le tiers personne morale a ete cree et lie a la fiche avec succes.'
        );

        return redirect(route('gestionnaire.entreprises.show', $parentEntreprise->token));
    }

    public function createQuestionnaire(string $token)
    {
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteresPrincipaux = InstructionCritere::query()
            ->whereHas('questionnaireSousCriteres')
            ->with([
                'questionnaireSousCriteres' => function ($q) {
                    $q->orderBy('id')->with(['questions.choices']);
                },
            ])
            ->orderBy('id')
            ->get();
        $reponses = $item->reponses()->pluck('choice_id', 'question_id')->toArray() ?? [];

        return view('Gestionnaire.Companies.questionnaire', compact('item', 'criteresPrincipaux', 'reponses'));
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
    public function edit(string $token)
    {
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        if ($item->prospect) {
            $item->load(['produits:id,name,code', 'appuis:id,name,financier,type_id']);

            return view('Gestionnaire.Companies.edit_prospect', array_merge(
                ['item' => $item],
                $this->prospectFormContext()
            ));
        }
        $formes = Forme::all();

        return view('Gestionnaire.Companies.edit', compact('item', 'formes'));
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

    /**
     * @return array<string, mixed>
     */
    private function prospectFormContext(): array
    {
        return [
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'departements' => Departement::orderBy('name')->get(['id', 'name', 'region_id']),
            'arrondissements' => Arrondissement::orderBy('name')->get(['id', 'name', 'departement_id']),
            'produitsCatalogue' => Produit::query()->orderBy('code')->orderBy('name')->get(['id', 'name', 'code']),
            'appuisFinanciers' => Service::with('type')->where('financier', 1)->orderBy('name')->get(),
            'appuisNonFinanciers' => Service::with('type')->where('financier', 0)->orderBy('name')->get(),
        ];
    }

    /**
     * @param  array<int|string, mixed>|string|null  $raw
     * @return array<int, int>
     */
    private function normalizeSelectionInput(array|string|null $raw): array
    {
        if (is_string($raw)) {
            $raw = $raw === '' ? [] : explode(',', $raw);
        }

        if (! is_array($raw)) {
            return [];
        }

        return array_values(array_filter(array_map(function ($value) {
            if ($value === null || $value === '') {
                return null;
            }

            return (int) $value;
        }, $raw), fn ($value) => $value !== null && $value > 0));
    }

    /**
     * @param  array<string, mixed>  $meta
     * @return array<string, mixed>
     */
    private function tierMoraleRelationPayload(int $entrepriseId, int $companyId, array $meta): array
    {
        $payload = [
            'entreprise_id' => $entrepriseId,
            'company_id' => $companyId,
            'lien' => $meta['lien'],
        ];

        if (Schema::connection('central_app_mysql')->hasTable('tiers') && Schema::connection('central_app_mysql')->hasColumn('tiers', 'commentaire')) {
            $payload['commentaire'] = $meta['commentaire'] ?? null;
        }

        return $payload;
    }

    private function normalizeCameroonPhone(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return preg_replace('/[\s\-\.]/', '', $value);
    }

    private function normalizeIdentifier(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return strtoupper($value);
    }
}
