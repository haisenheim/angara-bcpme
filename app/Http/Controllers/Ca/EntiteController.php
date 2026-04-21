<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EntiteController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('Ca/Entites/index');
    }

    public function fetchAll()
    {
        $items = Entreprise::orderBy('created_at', 'DESC')->where('prospect', 0)->where('individual', 1)->where('agence_id', auth()->user()->agence_id)->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    public function save(Request $request)
    {
        //
        $data = $request->except('_token', 'type_personnel');
        $type_personnel = $request->type_personnel;
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['personnel_'.$type_personnel] = 1;
        $entreprise = Entreprise::updateOrcreate(['token' => $data['token']], $data);

        // dd($data);
        Session::flash('success', 'Enregistrement effectué avec succès!');

        // return back();
        return redirect(route('ca.entites.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        // dd($item);
        $reponses = $item->reponses;
        $groups = $reponses->groupBy('critere_id');
        $groups = $groups->map(function ($v, $k) {
            $critere = InstructionCritere::find($k);

            return ['critere' => $critere,
                'items' => $v->groupBy('sous_critere_id')
                    ->map(function ($m, $n) {
                        $sc = QuestionSousCritere::find($n);

                        return [
                            'sous_critere' => $sc,
                            'items' => $m,
                        ];
                    }),
            ];
        });
        // dd($groups);
        $mr = $groups;

        return view('/Ca/Entites/show', compact('item', 'mr'));

    }

    public function saveProgramme(Request $request)
    {

        $data = $request->all();
        $data['token'] = sha1(time().rand(1, 100));
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;

        Dossier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'programme_id' => $request->programme_id,
            ],
            $data
        );
        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();

        return view('/Ca/Entites/questionnaire', compact('item', 'criteres'));
    }

    public function saveQuestionnaire(Request $request)
    {
        // dd($request->choices[1]);
        $choices = $request->choices;
        foreach ($choices as $choice) {
            QuestionAnswer::updateOrCreate([
                'entreprise_id' => $choice['entreprise_id'],
                'choice_id' => $choice['choice_id'],
            ], $choice);
        }

        // Session::flash('success','Enregistrement effectué avec succès!');
        return response()->json('ok');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if ($item) {
            $formes = Forme::all();

            return view('Ca.Entites.edit', compact('item', 'formes'));
        }

        return back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
