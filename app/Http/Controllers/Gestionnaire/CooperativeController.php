<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Exports\Gestionnaire\Cooperatives\EntreeExport;
use App\Exports\Gestionnaire\Cooperatives\PaiementExport;
use App\Http\Controllers\ExtendedController;
use App\Http\Resources\CooperativeListResource;
use App\Models\Arrondissement;
use App\Models\Banque;
use App\Models\Domaine;
use App\Models\Entreprise;
use App\Models\Secteur;
use App\Models\Structuration\BanqueCooperative;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use App\Models\Structuration\Paiement;
use App\Models\Structuration\Request as StructurationRequest;
use App\Models\Structuration\Transfert;
use App\Models\Structuration\User;
use App\Models\Tenant;
use App\Models\User as ModelsUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $unions = Tenant::where('is_union',true)->get();
        $domaines = Domaine::all();
        $secteurs = Secteur::where('agence_id',auth()->user()->agence_id)->get();
        return view('Gestionnaire/Cooperatives/index')->with(compact('domaines','secteurs','unions'));
    }


    public function fetchAll(){
        $items = Tenant::where('user_id',auth()->user()->id)->get();
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
        $data['entrepot_id'] = $request->entrepot_id??0;
        $data['tenant_id'] = $request->cooperative_id;
        Caisse::create($data);
        Session::flash('success','Caisse créé avec succès!');
        return back();
    }

    public function addWallet(Request $request){
       // dd($request->all());
        $data['name'] = $request->name;
        $data['montant'] = $request->montant;
        $data['operateur_id'] = $request->operateur_id;
        $data['entrepot_id'] = $request->entrepot_id??0;
        $data['api_key'] = $request->api_key;
        $data['api_secret'] = $request->api_secret;
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        $data['tenant_id'] = $request->cooperative_id;
        Wallet::create($data);
        Session::flash('success','Wallet créé avec succès!');
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
        $ent['name'] = $request->name;
        $ent['token'] = sha1(time().rand(0,99));
        $ar = Arrondissement::find($request->arrondissement_id);
        $ent['departement_id'] = $ar->departement_id;
        $ent['region_id'] = $ar->departement->region_id;
        $ent['user_id'] = auth()->user()->id;
        $ent['gestionnaire_id'] = auth()->user()->id;
        $ent['taille'] = 'COOPERATIVE';
        $ent['agence_id'] = auth()->user()->agence_id;
        $ent['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::create($ent);

        $data_ = $request->only('name','phone','address','domaine_id','is_union');


        $data_['token'] = sha1(time().auth()->user()->id);
        $data_['is_union'] = is_null($request->is_union) ? false : true;
        $data_['union_id'] = $request->union_id??0;
        $data_['secteur_id'] = $request->secteur_id??0;
        $data_['region_id'] = $ar->region_id;
        $data_['departement_id'] = $ar->departement_id;
        $data_['arrondissement_id'] = $ar->id;
        $data_['entreprise_id'] = $entreprise->id;
        $data_['agence_id'] = auth()->user()->agence_id;
        $data_['user_id'] = auth()->user()->id;
        $data_['representation_id'] = auth()->user()->representation_id;
        $photo = request()->photo;
        if($photo){
            $data_['photo_uri'] = $this->entityImgCreate($photo,'cooperatives',$data_['token']);
        }
        $tenant = Tenant::create($data_);
        $tenantSlug = Str::slug($tenant->name, '-');
        $tenant->domains()->create(['domain'=>$tenantSlug.'.'.config('tenancy.central_domains')[0]]);

        if($request->username){
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
            tenancy()->initialize($tenant);
        }
        Session::flash('success','Organisation intérimaire créée avec succès!');
        return back();
    }

    public function exportPaiements(Request $request){

        $item = Tenant::where('token',$request->token)->first();
        $data = $item->run(function()use($request,$item){
        $from = $request->from;
        $to = $request->to;
        $mode = $request->mode_id;
        $caisse_id = $request->caisse_id;
        $wallet_id = $request->wallet_id;
        $paiements = Paiement::orderBy('created_at','DESC')
                        ->where('saison_id',$this->_saison->id)
                        ->whereBetween('created_at',[$from,$to])
                        ->get();

        if($caisse_id){
            $paiements = $paiements->where('caisse_id',$caisse_id);
        }
        if($wallet_id){
            $paiements = $paiements->where('wallet_id',$wallet_id);
        }
        $title = $item->name . "_historique_paiements_" .  " ". $from."_". $to;
            return [
                'paiements'=>$paiements,
                'title'=>$title,
            ];
        });
        tenancy()->initialize($item);
        return Excel::download(new PaiementExport($data['paiements'],$item,$request->from,$request->to),$data['title'].'.xlsx');
    }

    public function exportEntrees(Request $request){
        $item = Tenant::where('token',$request->token)->first();
        $data = $item->run(function()use($request,$item){
            $from = $request->from;
            $to = $request->to;
            $entrepot_id = $request->entrepot_id;
            //$cooperative = Cooperative::find($request->cooperative_id);
            $items = Entree::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->whereBetween('created_at',[$from,$to])->get();
            if($entrepot_id){
                $items = $items->where('entrepot_id',$entrepot_id);
            }
            $title = $item->name . "_historique_entrees_en_stock_" .  " ". $from."_". $to;
            return [
                'items'=>$items,
                'title'=>$title,
            ];
        });
        tenancy()->initialize($item);
        return Excel::download(new EntreeExport($data['items'],$item,$request->from,$request->to),$data['title'].'.xlsx');

    }

    public function getEntree($token){
        $item = Entree::where('token',$token)->first();
        return view('Gestionnaire.Cooperatives.entree',compact('item'));
    }

    public function getEntrepot($token){
        $item = Entrepot::where('token',$token)->first();
        $entrees = Entree::where('entrepot_id',$item->id)->get();
        return view('Gestionnaire.Cooperatives.entrepot',compact('item','entrees'));
    }

    public function addCompte(){
        dd(request()->all());
        $data['name'] = request()->name;
        $data['montant'] = request()->montant;
        $data['token'] = sha1(time());
        $data['banque_id'] = request()->banque_id;
        $data['tenant_id'] = request()->tenant_id;
        BanqueCooperative::create($data);
        return back();
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
        $transferts = Transfert::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->whereIn('source_id',$item->entrepots->pluck('id'))->orWhereIn('target_id',$item->entrepots->pluck('id'))->get();
        $domaines = Domaine::all();
        $secteurs = Secteur::where('agence_id',auth()->user()->agence_id)->get();
        $operateurs = Operateur::all();
        $banques = Banque::all();
        $comptes = BanqueCooperative::where('banque_id',auth()->user()->banque_id)->get();
       // dd($item->caisses);
       if($item->is_union){

        $data = $item->run(function(){
            $paiements = Paiement::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            $entrees = Entree::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            $agents = User::where('role_id',2)->get();
            $users = User::all();
            return [
                'agents'=>$agents,
                'users'=>$users,
                'paiements'=>$paiements,
                'entrees'=>$entrees,
            ];
        });
        return view('Gestionnaire/Cooperatives/union')->with(compact('item','domaines','secteurs','data','operateurs','banques','transferts'));
       }else{
       //$requests = StructurationRequest::orderBy('created_at','DESC')->where('tenant_id',$item->id)->get();
       //$membres = Membre::where('tenant_id',$item->id)->get();
       //$entrepots = Entrepot::where('tenant_id',$item->id)->get();
       //$caisses = Caisse::where('tenant_id',$item->id)->get();
       //$wallets = Wallet::where('tenant_id',$item->id)->get();

       $data = $item->run(function(){
            $paiements = Paiement::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            $entrees = Entree::orderBy('created_at','DESC')->where('saison_id',$this->_saison->id)->get();
            //$membres = Membre::all();
            //$entrepots = Entrepot::all();
            $agents = User::where('role_id',2)->get();
            //$caisses = Caisse::all();
            //$wallets = Wallet::all();
            //$requests = StructurationRequest::orderBy('created_at','DESC')->get();
            $users = User::all();
            return [
                'paiements'=>$paiements,
                'entrees'=>$entrees,
                'agents'=>$agents,
                'users'=>$users,
            ];
        });
        tenancy()->initialize($item);
        //$data['requests'] = $requests;
        //$data['membres'] = $membres;
        //$data['entrepots'] = $entrepots;
        //$data['caisses'] = $caisses;
        //$data['wallets'] = $wallets;
		return view('Gestionnaire/Cooperatives/show')->with(compact('item','operateurs','data','banques','comptes','transferts'));
       }
	}


}
