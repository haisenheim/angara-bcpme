<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Agent;
use App\Models\Structuration\Cooperative;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AgentController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Agent::where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('Cooperative/Agents/index')->with(compact('items'));
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
        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $data['cooperative_id'] = $coop->id;
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;
        $data['arrondissement_id'] = $coop->arrondissement_id;
        $data['departement_id'] = $coop->departement_id;
        $data['region_id'] = $coop->region_id;
        $data['password'] = bcrypt($data['password']);
        $data['token'] = sha1(time().$coop->id);
        if($request->photo){
            $data['photo_uri'] = $this->entityImgCreate($request->photo,'agents',$data['token']);
        }
        Agent::create($data);
        Session::flash('success','Nouvel agent de terrain créé avec succès!');
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
		$item = Agent::where('token',$token)->first();
       // dd($item);
		return view('Cooperative/Agents/show')->with(compact('item'));
	}


}
