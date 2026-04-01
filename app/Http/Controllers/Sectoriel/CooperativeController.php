<?php

namespace App\Http\Controllers\Sectoriel;

use App\Exports\Gestionnaire\Cooperatives\EntreeExport;
use App\Exports\Gestionnaire\Cooperatives\PaiementExport;
//use App\Exports\Sectoriel\Cooperatives\EntreeExport;
//use App\Exports\Sectoriel\Cooperatives\PaiementExport;
use App\Http\Controllers\ExtendedController;
use App\Http\Resources\CooperativeListResource;
use App\Http\Resources\Structuration\EntreeResource;
use App\Models\Arrondissement;
use App\Models\Domaine;
use App\Models\Entreprise;
use App\Models\Secteur;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use App\Models\Structuration\Paiement;
use App\Models\Structuration\Request as StructurationRequest;
use App\Models\Structuration\User;
use App\Models\Tenant;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class CooperativeController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$cooperatives = Cooperative::where('sectoriel_id',auth()->user()->id);
        $domaines = Domaine::all();
       // $secteurs = Secteur::where('agence_id',auth()->user()->agence_id)->get();
        return view('Sectoriel/Cooperatives/index')->with(compact('domaines'));
    }


    public function fetchAll(){
        $items = Tenant::where('secteur_id',auth()->user()->secteur_id)->get();
        $items = CooperativeListResource::collection($items);
        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function addCaisse(Request $request){
        $data['name'] = $request->name;
        $data['montant'] = $request->montant;
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        $tenant = Tenant::find($request->cooperative_id);
        $tenant->run(function()use($data){
            Caisse::create($data);
        });
        app()->instance('tenant', $tenant);
        return back();
    }

    public function addWallet(Request $request){
        $data['name'] = $request->name;
        $data['montant'] = $request->montant;
        $data['operateur_id'] = $request->operateur_id;
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        $tenant = Tenant::find($request->cooperative_id);
        $tenant->run(function()use($data){
            Wallet::create($data);
        });
        app()->instance('tenant', $tenant);
        return back();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $secteur = Secteur::find(auth()->user()->secteur_id);
        $ent['name'] = $request->name;
        $ent['token'] = sha1(time().rand(0,99));
        $ar = Arrondissement::find($request->arrondissement_id);
        $ent['departement_id'] = $ar->departement_id;
        $ent['region_id'] = $ar->departement->region_id;
        $ent['user_id'] = auth()->user()->id;
        $ent['taille'] = 'COOPERATIVE';
        $ent['secteur_id'] = auth()->user()->secteur_id;
        $ent['agence_id'] = $secteur->agence_id;
        $ent['representation_id'] = $secteur->representation_id;
        $entreprise = Entreprise::create($ent);

        $data_ = $request->only('name','phone','address','domaine_id');
        $data_['token'] = sha1(time().auth()->user()->id);
        $data_['region_id'] = $ar->region_id;
        $data_['departement_id'] = $ar->departement_id;
        $data_['arrondissement_id'] = $ar->id;
        $data_['entreprise_id'] = $entreprise->id;
        $data_['agence_id'] = $secteur->agence_id;
        $data_['user_id'] = auth()->user()->id;
        $data_['representation_id'] = $secteur->representation_id;
        $photo = request()->photo;
        if($photo){
            $data_['photo_uri'] = $this->entityImgCreate($photo,'cooperatives',$data_['token']);
        }
        $tenant = Tenant::create($data_);
        $tenantSlug = Str::slug($tenant->name, '-');
        $tenant->domains()->create(['domain'=>$tenantSlug.'.'.config('structuration.central_domains')[0]]);

        $data['name'] = $request->username;
        $data['email'] = $request->email;
        $data['password'] = bcrypt($request->password);
        //$data['role_id'] = 1;
        //$data['token'] = sha1(time().rand(1,999));
        $tenant->run(function()use($data){
            $user = new User();
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->role_id = 1;
            $user->token = sha1(time().rand(1,999));
            $user->save();
        });
        app()->instance('tenant', $tenant);
        return back();
    }

    public function exportPaiements(Request $request){
        $from = $request->from;
        $to = $request->to;
        $mode = $request->mode_id;
        $caisse_id = $request->caisse_id;
        $wallet_id = $request->wallet_id;
        $cooperative = Cooperative::find($request->cooperative_id);
        $paiements = Paiement::orderBy('created_at','DESC')->where('cooperative_id',$cooperative->id)->where('saison_id',$this->_saison->id)->whereBetween('created_at',[$from,$to])->get();
        $title = $cooperative->name . "_historique_paiements_" .  " ". $from."_". $to;
        return Excel::download(new PaiementExport($paiements,$cooperative,$from,$to),$title.'.xlsx');
    }

    public function exportEntrees(Request $request){
        $from = $request->from;
        $to = $request->to;
        $entreprot_id = $request->entrepot_id;
        $cooperative = Cooperative::find($request->cooperative_id);
        $items = Entree::orderBy('created_at','DESC')->where('cooperative_id',$cooperative->id)->where('saison_id',$this->_saison->id)->whereBetween('created_at',[$from,$to])->get();
        $title = $cooperative->name . "_historique_entrees_en_stock_" .  " ". $from."_". $to;
        return Excel::download(new EntreeExport($items,$cooperative,$from,$to),$title.'.xlsx');
    }

    public function getEntree($token){
        $item = Entree::where('token',$token)->first();
        return view('Sectoriel.Cooperatives.entree',compact('item'));
    }

    public function getEntrepot(){
        $token = request()->token;
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data = $tenant->run(function()use($token){
            $item = Entrepot::where('token',$token)->first();
            $entrees = Entree::where('entrepot_id',$item->id)->get();
            return [
                'item'=>$item,
                'entrees'=>EntreeResource::collection($entrees)->toJson(),
            ];
        });
        $item = $data['item'];
        $entrees = json_decode($data['entrees'],true);

        //dd($entrees);

        return view('Sectoriel.Cooperatives.entrepot',compact('item','entrees'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = Tenant::where('token',$token)->first();
       // dd($item->caisses);

       $data = $item->run(function(){
            $paiements = Paiement::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            $entrees = Entree::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            $membres = Membre::all();
            $entrepots = Entrepot::all();
            $agents = User::where('role_id',2)->get();
            $caisses = Caisse::all();
            $wallets = Wallet::all();
            $requests = StructurationRequest::orderBy('created_at','DESC')->get();
            return [
                'paiements'=>$paiements,
                'entrees'=>$entrees,
                'membres'=>$membres,
                'entrepots'=>$entrepots,
                'agents'=>$agents,
                'caisses'=>$caisses,
                'wallets'=>$wallets,
                'requests'=>$requests,
            ];
        });
        app()->instance('tenant', $item);
        $operateurs = Operateur::all();
		return view('Sectoriel/Cooperatives/show')->with(compact('item','operateurs','data'));
	}


}
