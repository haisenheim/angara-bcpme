<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Critere;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseProduit;
use App\Models\Forme;
use App\Models\Person;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use App\Models\Programme;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regions = \App\Models\Region::orderBy('name')->get(['id', 'name']);
        $departements = \App\Models\Departement::orderBy('name')->get(['id', 'name', 'region_id']);
        $formes = Forme::orderBy('name')->get(['id', 'name']);
        return view('/Analyste/Companies/index', compact('regions', 'departements', 'formes'));
    }

    public function getProspects()
    {
        //
        return view('/Analyste/Companies/prospects');
    }


    /**
     * Entreprises pour lesquelles l'analyste a des dossiers d'instruction.
     */
    public function fetchAll(){
        $analysteId = auth()->id();
        $items = Entreprise::where('prospect', 0)
            ->whereHas('dossiers', fn($q) => $q->where('analyste_id', $analysteId))
            ->orderBy('name')
            ->get();
        $items = EntrepriseListResource::collection($items);
        return response()->json($items);
    }

    /**
     * Entreprises liées à l'analyste (dossiers d'instruction).
     */
    private function analysteEntreprisesBaseQuery()
    {
        $analysteId = auth()->id();

        return Entreprise::where('prospect', 0)
            ->whereHas('dossiers', fn ($q) => $q->where('analyste_id', $analysteId));
    }

    private function parseEntreprisesIndexFilters(Request $request): array
    {
        return [
            'region_id' => $request->input('region_id'),
            'departement_id' => $request->input('departement_id'),
            'forme_id' => $request->input('forme_id'),
        ];
    }

    private function applyEntreprisesIndexFilters($query, array $filters)
    {
        if (! empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (! empty($filters['departement_id'])) {
            $query->where('departement_id', $filters['departement_id']);
        }
        if (! empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }

        return $query;
    }

    /**
     * Statistiques (filtres région / département / forme, sans recherche textuelle).
     */
    public function fetchEntreprisesIndexStats(Request $request)
    {
        $filters = $this->parseEntreprisesIndexFilters($request);
        $query = $this->applyEntreprisesIndexFilters($this->analysteEntreprisesBaseQuery(), $filters);

        return response()->json([
            'total' => (clone $query)->count(),
            'avec_rccm' => (clone $query)->whereNotNull('rccm')->where('rccm', '!=', '')->count(),
            'avec_niu' => (clone $query)->whereNotNull('niu')->where('niu', '!=', '')->count(),
        ]);
    }

    /**
     * Liste paginée DataTables (server-side) avec filtres.
     */
    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim((string) $request->input('search.value', ''));

        $filters = $this->parseEntreprisesIndexFilters($request);
        $query = $this->applyEntreprisesIndexFilters(
            $this->analysteEntreprisesBaseQuery()->with(['region', 'departement', 'forme', 'arrondissement']),
            $filters
        );

        $recordsTotal = $this->analysteEntreprisesBaseQuery()->count();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $columnMap = ['name', 'rccm', 'niu', 'manager', 'region_id', 'forme_id'];
        $orderBy = $columnMap[$orderColumnIndex] ?? 'name';
        $query->orderBy($orderBy, $orderDir);

        $items = $query->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    public function fetchProspects(){
        $items = $this->prospectsQuery()->get();
        $items = EntrepriseListResource::collection($items);
        return response()->json($items);
    }

    private function prospectsQuery()
    {
        return Entreprise::where('prospect', 1);
    }

    public function fetchProspectsStats(Request $request)
    {
        $base = $this->prospectsQuery();
        $filters = $this->parseFiltersProspects($request);
        $query = $this->applyFiltersProspects($base->clone(), $filters);

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
        $search = trim($request->input('search.value', ''));

        $base = $this->prospectsQuery();
        $filters = $this->parseFiltersProspects($request);
        $query = $this->applyFiltersProspects($base->clone(), $filters);

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
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function parseFiltersProspects(Request $request): array
    {
        return [
            'region_id' => $request->input('region_id'),
            'taille' => $request->input('taille'),
            'forme_id' => $request->input('forme_id'),
            'caractere' => $request->input('caractere'),
        ];
    }

    private function applyFiltersProspects($query, array $filters)
    {
        if (!empty($filters['region_id'])) $query->where('region_id', $filters['region_id']);
        if (!empty($filters['taille'])) $query->where('taille', $filters['taille']);
        if (!empty($filters['forme_id'])) $query->where('forme_id', $filters['forme_id']);
        if (!empty($filters['caractere'])) $query->where('caractere', $filters['caractere']);
        return $query;
    }

    public function fetchFilterOptions()
    {
        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $formes = Forme::all();
        return view('/Analyste/Companies/create',compact('formes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token','appuisnf','appuisf','autres','type_personnel');
        $anfs = explode(',',$request->appuisnf);
        $afs = explode(',',$request->appuisf);
        $produits = explode(',',$request->autres);
        $type_personnel = $request->type_personnel;
        $data['token'] = sha1(time().rand(0,99));
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $data['personnel_'.$type_personnel] = 1;
        $entreprise = Entreprise::create($data);
        foreach($afs as $a){
            EntrepriseAppui::create([
                'entreprise_id'=>$entreprise->id,
                'service_id'=>$a
            ]);
        }
        foreach($anfs as $a){
            EntrepriseAppui::create([
                'entreprise_id'=>$entreprise->id,
                'service_id'=>$a
            ]);
        }
        foreach($produits as $a){
            EntrepriseProduit::create([
                'entreprise_id'=>$entreprise->id,
                'produit_id'=>$a
            ]);
        }
        //dd($data);
        return redirect(route('analyste.entreprises.index'));
    }

    /**
     * Display the specified resource.
     * Accès réservé aux entreprises pour lesquelles l'analyste a des dossiers.
     */
    public function show(string $token)
    {
        $item = Entreprise::where('token', $token)->first();
        if (!$item) {
            return back();
        }

        $dossiersAnalyste = $item->dossiers()->where('analyste_id', auth()->id())->with('programme')->get();
        if ($dossiersAnalyste->isEmpty()) {
            abort(403, 'Vous n\'avez aucun dossier d\'instruction pour cette entreprise.');
        }

        $reponses = $item->reponses;
        $groups = $reponses->groupBy('critere_id');
        $groups = $groups->map(function($v,$k){
            $critere = Critere::find($k);
            return ['critere'=>$critere,
             'items'=>$v->groupBy('sous_critere_id')
                        ->map(function($m,$n){
                            $sc = QuestionSousCritere::find($n);
                            return [
                                'sous_critere'=>$sc,
                                'items'=>$m
                            ];
                        })
            ];
        });
        //dd($groups);
        $mr = $groups;
        $analystes = User::where('role_id', 14)->where('agence_id', auth()->user()->agence_id)->get();
        $programmes = Programme::all();
        return view('/Analyste/Companies/show', compact('item', 'mr', 'programmes', 'analystes', 'dossiersAnalyste'));

    }

    public function saveProgramme(Request $request)
    {
        //$token = $request->token;
        //dd($request->all());
        $data = $request->all();
        $data['token']=sha1(time().rand(1,100));
        $data['analyste_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;

        Dossier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'programme_id'=>$request->programme_id,
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }


    public function createTiersPhysique(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        return view('/Analyste/Companies/tiers_physique',compact('item'));
    }

    public function createTiersMorale(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        $formes = Forme::all();
        return view('/Analyste/Companies/tiers_morale',compact('item','formes'));
    }

    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien','entreprise_id','token');
        $data['token']=sha1(time().rand(1,100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu',$data['niu'])->where('phone',$data['phone'])->first();
        if(!$person){
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'person_id'=>$person->id,
            ],
            [
                'entreprise_id'=>$request->entreprise_id,
                'person_id'=>$person->id,
                'lien'=>$request->lien
            ]
        );

        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('analyste.entreprises.show',$token));
    }

    public function saveTiersMorale(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('lien','entreprise_id','token');
        $data['token'] = sha1(time().rand(1,9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::where('phone',$data['phone'])->orWhere('email',$data['email'])->first();
        if(!$entreprise){
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'company_id'=>$entreprise->id,
            ],
            [
                'entreprise_id'=>$request->entreprise_id,
                'company_id'=>$entreprise->id,
                'lien'=>$request->lien
            ]
        );


        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('analyste.entreprises.show',$token));
    }


    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        $criteres = QuestionSousCritere::all();
        return view('/Analyste/Companies/questionnaire',compact('item','criteres'));
    }

    public function saveQuestionnaire(Request $request)
    {
        //dd($request->choices[1]);
        $choices = $request->choices;
        foreach($choices as $choice){
            QuestionAnswer::updateOrCreate([
                'entreprise_id'=>$choice['entreprise_id'],
                'choice_id'=>$choice['choice_id']
            ],$choice);
        }
        //Session::flash('success','Enregistrement effectué avec succès!');
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
