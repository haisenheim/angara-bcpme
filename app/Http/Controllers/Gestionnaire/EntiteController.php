<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Dossier;
use App\Models\ElementConstitutif;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseElementConstitutif;
use App\Models\EntrepriseProduit;
use App\Models\Forme;
use App\Models\Instruction\Critere as InstructionCritere;
use App\Models\Person;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Service;
use App\Models\Tier;
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
        return view('Gestionnaire/Entites/index');
    }

    public function fetchAll()
    {
        $items = Entreprise::orderBy('created_at', 'DESC')->where('prospect', 0)->where('individual', 1)->where('user_id', auth()->user()->id)->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('Gestionnaire/Entites/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->except('_token', 'appuisnf', 'appuisf', 'autres', 'type_personnel');
        $anfs = explode(',', $request->appuisnf);
        $afs = explode(',', $request->appuisf);
        $produits = explode(',', $request->autres);
        // $type_personnel = $request->type_personnel;
        $data['token'] = sha1(time().rand(0, 99));
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        // $data['personnel_'.$type_personnel] = 1;
        $data['individual'] = 1;
        $entreprise = Entreprise::create($data);
        foreach ($afs as $a) {
            EntrepriseAppui::create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($anfs as $a) {
            EntrepriseAppui::create([
                'entreprise_id' => $entreprise->id,
                'service_id' => $a,
            ]);
        }
        foreach ($produits as $a) {
            EntrepriseProduit::create([
                'entreprise_id' => $entreprise->id,
                'produit_id' => $a,
            ]);
        }

        // dd($data);
        return redirect(route('gestionnaire.entites.index'));
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
        return redirect(route('gestionnaire.entites.index'));
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
        $appuis = Service::all();
        $elements = ElementConstitutif::where('active', 1)->get();

        return view('/Gestionnaire/Entites/show', compact('item', 'mr', 'appuis', 'elements'));

    }

    public function saveProgramme(Request $request)
    {
        // $token = $request->token;
        // dd($request->all());
        $data = $request->all();
        $data['token'] = sha1(time().rand(1, 100));
        $data['gestionnaire_id'] = auth()->user()->id;
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

    public function saveAppui(Request $request)
    {

        EntrepriseAppui::create([
            'entreprise_id' => $request->entreprise_id,
            'service_id' => $request->appui_id,
        ]);

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    public function addElement(Request $request)
    {
        $token = sha1(time().auth()->user()->id);
        EntrepriseElementConstitutif::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'type_id' => $request->type_id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'type_id' => $request->type_id,
                'uri' => $this->entityDocumentCreate($request->fichier, 'elements_constitutifs', $token),
                'token' => $token,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    public function createTiersPhysique(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }

        return view('/Gestionnaire/Entites/tiers_physique', compact('item'));
    }

    public function createTiersMorale(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $formes = Forme::all();

        return view('/Gestionnaire/Entites/tiers_morale', compact('item', 'formes'));
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

        return redirect(route('gestionnaire.entites.show', $token));
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

        return redirect(route('gestionnaire.entites.show', $token));
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();

        return view('/Gestionnaire/Entites/questionnaire', compact('item', 'criteres'));
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

            return view('Gestionnaire.Entites.edit', compact('item', 'formes'));
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
