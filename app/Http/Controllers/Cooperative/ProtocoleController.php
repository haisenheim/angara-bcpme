<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\Arrondissement;
use App\Models\Calendrier;
use App\Models\CalendrierItem;
use App\Models\Protocole;
use App\Models\Contrat;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ProtocoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $protocoles = Protocole::orderBy('created_at','DESC')->where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('/Cooperative/Protocoles/index')->with(compact('protocoles'));
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

    public function generateCalendar($token){
        $protocole = Protocole::where('token',$token)->first();
        if($protocole){
            Calendrier::create([
                'protocole_id'=>$protocole->id,
                'saison_id'=>$protocole->saison_id,
                'cooperative_id'=>$protocole->cooperative_id,
                'token'=>sha1(time() . rand(0,9999)),
            ]);
        }
        Session::flash('success','Opération effectuée avec succès!');
        return back();
    }

    public function setCalendarItem(){
        $data = request()->all();
        $cal = Calendrier::find($data['calendrier_id']);
        $item = new CalendrierItem();
        $item->token = sha1(time() . rand(0,9999));
        $item->quantity = $data['quantity'];
        $item->day = $data['day'];
        $item->region_id = $data['region_id'];
        $item->departement_id = $data['departement_id'];
        $item->arrondissement_id = $data['arrondissement_id'];
        $item->village_id = $data['village_id'];
        $item->calendrier_id = $data['calendrier_id'];
        $item->cooperative_id = $cal->cooperative_id;
        $item->saison_id = $cal->saison_id;
        $item->save();
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }

 

    public function showCalendarItem($token){
        $item = CalendrierItem::where('token',$token)->first();
        $contrats = Contrat::where('saison_id',$item->saison_id)->get();
        $arrondissements = Arrondissement::where('departement_id',$item->departement_id)->get();
        return view('/Cooperative/Protocoles/calendar_item')->with(compact('item','arrondissements','contrats'));

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$protocole = Protocole::where('token',$token)->first();
        $regions = Region::all();
		return view('/Cooperative/Protocoles/show')->with(compact('protocole','regions'));
	}


}
