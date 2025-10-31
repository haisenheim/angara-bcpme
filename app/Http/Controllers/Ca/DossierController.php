<?php

namespace App\Http\Controllers\Ca;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    //
    public function index()
    {
        //
        return view('/Ca/Dossiers/index');
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->where('agence_id',auth()->user()->agence_id)->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
        $criteres = Critere::all();
        $id = $item->id;
        $criteres = $criteres->map(function($critere)use($id){
            $critere->souscriteres = $critere->sousCriteres->map(function($souscritere)use($id){
                $souscritere->reponse = $souscritere->reponses->where('dossier_id',$id)->first();
                return $souscritere;
            });
            return $critere;
        });

        $criteres = $criteres->map(function($ct){
            return $this->parseCriteres($ct);
        });

        $indicateurs = IndicateurFinancier::where('dossier_id',$item->id)->get();
        $banques = Banque::all();
        $sme = DossierHelper::getSme($item->note);

        return view('Ca/Dossiers/show',compact('item','indicateurs','criteres','sme','banques'));
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
}
