<?php

namespace App\Http\Controllers\Tenant\Rstock;

use App\Http\Controllers\Controller;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entrepot;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EntrepotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tenant = tenant();
        if($tenant->is_union){
            $tenants = $tenant->children;
            $ids = $tenants->pluck('id');
            $items = Entrepot::whereIn('tenent_id',$ids)->get();
            return view('Tenant/Rstock/Entrepots/index')->with(compact('items'));
        }else{
            Session::flash('error','Vous n\'avez pas les permissions pour accéder à la liste des entrepots');
            return redirect()->back();

        }
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
		$item = Entrepot::where('token',$token)->first();
		return view('/Tenant/Rstock/Entrepots/show')->with(compact('item'));
	}


}
