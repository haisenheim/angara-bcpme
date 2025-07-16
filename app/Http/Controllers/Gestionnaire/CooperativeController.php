<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Exports\Gestionnaire\Cooperatives\EntreeExport;
use App\Exports\Gestionnaire\Cooperatives\PaiementExport;
use App\Http\Controllers\ExtendedController;
use App\Http\Resources\CooperativeListResource;
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
        $cooperatives = Cooperative::where('gestionnaire_id',auth()->user()->id);
        $domaines = Domaine::all();
        $secteurs = Secteur::where('agence_id',auth()->user()->agence_id)->get();
        return view('Gestionnaire/Cooperatives/index')->with(compact('cooperatives','domaines','secteurs'));
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
        $data['entrepot_id'] = $request->entrepot_id;
        $tenant = Tenant::find($request->cooperative_id);
        $tenant->run(function()use($data){
            Caisse::create($data);
        });
        tenancy()->initialize($tenant);
        return back();
    }

    public function addWallet(Request $request){
        $data['name'] = $request->name;
        $data['montant'] = $request->montant;
        $data['operateur_id'] = $request->operateur_id;
        $data['entrepot_id'] = $request->entrepot_id;
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        $tenant = Tenant::find($request->cooperative_id);
        $tenant->run(function()use($data){
            Wallet::create($data);
        });
        tenancy()->initialize($tenant);
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
        $ent['taille'] = 'COOPERATIVE';
        $ent['agence_id'] = auth()->user()->agence_id;
        $ent['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::create($ent);

        $data_ = $request->only('name','phone','address','domaine_id');


        $data_['token'] = sha1(time().auth()->user()->id);
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
        tenancy()->initialize($item);
        $operateurs = Operateur::all();
		return view('Gestionnaire/Cooperatives/show')->with(compact('item','operateurs','data'));
	}


}
