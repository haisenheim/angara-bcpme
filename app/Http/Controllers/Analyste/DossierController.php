<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    //
    public function index()
    {
        //
        return view('/Analyste/Dossiers/index');
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->where('analyste_id',auth()->user()->id)->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        $resp = json_decode($resp->body(),true);
        $dossier = $resp['dossier'];
        //dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }
}
