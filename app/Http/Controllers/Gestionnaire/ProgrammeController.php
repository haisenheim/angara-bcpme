<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProgrammeListResource;
use App\Models\Banque;
use App\Models\Composante;
use App\Models\Indicateur;
use App\Models\Organisme;
use App\Models\Programme;
use App\Models\ProgrammeAppui;
use App\Models\ProgrammeIndicateur;
use App\Models\ProgrammeOrgamisme;
use App\Models\ProgrammeProduit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProgrammeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Gestionnaire/Programmes/index');
    }


    public function fetchAll(){
        $items = Programme::all();
        $items = ProgrammeListResource::collection($items);
        return response()->json($items);
    }

    public function fetchFilterOptions()
    {
        $signataires = Programme::query()
            ->whereNotNull('signataire')
            ->where('signataire', '!=', '')
            ->distinct()
            ->orderBy('signataire')
            ->pluck('signataire')
            ->values();

        return response()->json(['signataires' => $signataires]);
    }

    public function fetchStats(Request $request)
    {
        $filters = $this->parseProgrammeFilters($request);
        $query = $this->applyProgrammeFilters(Programme::query(), $filters);

        return response()->json([
            'total' => (clone $query)->count(),
            'actifs' => (clone $query)->where('active', true)->count(),
            'avec_convention' => (clone $query)->whereNotNull('convention')->where('convention', '!=', '')->count(),
        ]);
    }

    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim((string) $request->input('search.value', ''));

        $filters = $this->parseProgrammeFilters($request);
        $query = $this->applyProgrammeFilters(Programme::query(), $filters);

        $recordsTotal = Programme::query()->count();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('convention', 'like', "%{$search}%")
                    ->orWhere('signataire', 'like', "%{$search}%")
                    ->orWhere('type_pp', 'like', "%{$search}%")
                    ->orWhere('type_pm', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $orderColumnIndex = (int) $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc') === 'desc' ? 'desc' : 'asc';
        $columnMap = ['name', 'convention', 'signataire', 'dt_sig_conv', 'budget', 'type_pp', 'type_pm'];
        $orderBy = $columnMap[$orderColumnIndex] ?? 'name';
        $query->orderBy($orderBy, $orderDir);

        $items = $query->skip($start)->take($length)->get();
        $resolved = ProgrammeListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function parseProgrammeFilters(Request $request): array
    {
        return [
            'signataire' => $request->input('signataire_filter'),
        ];
    }

    private function applyProgrammeFilters($query, array $filters)
    {
        if (! empty($filters['signataire'])) {
            $query->where('signataire', $filters['signataire']);
        }

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('/Gestionnaire/Programmes/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        //dd($request->all());
        $data = $request->except('_token','appuisnf','appuisf','produits','bailleurs','type_entreprise','type_personne');
        $anfs = explode(',',$request->appuisnf);
        $afs = explode(',',$request->appuisf);
        $produits = explode(',',$request->produits);
        $bailleurs = explode(',',$request->bailleurs);
        $data['token'] = sha1(time().rand(0,99));
        $data['user_id'] = auth()->user()->id;
        $data['type_pp'] = implode('-',$request->type_personne);
        $data['type_pm'] = implode('-',$request->type_entreprise);
        $item = Programme::create($data);

        foreach($afs as $a){
            ProgrammeAppui::create([
                'programme_id'=>$item->id,
                'service_id'=>$a
            ]);
        }
        foreach($anfs as $a){
            ProgrammeAppui::create([
                'programme_id'=>$item->id,
                'service_id'=>$a
            ]);
        }
        foreach($produits as $a){
            ProgrammeProduit::create([
                'programme_id'=>$item->id,
                'produit_id'=>$a
            ]);
        }

        foreach($bailleurs as $a){
            ProgrammeOrgamisme::create([
                'programme_id'=>$item->id,
                'organisme_id'=>$a
            ]);
        }
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('gestionnaire.programmes.show',$item->token));

    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        //
        $item = Programme::where('token',$token)->first();
        if(!$item){
            Session::flash('error','Accès non autorisé à ce programme!');
            return back();
        }
        $banques = Banque::all();
        $organismes = Organisme::all();
        $indicateurs = Indicateur::all();
        return view('Gestionnaire/Programmes/show',compact('item','banques','organismes','indicateurs'));
    }


    public function saveComposante(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('token');
        Composante::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'banque_id'=>$request->banque_id??0,
            'organisme_id'=>$request->organisme_id??0
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('gestionnaire.programmes.show',$token));
    }

    public function saveResultat(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('token');
        ProgrammeIndicateur::updateOrCreate(
            [
            'programme_id'=>$request->programme_id,
            'indicateur_id'=>$request->banque_id??0,
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('gestionnaire.programmes.show',$token));
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
