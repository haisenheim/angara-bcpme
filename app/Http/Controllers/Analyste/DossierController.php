<?php

namespace App\Http\Controllers\Analyste;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Http\Resources\EngagementEntrepriseResource;
use App\Http\Resources\EngagementResource;
use App\Imports\DsfImport;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
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

    public function setAnalyse(){
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $data = [];
        if($sequence==1)
            $data = ['donnees_generales'=>$content];
        if($sequence==2)
            $data = ['analyse_ensemble'=>$content];
        if($sequence==3)
            $data = ['analyse_financiere'=>$content];
        if($sequence==4)
            $data = ['appuis'=>$content];
        if($sequence==5)
            $data = ['analyse_risque'=>$content];
        if($sequence==6)
            $data = ['analyse_rentabilite'=>$content];
        if($sequence==7)
            $data = ['conclusions_analyste'=>$content];

        Dossier::where('id',$dossier_id)->update($data);
        return redirect()->back();

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

    private function parse($eng,$id){
        $data = [
            'id'=>$eng->id,
            'name'=>$eng->name,
            'montant'=>$eng->montant??0,
            'encours_montant'=>$eng->encours_montant??0,
            'encours_impaye'=>$eng->encours_impaye??0,
            'sollicite_montant'=>$eng->sollicite_montant??0,
            'parent_id'=>$eng->parent_id,
            'is_title'=>$eng->is_title,
            'is_leaf'=>$eng->is_leaf,
            'niveau'=>$eng->niveau,
        ];
        if($data['is_leaf']){
            $elts = EngagementEntreprise::where('engagement_id',$eng->id)->where('entreprise_id',$id)->get();
            $data['encours_montant'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_montant;
            },0);
            $data['sollicite_montant']= $elts->reduce(function($carry,$item){
                return $carry + $item->sollicite_montant;
            },0);
            $data['encours_impaye'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_impaye;
            },0);
            $data['elts'] = $elts;

        }else{
            $data['children'] = $eng->children->map(function($child)use($id){
                return $this->parse($child,$id);
            });
            foreach($data['children'] as $child){
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
            }
        }
        return $data;
    }

    private function parseCriteres(Critere $critere){
        $dsc = [];
        $note = 0;
        foreach($critere->souscriteres as $sc){
            $r = $sc->reponse;
            $ch = $r?->choice;
            if($r){
                $note += $r->value;
            }
            $dsc[] = [
                'id'=>$sc->id,
                'name'=>$sc->name,
                'critereId'=>$sc->critere_id,
                'sequence'=>$sc->sequence,
                'default'=>$sc->default,
                'note'=>$r?$r->note:0,
                'reponse'=>$r?[
                        'id'=>$r->id,
                        'dossierId'=>$r->dossier_id,
                        'critereId'=>$r->critere_id,
                        'choiceId'=>$r->choice_id,
                        'note'=>$r->note,
                        'choice'=>[
                            'id'=>$ch->id,
                            'valeur'=>$ch->valeur,
                            'note'=>$ch->note,
                            'critereId'=>$sc->critere_id,
                        ]

                ]:[],
            ];
        }

        return [
            'id'=>$critere->id,
            'name'=>$critere->name,
            'note'=>$note,
            'souscriteres'=>$dsc,
        ];
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
       // $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        //dd(json_decode($resp->body(),true));
        //$resp = json_decode($resp->body(),true);
        $engagements = Engagement::where('parent_id',0)->get();
       // $engagements = EngagementResource::collection($engagements);
        $data = [];
        foreach($engagements as $eng){
            $data[] = $this->parse($eng,1);
        }
        //dd($data);

        $engagements = $data;

        $criteres = Critere::all();
        $id = $item->id;
        $criteres = $criteres->map(function($critere)use($id){
            //$critere->indicateurs = IndicateurFinancier::where('dossier_id',$critere->dossier_id)->where('critere_id',$critere->id)->get();
            $critere->souscriteres = $critere->sousCriteres->map(function($souscritere)use($id){
                $souscritere->reponse = $souscritere->reponses->where('dossier_id',$id)->first();
                //$souscritere->reponse->choice = $souscritere->reponse->choice;
                return $souscritere;
            });
            return $critere;
        });

        $criteres = $criteres->map(function($ct){
            return $this->parseCriteres($ct);
        });

        //dd($criteres);

        //$criteres = $resp['criteres'];

        //dd($criteres);
        $indicateurs = IndicateurFinancier::where('dossier_id',$item->id)->get();
        $banques = Banque::all();
        //dd($item->note);
        $sme = DossierHelper::getSme($item->note); //$resp['sme'];

       // dd($item['variations']);

        return view('Analyste/Dossiers/show',compact('item','indicateurs','criteres','sme','banques','engagements'));
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
