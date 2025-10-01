<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entrepot;
use App\Models\Structuration\Membre;
use App\Models\Structuration\User;
use App\Models\Tenant;
use App\Models\Niveau;
use App\Models\Structuration\Entree;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class TenantController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Tenant::where('union_id',tenant()->id)->get();
        return view('Tenant/Admin/Tenants/index')->with(compact('items'));
    }

    public function addEntrepot(Request $request)
    {
        $data = $request->all();
        $data['token'] = sha1(time());
        $data['tenant_id'] = $request->tenant_id;
        Entrepot::create($data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }

    public function addMembre(Request $request)
    {
        $data = $request->except('photo');
        //dd($data);
        $coop = Tenant::find($request->tenant_id);
       // dd($coop);
        $data['token'] = sha1(time());
        $data['tenant_id'] = $request->tenant_id;
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
        Session::flash('success','Producteur créé avec succès!');
        return back();
    }

    public function getMembres($id)
    {
        $membres = Membre::where('tenant_id',$id)->get();
        return response()->json($membres->map(function($item){
            return [
                'id'=>$item->id,
                'name'=>$item->name,
                'phone'=>$item->phone,
                'village'=>$item->village?->name,
            ];
        }));
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
        $membres = $item->membres;
        if($item->union_id != tenant()->id){
            Session::flash('error','Vous n\'avez pas les permissions pour accéder à cette coopérative');
            return back();
        }
        $item->entrees = $item->run(function(){
            return Entree::orderBy('created_at','DESC')->get();
        });
        $villages = Village::all(); //Village::where('arrondissement_id',$item->arrondissement_id)->get();
        $niveaux = Niveau::all();
        return view('Tenant/Admin/Tenants/show')->with(compact('item','membres','villages','niveaux'));
	}


}
