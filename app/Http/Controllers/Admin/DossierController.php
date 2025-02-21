<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    //
    public function index()
    {
        //
        return view('/Admin/Dossiers/index');
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
       // dd($item);
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        $resp = json_decode($resp->body(),true);
        //dd($resp);
        $dossier = $resp['dossier'];
        //dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        return view('/Admin/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }
}
