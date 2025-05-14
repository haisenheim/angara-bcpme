<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Exports\Gestionnaire\Cooperatives\EntreeExport;
use App\Exports\Gestionnaire\Cooperatives\PaiementExport;
use App\Http\Controllers\ExtendedController;
use App\Http\Resources\CooperativeListResource;
use App\Models\Arrondissement;
use App\Models\Domaine;
use App\Models\Entreprise;
use App\Models\Region;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use App\Models\Structuration\Paiement;
use App\Models\Taille;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
        return view('/Gestionnaire/Cooperatives/index')->with(compact('cooperatives','domaines'));
    }


    public function fetchAll(){
        $items = Cooperative::where('user_id',auth()->user()->id)->get();
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
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        Caisse::create($data);
        return back();
    }

    public function addWallet(Request $request){
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        Wallet::create($data);
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
        //$data = $request->except('_token','appuisnf','appuisf','autres','type_personnel');
        //dd($request->all());
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

        $data = $request->all();
        $coop = new Cooperative();
        $coop->name = $data['name'];
        //$coop->email = $data['m-email'];
        $coop->phone = $data['phone'];
        $coop->token = sha1(time());
        $coop->address = $data['address'];
       // $coop->immatriculation = $data['immatriculation'];
        $coop->region_id = $ar->region_id;
        $coop->departement_id = $ar->departement_id;
        $coop->arrondissement_id = $ar->id;
        $coop->entreprise_id = $entreprise->id;
        $coop->domaine_id = $data['domaine_id'];
        $coop->agence_id = auth()->user()->agence_id;
        $coop->user_id = auth()->user()->id;
        $coop->representation_id = auth()->user()->representation_id;
        $photo = request()->photo;
        if($photo){
            $coop->photo_uri = $this->entityImgCreate($photo,'cooperatives',$coop->token);
        }
        $coop->save();
        $user = new User();
        $user->role_id = 21;
        $user->name = $data['username'];
        $user->email = $data['email'];
        $user->password = bcrypt($data['password']);
        $user->token = sha1(time());
        $user->cooperative_id = $coop->id;
        $user->save();
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
		$item = Cooperative::where('token',$token)->first();
       // dd($item->caisses);
       $paiements = Paiement::orderBy('created_at','DESC')->where('cooperative_id',$item->id)->where('saison_id',$this->_saison->id)->get();
       $entrees = Entree::orderBy('created_at','DESC')->where('cooperative_id',$item->id)->where('saison_id',$this->_saison->id)->get();
        $operateurs = Operateur::all();
		return view('Gestionnaire/Cooperatives/show')->with(compact('item','operateurs','paiements','entrees'));
	}


}
