<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Niveau;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Membre;
use App\Models\Structuration\MembrePlateforme;
use App\Models\Structuration\Paiement;
use App\Models\Structuration\Plateforme;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        
        $items = Membre::where('tenant_id',tenant()->id)->get();
        $coop = tenant();
        $villages = Village::where('arrondissement_id',$coop->arrondissement_id)->get();
        $niveaux = Niveau::all();
        return view('Tenant/Admin/Members/index')->with(compact('villages','items','niveaux'));
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
        $data = $request->except('photo');
        $data['token'] = sha1(time().auth()->user()->id);
        $coop = tenant();
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;

        $data['arrondissement_id'] = $coop->arrondissement_id;
        $data['departement_id'] = $coop->departement_id;
        $data['region_id'] = $coop->region_id;
        $data['tenant_id'] = $coop->id;
        if($request->photo){
            $data['photo_uri'] = $this->entityImgCreate($request->photo,'members',$data['token']);
        }
        Membre::create($data);
        Session::flash('success','Nouvel adherent créé avec succès!');
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



		$item = Membre::where('token',$token)->first();
        $parts = Entree::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        $paiements = Paiement::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        $villages = Village::where('arrondissement_id',tenant()->arrondissement_id)->get();
        //$pps = PaiementPart::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        $plateformes = Plateforme::all();
		return view('Tenant/Admin/Members/show')->with(compact('item','parts','paiements','plateformes','villages'));
	}

    public function addKey(Request $request){
        $data = $request->all();
        MembrePlateforme::updateOrCreate([
            'plateforme_id'=>$data['plateforme_id'],
            'exploitant_id'=>$data['exploitant_id']
        ],$data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }


}
