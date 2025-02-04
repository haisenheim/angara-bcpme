<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\CooperativeOperateur;
use App\Models\Structuration\Operateur;
use App\Models\Structuration\Request as StructurationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RequestController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = StructurationRequest::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('Cooperative/Requests/index')->with(compact('items'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $items = CooperativeOperateur::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('Cooperative/Requests/create')->with(compact('items'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->except('token');
        $wallet = CooperativeOperateur::where('token',$request->token)->first();
        if($wallet){
            if($wallet->cooperative_id == auth()->user()->cooperative_id){
                $data['cooperative_id'] = $wallet->cooperative_id;
                $data['token'] = sha1(time().$wallet->id);
                $data['user_id'] = auth()->user()->id;
                $data['operateur_id'] = $wallet->operateur_id;
                $data['saison_id'] = $this->_saison->id;
                $data['wallet_id'] = $wallet->id;
                $data['agence_id'] = $wallet->cooperative->agence_id;
                $data['representation_id'] = $wallet->cooperative->representation_id;
                StructurationRequest::create($data);
                Session::flash('success','Requete envoyée avec succès!');
                return redirect(route('cooperative.requests.index'));
            }
        }

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

	}


}
