<?php

namespace App\Http\Controllers\Structuration\Banquier;

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
        $requests = collect();
        $comptes = BanqueCooperative::where('banque_id', auth()->user()->banque_id)->get();
        foreach ($comptes as $compte) {
            $tenant = Tenant::where('id', $compte->tenant_id)->first();
            $tenant->run(function () use ($requests,$compte) {
                $items = StructurationRequest::where('source_id', $compte->id)
                    ->orderBy('created_at', 'DESC')
                    ->get();
                foreach ($items as $item) {
                    $requests->push($item);
                }
                   // $requests = $requests->merge($items);

            });
            app()->instance('tenant', $tenant);
        }
        return view('Structuration/Banquier/Requests/index')->with(compact('requests'));
    }

    public function valider(){
        $token = request()->token;
        //dd(request()->all());
        $compte = BanqueCooperative::where('token',request()->compte_id)->first();
        $tenant = $compte->tenant;
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

                    $item->treated_at = new \DateTime();
                    $item->treated_by = auth()->user()->id;
                    if($compte){
                        $item->source_id = $compte->id;
                        $item->banque_id = $compte->banque_id;
                        $compte->montant = $compte->montant - $item->montant;
                        $compte->save();
                        if($compte->montant >= $item->montant){
                            $item->save();
                            if($item->wallet_id){
                                $wallet = Wallet::find($item->wallet_id);
                                $wallet->montant = $wallet->montant + $item->montant;
                                $wallet->save();
                            }else{
                                if($item->caisse_id){
                                    $caisse = Caisse::find($item->caisse_id);
                                    $caisse->montant = $caisse->montant + $item->montant;
                                    $caisse->save();
                                }
                            }
                        }else{
                            Session::flash('error','Solde du compte insuffisant! Cette requete ne peut pas être traitée!');
                            return back();
                        }
                    }

                }
        });
        Session::flash('success','Requete traitée avec succès!');
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
