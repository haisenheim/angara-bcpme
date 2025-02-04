<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Models\Exploitant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Paiement;
use App\Models\PaiementPart;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OrderController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = OrderItem::orderBy('created_at','DESC')->where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('/Cooperative/Orders/index')->with(compact('items'));
    }


    public function cancel($token){
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

    public function addPart(){
        $data = request()->all();
        $item = OrderItem::find($data['item_id']);
        $data['order_id'] = $item->order_id;
        $data['saison_id'] = $item->saison_id;
        $data['token'] = sha1(time().$item->id);
        $data['grd1_qty'] = $data['grd1_qty']/1000;
        $data['grd2_qty'] = $data['grd2_qty']/1000;
        $data['hs_qty'] = $data['hs_qty']/1000;
        $data['cooperative_id'] = auth()->user()->cooperative_id;
        Part::create($data);
        Session::flash('success','Part créée avec succès!');
        return back();
    }

    public function addPaiementPart(){
        $data = request()->all();
        $paiement = Paiement::find($data['paiement_id']);
        $part = Part::find($data['part_id']);
        $data['user_id'] = auth()->user()->id;
        $data['exploitant_id'] = $part->exploitant_id;
        $data['item_id'] = $part->item_id;
        $data['order_id'] = $part->order_id;
        $data['cooperative_id'] = auth()->user()->cooperative_id;
        $data['contrat_id'] = $paiement->contrat_id;
        $data['saison_id'] = $part->saison_id;
        $data['client_id'] = $paiement->client_id;
        $data['banque_id'] = $paiement->banque_id;
        $data['paiement_id'] = $paiement->id;
        $data['semaine'] = date('W');
        $data['moi_id'] = date('m');
        $data['token'] = sha1(time().$paiement->id);
        PaiementPart::create($data);
        Session::flash('success','Paiement enrégistré avec succès!');
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
		$item = OrderItem::where('token',$token)->first();
        $members = Exploitant::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('/Cooperative/Orders/show')->with(compact('item','members'));


	}


}
