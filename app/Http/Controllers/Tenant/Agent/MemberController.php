<?php

namespace App\Http\Controllers\Tenant\Agent;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\Structuration\CampagneResource;
use App\Models\Arrondissement;
use App\Models\Entreprise;
use App\Models\Instruction\Scoring\Individual\CritereCategory;
use App\Models\Niveau;
use App\Models\Structuration\Campagne;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Membre;
use App\Models\Structuration\MembrePlateforme;
use App\Models\Structuration\Plateforme;
use App\Models\Structuration\ProduitPhytoSanitaire;
use App\Models\Structuration\TraitementVerger;
use App\Models\Structuration\TravailVerger;
use App\Models\Structuration\TypeTravailVerger;
use App\Models\Structuration\Verger;
use App\Models\Structuration\VisiteVerger;
use App\Models\Tenant;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberController extends ExtendedController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $items = Membre::all();
        $coop = tenant();
        $villages = Village::where('arrondissement_id',$coop->arrondissement_id)->get();
        $niveaux = Niveau::all();
        return view('Tenant/Agent/Members/index')->with(compact('villages','items','niveaux'));
    }

    public function editScoring($token){
        $item = Verger::where('token',$token)->first();
        $entreprise = Entreprise::where('verger_id',$item->id)->where('cooperative_id',tenant()->id)->first();
        if(!$entreprise){
            //dd($item);
            $data = [];
            $data['producteur_id'] = $item->membre_id;
            $data['name'] = $item->name. ' - ' . $item->membre?->name;
            $data['cooperative_id'] = tenant()->id;
            $data['verger_id'] = $item->id;
            $data['token'] = sha1(time().rand(0,99));
            //$village = Village::find($item->village_id);
            //$ar = $village->arrondissement;
            $data['departement_id'] = $item->departement_id;
            $data['region_id'] = $item->region_id;
            $data['user_id'] = auth()->user()->id;
            $data['agence_id'] = tenant()->agence_id;
            $data['representation_id'] = tenant()->representation_id;
            $data['individual'] = 1;
            $data['manager'] = $item->membre?->name;
            $data['taille'] = 'TRES PETITE';
            $data['caractere'] = 'informel';
            $data['forme_id'] = 7;
            $data['village_id'] = $item->village_id;
            $entreprise = Entreprise::create($data);
        }
        $answers = $entreprise->answers;
        $categories =  CritereCategory::orderBy('id','ASC')->get();
        $grps = $answers->groupBy(function($a){
            return $a->critere?->category_id;
        });
        $notes = $grps->map(function($item,$key){
            $total = $item->sum('score');
            $pondere = $item->sum('score_pondere');
            return [
                'total'=>$total,
                'pondere'=>$pondere
            ];
        });
        return view('Tenant/Agent/Members/scoring')->with(compact('item','categories','entreprise','answers','notes'));
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
        $data = $request->except('photo');
        $data['token'] = sha1(time().auth()->user()->id);
        $coop = tenant();
        $data['agence_id'] = $coop->agence_id;
        $data['representation_id'] = $coop->representation_id;
        $data['arrondissement_id'] = $coop->arrondissement_id;
        $data['departement_id'] = $coop->departement_id;
        $data['region_id'] = $coop->region_id;
        if($request->photo){
            $data['photo_uri'] = $this->entityImgCreate($request->photo,'members',$data['token']);
        }
        Membre::create($data);
        Session::flash('success','Nouvel adherent créé avec succès!');
        return back();
    }

    public function getVerger()
	{
        $token=request()->token;
        $item = Verger::where('token',$token)->first();
        $campagnes = Campagne::orderBy('created_at','DESC')->where('verger_id',$item->id)->get();


        $produits = ProduitPhytoSanitaire::where('active',1)->get();
        $travaux = TypeTravailVerger::where('active',1)->get();

		return view('Tenant/Agent/Members/verger')->with(compact('item','campagnes','produits','travaux'));
	}


    public function addCampagneVerger(Request $request){

        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        $tenant->run(function()use($data,){
            $verger = Verger::find($data['verger_id']);
            $item = new Campagne();
            $item->ombrage = $data['ombrage'];
            $item->observations = $data['observations'];
            $item->etat = $data['etat'];
            $item->morts = $data['morts'];
            $item->replantations = $data['replantations'];
            $item->membre_id = $verger->membre_id;
            $item->saison_id = $this->_saison->id;
            $item->token = sha1(time());
            $item->user_id = $data['user_id'];
            $item->verger_id = $data['verger_id'];

            $item->save();
        });
    tenancy()->initialize($tenant);
    Session::flash('success','Enregistrement effectue avec succes');
    return back();

    }

    public function addTravailCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        $campagne = Campagne::find($data['campagne_id']);
        $item = new TravailVerger();
        $item->campagne_id = $data['campagne_id'];
        $item->type_id = $data['type_id'];
        $item->verger_id = $campagne->verger_id;
        $item->saison_id = $campagne->saison_id;
        $item->jour = $data['jour'];
        $item->membre_id = $campagne->membre_id;
           // $item->token = sha1(time());
        $item->user_id = $data['user_id'];
        $item->methode = $data['methode'];
        $item->description = $data['description'];
        $item->save();
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

    public function addVisiteCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        $campagne = Campagne::find($data['campagne_id']);
        $item = new VisiteVerger();
        $item->campagne_id = $data['campagne_id'];
        $item->technicien = $data['technicien'];
        $item->verger_id = $campagne->verger_id;
        $item->saison_id = $campagne->saison_id;
        $item->jour = $data['jour'];
        $item->membre_id = $campagne->membre_id;
        $item->user_id = $data['user_id'];
        $item->name = $data['name'];
        $item->description = $data['description'];
        $item->save();
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

    public function setRendementCampagne(){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        //dd($data);
        $campagne = Campagne::find($data['campagne_id']);
        $campagne->rendement = $data['rendement'];
        $campagne->save();
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

    public function addTraitementCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        //dd($data);
        $campagne = Campagne::find($data['campagne_id']);
        $item = new TraitementVerger();
        $item->campagne_id = $data['campagne_id'];
        $item->produit_id = $data['produit_id'];
        $item->verger_id = $campagne->verger_id;
        $item->saison_id = $campagne->saison_id;
        $item->jour = $data['jour'];
        $item->membre_id = $campagne->membre_id;
        // $item->token = sha1(time());
        $item->user_id = $data['user_id'];
        $item->dosage = $data['dosage'];
        $item->description = $data['description'];
        $item->save();
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }


    public function addVerger(Request $request){

    $data = $request->except('photo');
    $tenant = tenant();
    $data['user_id'] = auth()->user()->id;
    $photo = $request->photo;
    $village = Village::find($data['village_id']);
    $item = new Verger();
    $item->name = $data['name'];
    $item->nbph = $data['nbph'];
    $item->size = $data['size'];
    $item->annee = $data['annee'];
    $item->membre_id = $data['member_id'];
    $item->token = sha1(time());
    $item->arrondissement_id = $village->arrondissement_id;
    $item->departement_id = $village->departement_id;
    $item->region_id = $village->region_id;
    $item->localisation = $data['localisation'];
    $item->village_id = $data['village_id'];
    if($photo){
        $item->photo_uri = $this->entityImgCreate($photo,'vergers',$item->token);
    }
    $item->save();
    $data = [];
    $data['producteur_id'] = $item->membre_id;
    $data['name'] = $item->name. ' - ' . $item->membre?->name;
    $data['cooperative_id'] = $tenant->id;
    $data['verger_id'] = $item->id;
    $data['token'] = sha1(time().rand(0,99));
    //$village = Village::find($data['village_id']);
    $ar = $village->arrondissement;
    $data['departement_id'] = $ar->departement_id;
    $data['region_id'] = $ar->departement->region_id;
    $data['user_id'] = auth()->user()->id;
    $data['agence_id'] = tenant()->agence_id;
    $data['representation_id'] = tenant()->representation_id;
    $data['individual'] = 1;
    $data['manager'] = $item->membre?->name;
    //$data['promoteur_name'] = $item->membre?->name;
    $data['taille'] = 'TRES PETITE';
    $data['caractere'] = 'informel';
    $data['forme_id'] = 7;
    $data['village_id'] = $item->village_id;
    $entreprise = Entreprise::create($data);

   /* $dossier = Dossier::create([
        'name' => $data['name']. ' - ' . $verger['producteur'],
        'promoteur' => $verger['producteur'],
        'superficie_ha' => $data['size'],
        'producteur_id' => $data['member_id'],
        'cooperative_id' => $tenant->id,
        'localisation' => $data['localisation'],
        'annee' => $data['annee'],
        'exploitation_id' => $verger['id'],
        'token' => sha1(time()),
    ]); */

    return back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show($token)
	{
		$item = Membre::where('token',$token)->first();
        $parts = Entree::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        $villages = Village::where('arrondissement_id',tenant()->arrondissement_id)->get();
        //$pps = PaiementPart::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
        $plateformes = Plateforme::all();
		return view('Tenant/Agent/Members/show')->with(compact('item','parts','plateformes','villages'));
	}

    public function addKey(Request $request){
        $data = $request->all();
        MembrePlateforme::updateOrCreate([
            'plateforme_id'=>$data['plateforme_id'],
            'exploitant_id'=>$data['exploitant_id']
        ],$data);
        Session::flash('success','Enregistrement effectué avec succès!');
        return back();
    }


}
