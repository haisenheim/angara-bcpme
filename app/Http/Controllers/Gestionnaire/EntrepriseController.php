<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resp = Http::get('http://localhost:8080/entreprises');
        $items = json_decode($resp->body(),true);
        //dd($items);
        return view('/Gestionnaire/Entreprises/index')->with(compact('items'));
    }

    public function getDossier($id){
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$id);
        $resp = json_decode($resp->body(),true);
        $dossier = $resp['dossier'];
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        //$note3 = $dossier['exercices'][0]['notation'];
        //$criteres = $dossier['criteres'];
        //$notes = $dossier['notes'];
       // $nf = $dossier['note'];
        $sme = $resp['sme'];
        return view('/Gestionnaire/Entreprises/dossier',compact('id','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }

    public function _getDossier($id){
        $dossier = Http::get('http://localhost:8080/dossier?id='.$id);
        $dossier = json_decode($dossier->body(),true);
        $note3 = $dossier['exercices'][0]['notation'];
        $criteres = $dossier['criteres'];
        $notes = $dossier['notes'];
        $nf = $dossier['note'];
        $sme = $dossier['sme'];

        return view('/Gestionnaire/Instruction/dossier',compact('id','note3','criteres','notes','nf','sme'));
    }

    public function getCreateInstruction($id){
        return view('/Gestionnaire/Instruction/create',compact('id'));
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
		$resp = Http::get('http://localhost:8080/entreprise?id='.$id);
        $resp = json_decode($resp->body(),true);
        $item = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $banques = $resp['banques'];
        return view('/Gestionnaire/Entreprises/show')->with(compact('item','engagements','banques'));
	}

    public function setEngagement()
    {
        abort(403, 'Seuls l’analyste financier et l’analyste crédit peuvent modifier l’état des engagements.');
    }

    public function setAnalyse(){
        $data = request()->except('_token');
        //dd($data);
        $resp = Http::post('http://localhost:8080/entreprise/dossier/analyse',$data);
        return back();
    }

    public function  enable($id){

        return back();
    }

    public function  disable($id){

        return back();
    }




}
