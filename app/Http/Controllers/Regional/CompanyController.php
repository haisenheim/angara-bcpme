<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Critere;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Person;
use App\Models\Programme;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Tier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Regional/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Regional/Companies/prospects');
    }

    public function fetchAll()
    {
        $items = Entreprise::where('prospect', 0)->where('representation_id', auth()->user()->representation_id)->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    public function fetchProspects()
    {
        $items = Entreprise::where('prospect', 1)->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $formes = Forme::all();

        return view('/Regional/Companies/create', compact('formes'));
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
        $item->load([
            'promuClientUser',
            'prospectRejectedUser',
            'dossierEntreeRelation.qualificationUser',
            'dossierEntreeRelation.programmesSubmittedBy',
            'dossierEntreeRelation.instructionValidatedBy',
            'dossierEntreeRelation.programmeSelections.programme',
            'dossierEntreeRelation.programmeSelections.instructionDossier',
        ]);
        // dd($item);
        $reponses = $item->reponses;
        $groups = $reponses->groupBy('critere_id');
        $groups = $groups->map(function ($v, $k) {
            $critere = Critere::find($k);

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
        $cas = User::where('role_id', (int) config('angara.role_analyste_financier', 17))->where('agence_id', auth()->user()->agence_id)->get();
        $programmes = Programme::all();

        return view('/Regional/Companies/show', compact('item', 'mr', 'programmes', 'cas'));

    }

    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu', $data['niu'])->where('phone', $data['phone'])->first();
        if (! $person) {
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function saveTiersMorale(Request $request)
    {
        // dd($request->all());
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();
        if (! $entreprise) {
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();

        return view('/Regional/Companies/questionnaire', compact('item', 'criteres'));
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
    public function edit(string $id)
    {
        //
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
