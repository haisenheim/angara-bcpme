<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ExtendedController;
use App\Models\Structuration\Operateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class OperateurController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Operateur::where('active',1)->get();
        return view('Admin/Operateurs/index')->with(compact('items'));
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
        if($request->photo){
            $data['photo_uri'] = $this->entityImgCreate($request->photo,'operateurs_mobiles',time());
        }
        Operateur::create($data);
        Session::flash('success','Nouvel operateur créé avec succès!');
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
		$item = Operateur::where('token',$token)->first();
       // dd($item);
		return view('Admin/Operateurs/show')->with(compact('item'));
	}


}
