<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Banque;
use App\Models\Critere;
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
use App\Models\Programme;
use App\Models\Question;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Service;
use App\Models\Tier;
use App\Models\User;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

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


    public function fetchAll(){
        $items = $this->baseQuery()->orderBy('created_at','DESC')->get();
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
        if (!empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (!empty($filters['taille'])) {
            $query->where('taille', $filters['taille']);
        }
        if (!empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }
        if (!empty($filters['caractere'])) {
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

    public function fetchProspects(){
        $items = Entreprise::where('prospect',1)->get();
        $items = EntrepriseListResource::collection($items);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('/Gestionnaire/Companies/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token','appuisnf','appuisf','autres','type_personnel');
        $anfs = array_filter(explode(',', $request->appuisnf ?? ''));
        $afs = array_filter(explode(',', $request->appuisf ?? ''));
        $produits = array_filter(explode(',', $request->autres ?? ''));
        $type_personnel = $request->type_personnel;
        $data['token'] = sha1(time().rand(0,99));
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['user_id'] = auth()->user()->id;
        $data['gestionnaire_id'] = auth()->user()->id;
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
        return redirect(route('gestionnaire.entreprises.index'));
    }

    public function save(Request $request)
    {
        //
       $data = $request->except('_token','type_personnel');
        $type_personnel = $request->type_personnel;
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['personnel_'.$type_personnel] = 1;
        $entreprise = Entreprise::updateOrcreate(['token'=>$data['token']],$data);

        //dd($data);
        Session::flash('success','Enregistrement effectué avec succès!');
        //return back();
        return redirect(route('gestionnaire.entreprises.index'));
    }

    private function parse($eng,$id){

        $data = [
            'id'=>$eng->id,
            'name'=>$eng->name,
            'montant'=>$eng->montant??0,
            'encours_montant'=>$eng->encours_montant??0,
            'encours_impaye'=>$eng->encours_impaye??0,
            'sollicite_montant'=>$eng->sollicite_montant??0,
            'variation'=>$eng->variation,
            'parent_id'=>$eng->parent_id,
            'is_title'=>$eng->is_title,
            'is_leaf'=>$eng->is_leaf,
            'niveau'=>$eng->niveau,
        ];
        if($data['is_leaf']){
            $elts = EngagementEntreprise::where('engagement_id',$eng->id)->where('entreprise_id',$id)->get();
            //dd($elts);
            $data['encours_montant'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_montant;
            },0);
            $data['sollicite_montant']= $elts->reduce(function($carry,$item){
                return $carry + $item->sollicite_montant;
            },0);
            $data['encours_impaye'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_impaye;
            },0);
            $data['elts'] = $elts;
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];

        }else{
            $data['children'] = $eng->children->map(function($child)use($id){
                return $this->parse($child,$id);
            });
            foreach($data['children'] as $child){
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }
        return $data;
    }

    public function getEngagementReport($token){
        $entreprise = Entreprise::where('token',$token)->first();
        if($entreprise){
            $engagements = Engagement::where('parent_id',0)->get();
             $data = [];
             foreach($engagements as $eng){
                 $data[] = $this->parse($eng,$entreprise->id);
             }
             //dd($data);

            $engagements = $data;
            $banques = Banque::all();
            //$engagements = EngagementEntreprise::where('entreprise_id',$entreprise->id)->get();
            return view('Gestionnaire.Companies.engagement_report',compact('engagements','entreprise','banques'));
        }else{
            return back();
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        //dd($item);
        $reponses = $item->reponses;
        $groups = $reponses->groupBy('critere_id');
        $groups = $groups->map(function($v,$k){
            $critere = InstructionCritere::find($k);
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
        $analystes = User::where('role_id',14)->where('agence_id',auth()->user()->agence_id)->get();
        $programmes = Programme::all();
        $appuis = Service::all();
        $elements = ElementConstitutif::where('active',1)->get();
        return view('/Gestionnaire/Companies/show',compact('item','mr','programmes','analystes','appuis','elements'));

    }

    public function saveProgramme(Request $request)
    {
        //$token = $request->token;
        //dd($request->all());
        $data = $request->all();
        $data['token']=sha1(time().rand(1,100));
        $data['gestionnaire_id'] = auth()->user()->id;
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

    public function saveAppui(Request $request)
    {

        EntrepriseAppui::create([
            'entreprise_id'=>$request->entreprise_id,
            'service_id'=>$request->appui_id
        ]);

        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }


    public function addElement(Request $request)
    {
        $token = sha1(time().auth()->user()->id);
        EntrepriseElementConstitutif::updateOrCreate(
            [
                'entreprise_id'=>$request->entreprise_id,
                'type_id'=>$request->type_id,
            ],
            [
                'entreprise_id'=>$request->entreprise_id,
                'type_id'=>$request->type_id,
                'uri'=>$this->entityDocumentCreate($request->fichier,'elements_constitutifs',$token),
                'token'=>$token
            ]
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
        return view('/Gestionnaire/Companies/tiers_physique',compact('item'));
    }

    public function createTiersMorale(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        $formes = Forme::all();
        return view('/Gestionnaire/Companies/tiers_morale',compact('item','formes'));
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
        return redirect(route('gestionnaire.entreprises.show',$token));
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
        return redirect(route('gestionnaire.entreprises.show',$token));
    }


    public function createQuestionnaire(string $token)
    {
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        $criteres = QuestionSousCritere::all();
        $reponses = $item->reponses()->pluck('choice_id', 'question_id')->toArray() ?? [];
        return view('/Gestionnaire/Companies/questionnaire',compact('item','criteres','reponses'));
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
    public function edit(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if($item){
            $formes = Forme::all();
            return view('Gestionnaire.Companies.edit',compact('item','formes'));
        }
        return back();
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
