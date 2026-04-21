<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Arrondissement;
use App\Models\Critere;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\EntrepriseAppui;
use App\Models\EntrepriseProduit;
use App\Models\Forme;
use App\Models\Person;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Tier;
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
        return view('/Admin/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Admin/Companies/prospects');
    }

    public function fetchAll()
    {
        $items = Entreprise::where('prospect', 0)->get();
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

        return view('/Admin/Companies/create', compact('formes'));
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
        $type_personnel = $request->type_personnel;
        $data['token'] = sha1(time().rand(0, 99));
        $ar = Arrondissement::find($data['arrondissement_id']);
        $data['departement_id'] = $ar->departement_id;
        $data['region_id'] = $ar->departement->region_id;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $data['personnel_'.$type_personnel] = 1;
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
        return redirect(route('admin.entreprises.index'));
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

        return view('/Admin/Companies/show', compact('item', 'mr'));
        /* $data=[];
        $cpt=0;
        foreach($groups as $k=>$v){
            $critere = Critere::find($k);
            $data[$k] = ['critere'=>$critere];
            foreach($v as $n=>$m){
                $sc = QuestionSousCritere::find($n);
                $data[$k]['items']= [
                    'sous_critere'=>$sc,'reponses'=>$m
                ];
            }
           $cpt++;
        }
        dd($data);
        $mr = $data;
        return view('/Admin/Companies/show',compact('item','mr')); */
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

    public function createTiersPhysique(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }

        return view('/Admin/Companies/tiers_physique', compact('item'));
    }

    public function createTiersMorale(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $formes = Forme::all();

        return view('/Admin/Companies/tiers_morale', compact('item', 'formes'));
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

        return redirect(route('admin.entreprises.show', $token));
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

        return redirect(route('admin.entreprises.show', $token));
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();
        $isOpen = false; // Pour contrôller l'ouverture de l'accordeon

        return view('/Admin/Companies/questionnaire', compact('item', 'criteres', 'isOpen'));
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
