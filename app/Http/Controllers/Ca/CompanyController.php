<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
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
        return view('/Ca/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Ca/Companies/prospects');
    }


    public function fetchAll(){
        $items = Entreprise::where('prospect',0)->where('agence_id',auth()->user()->agence_id)->get();
        $items = EntrepriseListResource::collection($items);
        return response()->json($items);
    }

    public function fetchProspects(){
        $items = Entreprise::where('prospect',1)->get();
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
        return view('/Ca/Companies/create',compact('formes'));
    }

    private function parse($eng,$id){

        $data = [
            'id'=>$eng->id,
            'name'=>$eng->name,
            'montant'=>$eng->montant??0,
            'encours_montant'=>$eng->encours_montant??0,
            'encours_impaye'=>$eng->encours_impaye??0,
            'sollicite_montant'=>$eng->sollicite_montant??0,
            'variation'=>$eng->variation,
            'parent_id'=>$eng->parent_id,
            'is_title'=>$eng->is_title,
            'is_leaf'=>$eng->is_leaf,
            'niveau'=>$eng->niveau,
        ];
        if($data['is_leaf']){
            $elts = EngagementEntreprise::where('engagement_id',$eng->id)->where('entreprise_id',$id)->get();
            //dd($elts);
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
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];

        }else{
            $data['children'] = $eng->children->map(function($child)use($id){
                return $this->parse($child,$id);
            });
            foreach($data['children'] as $child){
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }
        return $data;
    }

    public function getEngagementReport($token){
        $entreprise = Entreprise::where('token',$token)->first();
        if($entreprise){
            $engagements = Engagement::where('parent_id',0)->get();
             $data = [];
             foreach($engagements as $eng){
                 $data[] = $this->parse($eng,$entreprise->id);
             }
             //dd($data);

            $engagements = $data;
            $banques = Banque::all();
            //$engagements = EngagementEntreprise::where('entreprise_id',$entreprise->id)->get();
            return view('Ca.Companies.engagement_report',compact('engagements','entreprise','banques'));
        }else{
            return back();
        }

    }


    public function saveProgramme(Request $request)
    {
        $data = $request->all();
        $data['token']=sha1(time().rand(1,100));
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        Dossier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'programme_id'=>$request->programme_id,
            ],
            $data
        );
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }


    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        //dd($item);
        $reponses = $item->reponses;
        $groups = $reponses->groupBy('critere_id');
        $groups = $groups->map(function($v,$k){
            $critere = Critere::find($k);
            return ['critere'=>$critere,
             'items'=>$v->groupBy('sous_critere_id')
                        ->map(function($m,$n){
                            $sc = QuestionSousCritere::find($n);
                            return [
                                'sous_critere'=>$sc,
                                'items'=>$m
                            ];
                        })
            ];
        });
        //dd($groups);
        $mr = $groups;
        $analystes = User::where('role_id',14)->where('agence_id',auth()->user()->agence_id)->get();
        //$cas = User::where('role_id',14)->where('agence_id',auth()->user()->agence_id)->get();
        $programmes = Programme::all();
        return view('/Ca/Companies/show',compact('item','mr','programmes','analystes'));

    }


    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien','entreprise_id','token');
        $data['token']=sha1(time().rand(1,100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu',$data['niu'])->where('phone',$data['phone'])->first();
        if(!$person){
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'person_id'=>$person->id,
            ],
            [
                'entreprise_id'=>$request->entreprise_id,
                'person_id'=>$person->id,
                'lien'=>$request->lien
            ]
        );

        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('ca.entreprises.show',$token));
    }

    public function saveTiersMorale(Request $request)
    {
        //dd($request->all());
        $token = $request->token;
        $data = $request->except('lien','entreprise_id','token');
        $data['token'] = sha1(time().rand(1,9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::where('phone',$data['phone'])->orWhere('email',$data['email'])->first();
        if(!$entreprise){
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
            'entreprise_id'=>$request->entreprise_id,
            'company_id'=>$entreprise->id,
            ],
            [
                'entreprise_id'=>$request->entreprise_id,
                'company_id'=>$entreprise->id,
                'lien'=>$request->lien
            ]
        );


        Session::flash('success','Enregistrement effectué avec succès!');
        return redirect(route('ca.entreprises.show',$token));
    }


    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token',$token)->first();
        if(!$item){
            return back();
        }
        $criteres = QuestionSousCritere::all();
        return view('/Ca/Companies/questionnaire',compact('item','criteres'));
    }

    public function saveQuestionnaire(Request $request)
    {
        //dd($request->choices[1]);
        $choices = $request->choices;
        foreach($choices as $choice){
            QuestionAnswer::updateOrCreate([
                'entreprise_id'=>$choice['entreprise_id'],
                'choice_id'=>$choice['choice_id']
            ],$choice);
        }
        //Session::flash('success','Enregistrement effectué avec succès!');
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
