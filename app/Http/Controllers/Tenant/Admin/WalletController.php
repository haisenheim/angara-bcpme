<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\AgentOperateur;
use App\Models\Structuration\Caisse;
use App\Models\Structuration\Wallet;
use App\Models\Structuration\Operateur;
use App\Models\Structuration\RechargeAgent;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class WalletController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

    }

    public function getCaisses(){
        $items = Caisse::where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Wallets/caisses')->with(compact('items'));
    }

    public function getMyWallets(){
        $items = Wallet::where('tenant_id',tenant()->id)->get();
        return view('Tenant/Admin/Wallets/mine')->with(compact('items'));
    }


    public function getRecharges()
    {
        //
        $items = RechargeAgent::orderBy('created_at','DESC')->get();
        return view('Tenant/Admin/Wallets/recharges')->with(compact('items'));
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
        $data['user_id'] = auth()->user()->id;
        $data['token'] = sha1(time());
        $data['cooperative_id'] = auth()->user()->cooperative_id;
        AgentOperateur::create($data);
        return back();
    }


    public function recharger(Request $request)
    {
        //$data = $request->all();
        $wallet = AgentOperateur::where('token',$request->token)->first();
        if($wallet){
            $pf = Wallet::where('operateur_id',$wallet->operateur_id)->where('montant','>=',$request->montant)->first();
            if($pf){
                DB::beginTransaction();
                try{
                    //Appel a l'api de l'operation de paiement
                    $pf->montant = $pf->montant - $request->montant;
                    $pf->save();
                    $wallet->montant = $wallet->montant + $request->montant;
                    $wallet->save();
                    $data['montant'] = $request->montant;
                    $data['agent_id'] = $wallet->agent_id;
                    $data['operateur_id'] = $wallet->operateur_id;
                    $data['wallet_id'] = $wallet->id;
                    $data['user_id'] = auth()->user()->id;
                    $data['token'] = sha1(time());
                    $cooperative = tenant();
                    $data['cooperative_id'] = $cooperative->id;
                    $data['agence_id'] = $cooperative->agence_id;
                    $data['representation_id'] = $cooperative->representation_id;
                    $data['saison_id'] = $this->_saison->id;
                    RechargeAgent::create($data);
                    DB::commit();
                    Session::flash('success','Recharge effectuée avec succès!');

                }catch(Exception $e){
                    DB::rollBack();
                    Session::flash('error','Les fonds sont insuffisants pour effectuer cette operation!');
                    back();
                }
            }else{

            }



        }

        return back();
    }

    public function disable($token){
        $item = AgentOperateur::where('token',$token)->first();
        if($item){
            $item->active = 0;
            $item->save();
        }
        return back();
    }

    public function enable($token){
        $item = AgentOperateur::where('token',$token)->first();
        if($item){
            $item->active = 1;
            $item->save();
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
