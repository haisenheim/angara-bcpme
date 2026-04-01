<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Models\Banque;
use App\Models\Structuration\BanqueCooperative;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Request as StructurationRequest;
use App\Models\Tenant;
use Exception;
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
        $items = StructurationRequest::orderBy('created_at','DESC')->where('agence_id',auth()->user()->agence_id)->get();
        return view('Gestionnaire/Requests/index')->with(compact('items'));
    }

    public function valider(){
        $token = request()->token;
        //dd(request()->all());
        $compte = BanqueCooperative::find(request()->compte_id);
        $tenant = Tenant::where('token',request()->tenant_id)->first();
        $data = $tenant->run(function()use($token,$compte){
                $item = StructurationRequest::where('token',$token)->first();
                //dd(request()->all());
                if($item){
                    try{
                        //Appel a l'api de l'operateur
                    }catch(Exception $e){
                        //Session::flash('error','Une erreur est survenue lors de l\'echange avec l\'operateur de paiement!');
                        //return back();
                    }

                    $item->validated_at = new \DateTime();
                    $item->validated_by = auth()->user()->id;
                    if($compte){
                        $item->source_id = $compte->id;
                        $item->banque_id = $compte->banque_id;
                    }
                    $item->save();
                    if($item->wallet_id){
                        //$wallet = Wallet::find($item->wallet_id);
                        //$wallet->montant = $wallet->montant + $item->montant;
                        //$wallet->save();
                    }else{
                        if($item->caisse_id){
                            //$caisse = Caisse::find($item->caisse_id);
                            //$caisse->montant = $caisse->montant + $item->montant;
                            //$caisse->save();
                        }
                    }
                }
        });
        Session::flash('success','Requete approuvée avec succès!');
        app()->instance('tenant', $tenant);
       // Session::flash('error','Echec lors de l\'approbation de la requete!');
        return back();
    }

    public function cancel(){
        $token = request()->token;
        $tenant = Tenant::where('token',request()->tenant_id)->first();
        $data = $tenant->run(function()use($token){
            $item = StructurationRequest::where('token',$token)->first();
            if($item){
                $item->cancelled_at = new \DateTime();
                $item->cancelled_by = auth()->user()->id;

                $item->save();
            }
        });
        app()->instance('tenant', $tenant);
        Session::flash('info','Requete rejetée!');
        return back();
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        //$items = Wallet::where('cooperative_id',auth()->user()->cooperative_id)->get();
        //return view('Gestionnaire/Requests/create')->with(compact('items'));
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

	}


}
