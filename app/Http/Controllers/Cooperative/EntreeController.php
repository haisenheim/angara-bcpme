<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntreeResource;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\EntrepotGamme;
use App\Models\Structuration\Exploitant;
use App\Models\Structuration\Gamme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EntreeController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('Cooperative/Entrees/index');
    }

    public function fetchAll(){
        $items = Entree::where('cooperative_id',auth()->user()->cooperative_id)->get();
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

        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $agents = Agent::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $entrepots = Entrepot::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $gammes = Gamme::where('domaine_id',$coop->domaine_id)->get();
        $exploitants = Exploitant::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('Cooperative.Entrees.create',compact('agents','entrepots','gammes','exploitants'));
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
        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;
        $data['cooperative_id'] = $coop->id;
        $data['saison_id'] = $this->_saison->id;
        $data['domaine_id'] = $coop->domaine_id;
        $data['cooperative_id'] = $coop->id;
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
                'cooperative_id'=>$coop->id,
            ]);
        }
        $stock->quantity = $stock->quantity + $data['quantity'];
        $stock->save();
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('cooperative.entrees.index'));
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
