<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Models\Cooperative;
use App\Models\Exploitant;
use App\Models\Mouvement;
use App\Models\Niveau;
use App\Models\PaiementPart;
use App\Models\Part;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MouvementController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('/Cooperative/Mouvements/index');
    }

    public function fetchAll(){
        $items = Mouvement::where('cooperative_id',auth()->user()->cooperative_id)->get();
        //$items = EntrepriseListResource::collection($items);
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
        $data = $request->except('photo');
        $data['token'] = sha1(time().auth()->user()->id);
        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;
        $data['cooperative_id'] = $coop->id;
       
        Mouvement::create($data);
        Session::flash('success','Enregistrement créé avec succès!');
        return redirect(route('cooperative,mouvements.index'));
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
