<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Concerns\RedirectsBackToGrilleSousCritere;
use App\Http\Controllers\Controller;
use App\Models\Compte;
use App\Models\Dossier;
use App\Models\Instruction\Choice;
use App\Models\Instruction\CritereProgrammePonderation;
use App\Models\Instruction\Reponse;
use App\Models\Instruction\SousCritere;
use App\Models\Libelle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InstructionController extends Controller
{
    use RedirectsBackToGrilleSousCritere;

    public function getChoices(){
        //$dossier_id = request('dossier_id');

        $id = request('id');
        $choices = Choice::where('critere_id',$id)->get();
        return response()->json($choices);
    }

    public function saveCritereReponse(){
        //dd(request()->all());
        $choice_id = request('choice_id');
        $dossier_id = request('dossier_id');
        $critere_id = request('critere_id');
        $critere = SousCritere::find($critere_id);
        //$dossier = Dossier::find(request('dossier_id'));
        $choice = Choice::find(request('choice_id'));
        $cpp = CritereProgrammePonderation::where('programme_id',request('programme_id'))->where('critere_id',$critere_id)->first();
        //dd(request()->all());
        $pond = $critere?->default;
        if($cpp){
            $pond = $cpp->ponderation;
        }
        $val = $choice->note * $pond/100;
        $data = [
            'choice_id'=>$choice_id,
            'critere_id'=>$critere_id,
            'dossier_id'=>$dossier_id,
            'note'=>$choice->note,
            'ponderation'=>$pond,
            'value'=>$val,
        ];
        Reponse::updateOrCreate([
            'critere_id'=>$critere_id,
            'dossier_id'=>$dossier_id
        ],$data);

        return $this->redirectBackToGrilleSousCritere($critere_id)
            ->with('success', 'Choix enregistré.');
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
        $data = $request->all();
       // Libelle::create($data);
        return back();
    }

}
