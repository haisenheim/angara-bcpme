<?php

namespace App\Http\Controllers\Cooperative;

use App\Http\Controllers\Controller;
use App\Models\Saison;
use App\Models\Livraison;
use App\Models\Client;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $livraisons = Livraison::orderBy('created_at','DESC')->where('cooperative_id',auth()->user()->cooperative_id)->get();
        return view('/Cooperative/Livraisons/index')->with(compact('livraisons'));
    }

    public function accept($token)
	{
		$livraison = Livraison::where('token',$token)->first();
        $livraison->accepted_at = new \DateTime();
        $livraison->save();
        Session::flash('success','Commande validée avec succès!');
		return back();
	}

    public function valider()
	{
        $token = request()->token;
		$livraison = Livraison::where('token',$token)->first();
        $livraison->accepted_at = new \DateTime();
        $livraison->save();
        Session::flash('success','Commande validée avec succès!');
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($id)
	{
		$livraison = Livraison::find($id);
		return view('/Cooperative/Livraisons/show')->with(compact('livraison'));
	}


}
