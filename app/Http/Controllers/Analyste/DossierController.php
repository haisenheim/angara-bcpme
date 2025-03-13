<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Imports\DsfImport;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\IndicateurFinancier;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

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

    public function createIndicateur(Request $request){
        $upload = $request->upload;
        $dossier_id = $request->dossier_id;
        $annee = $request->annee;
        $import = new DsfImport();
        Excel::import($import,$upload);
        dd($import);


    }

    public function loadDsf(Request $request){

        $dossier_id = $request->dossier_id;
       // $filename = $request->file('upload')->getClientOriginalName();
        $getfilePath  = $request->file('upload')->getRealPath();
        $client = new Client();
        $resp = $client->request('POST','http://localhost:8080/dossier', [
            'multipart' => [
                [
                    'name'     => 'upload',
                    'contents' => fopen($getfilePath, 'r')
                ],
                [
                    'name'     => 'dossier_id',
                    'contents' => $dossier_id,
                ],
                [
                    'name'     => 'annee',
                    'contents' => $request->annee,
                ],
            ],

        ]);

        $data = $resp->getBody()->getContents();
        $inds = json_decode($data,true);
        //dd($inds);
        foreach($inds as $ind){
            IndicateurFinancier::updateOrCreate(
                ['dossier_id'=>$dossier_id,'annee'=>$ind['annee']],$ind
            );
        }

        Session::flash('success','Enregistrement effectué avec succès!');
        return back();

        //return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        //dd(json_decode($resp->body(),true));
        $resp = json_decode($resp->body(),true);
        $dossier = $resp['dossier'];
        //dd($dossier);
        //$entreprise = $resp['entreprise'];
       // $engagements = Engagement::all();
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = IndicateurFinancier::where('dossier_id',$item->id)->get();
        $banques = Banque::all();
        $sme = $resp['sme'];

       // dd($item['variations']);

        return view('Analyste/Dossiers/show',compact('item','engagements','indicateurs','criteres','sme','banques'));
    }

    public function show_($token){

        $item = Dossier::where('token',$token)->first();
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        dd(json_decode($resp->body(),true));
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
