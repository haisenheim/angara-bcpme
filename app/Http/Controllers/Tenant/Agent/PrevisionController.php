<?php

namespace App\Http\Controllers\Tenant\Agent;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntreeResource;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Gamme;
use App\Models\Structuration\Membre;
use App\Models\Structuration\Prevision;
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
        return view('Tenant/Agent/Previsions/index');
    }

    public function fetchAll(){
       //$id = auth()->user()->id;
        $items = Prevision::orderBy('created_at','DESC')->where('agent_id',auth()->user()->id)->get();
        //dd($items);
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

        $coop = tenant();
       // $agents = User::where('role_id',2)->get();
        $entrepots = Entrepot::all();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        $exploitants = Membre::all();
        return view('Tenant/Agent.Previsions.create',compact('entrepots','gammes','exploitants'));
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
        $data['agent_id'] = auth()->user()->id;
        $data['saison_id'] = $this->_saison->id;
       // $data['domaine_id'] = $coop->domaine_id;
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_pad($coop->id.date('ymdhi').$data['membre_id'],'0',STR_PAD_LEFT);
        $data['montant'] = $data['pu'] * $data['quantity'];
        Prevision::create($data);

        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('agent.previsions.index'));
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
        return view('Tenant.Agent.Previsions.show',compact('item'));
	}



}
