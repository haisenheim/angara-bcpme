<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntreeResource;
//use App\Models\Agent;
use App\Models\Structuration\Sortie;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Exploitant;
use App\Models\Structuration\Gamme;
use App\Models\Structuration\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SortieController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Tenant/Admin/Sorties/index');
    }

    public function fetchAll(){
        $items = Sortie::all();
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
        $agents = Agent::all();
        $entrepots = Entrepot::all();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        $exploitants = Membre::all();
        return view('Tenant.Admin.Sorties.create',compact('agents','entrepots','gammes','exploitants'));
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
        $data['cooperative_id'] = $coop->id;
        $data['saison_id'] = $this->_saison->id;
        $data['domaine_id'] = $coop->domaine_id;
        $data['cooperative_id'] = $coop->id;
        $data['user_id'] = auth()->user()->id;
        $data['name'] = str_pad($coop->id.date('ymdhi').$data['exploitant_id'],'0',STR_PAD_LEFT);
        $data['montant'] = $data['pu'] * $data['quantity'];
        Sortie::create($data);
        $stock = EntrepotGamme::where('entrepot_id',$data['entrepot_id'])->where('gamme_id',$data['gamme_id'])->first();
        if(!$stock){
            $stock = EntrepotGamme::create([
                'entrepot_id'=>$data['entrepot_id'],
                'gamme_id'=>$data['gamme_id'],
                'agence_id'=>$coop->agence_id,
                'representation_id'=>$coop->representation_id,
                'cooperative_id'=>$coop->id,
            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('admin.Sorties.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{

	}


}
