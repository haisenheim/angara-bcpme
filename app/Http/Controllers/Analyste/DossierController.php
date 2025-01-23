<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

    public function loadDsf(Request $request){

        $item = Dossier::find($request->dossier_id);
        $filename = $request->file('upload')->getClientOriginalName();
        $getfilePath  = $request->file('upload')->getRealPath();
        $client = new Client();
        $resp = $client->request('POST','http://angara.pft-keka.com:8080/dossier', [
            'multipart' => [
                [
                    'name'     => 'upload',
                    'contents' => fopen($getfilePath, 'r')
                ],
                [
                    'name'     => 'dossier_id',
                    'contents' => $item->id,
                ],
                [
                    'name'     => 'annee',
                    'contents' => $request->annee,
                ],
            ],

        ]);

        Session::flash('success','Enregistrement effectué avec succès!');
        return back();

        //return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
        $resp = Http::get('http://angara.pft-keka.com:8080/entreprise/dossier?id='.$item->id);
        //dd($resp->body());
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
