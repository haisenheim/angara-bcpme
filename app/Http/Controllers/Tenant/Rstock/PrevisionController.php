<?php

namespace App\Http\Controllers\Tenant\Rstock;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntreeResource;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Gamme;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Paiement;
use App\Models\Structuration\Prevision;
use App\Models\Structuration\Wallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrevisionController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Tenant/Rstock/Previsions/index');
    }

    public function fetchAll(){
        $items = Prevision::orderBy('created_at','DESC')->get();
        $items = EntreeResource::collection($items);
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['token'] = sha1(time().auth()->user()->id);
        $coop = tenant();
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;

        $data['saison_id'] = $this->_saison->id;
        $data['domaine_id'] = $coop->domaine_id;
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_pad($coop->id.date('ymdhi').$data['exploitant_id'],'0',STR_PAD_LEFT);
        $data['montant'] = $data['pu'] * $data['quantity'];
        Entree::create($data);
        $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->first();
        if(!$stock){
            $stock = EntrepotGamme::create([
                'entrepot_id'=>$data['entrepot_id'],
                'gamme_id'=>$data['gamme_id'],
                'agence_id'=>$coop->agence_id,
                'representation_id'=>$coop->representation_id,

            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('rstock.entrees.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
        $item = Prevision::where('token',$token)->first();
        //dd($wallets);
        $coop = tenant();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        return view('Tenant/Rstock.Previsions.show',compact('item','gammes'));
	}

    public function getPaiements(){
        $items = Paiement::orderBy('created_at','DESC')->get();
        return view('Tenant/Rstock.Entrees.paiements',compact('items'));
    }

    public function addEntree(Request $request){

        $data = $request->all();
        $item = Prevision::find($data['prevision_id']);
        $data['token'] = sha1(time().auth()->user()->id);
        $coop = tenant();
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;
        $data['entrepot_id'] = auth()->user()->entrepot_id;
        $data['saison_id'] = $this->_saison->id;
        $data['domaine_id'] = $coop->domaine_id;
        $data['user_id'] = auth()->user()->id;
        $data['agent_id'] = $item->agent_id;
        $data['exploitant_id'] = $item->membre_id;
        $data['name'] = str_pad($coop->id.date('ymdhi').$data['exploitant_id'],'0',STR_PAD_LEFT);
        $data['montant'] = $data['pu'] * $data['quantity'];
        Entree::create($data);
        $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->first();
        if(!$stock){
            $stock = EntrepotGamme::create([
                'entrepot_id'=>$data['entrepot_id'],
                'gamme_id'=>$data['gamme_id'],
                'agence_id'=>$coop->agence_id,
                'representation_id'=>$coop->representation_id,

            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        Session::flash('success','Enregistrement créé avec succès!');
        return back();
    }


}
