<?php

namespace App\Http\Controllers\Sectoriel;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\Structuration\CampagneResource;
use App\Models\Niveau;
use App\Models\Structuration\Campagne;
use App\Models\Structuration\Cooperative;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Exploitant;
use App\Models\Structuration\ExploitantPlateforme;
use App\Models\Structuration\Membre;
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
        $items = Membre::where('cooperative_id',auth()->user()->cooperative_id)->get();
        $coop = Cooperative::find(auth()->user()->cooperative_id);
        $villages = Village::where('arrondissement_id',$coop->arrondissement_id)->get();
        $niveaux = Niveau::all();
        return view('Sectoriel/Members/index')->with(compact('villages','items','niveaux'));
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Projet  $projet
     * @return \Illuminate\Http\Response
     */
	public function show()
	{
        $token=request()->token;
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $villages = Village::where('arrondissement_id',$tenant->arrondissement_id)->get();
        $data = $tenant->run(function()use($token){
            $item = Membre::where('token',$token)->first();
            $parts = Entree::where('saison_id',$this->_saison->id)->where('exploitant_id',$item->id)->get();
            return [
                'item'=>$item,
                'parts'=>$parts,
            ];
        });
        tenancy()->initialize($tenant);

        $item = $data['item'];
        $parts = $data['parts'];

		return view('Sectoriel/Cooperatives/member')->with(compact('item','parts','villages','tenant_id'));
	}

    public function getVerger()
	{
        $token=request()->token;
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data = $tenant->run(function()use($token){
            $item = Verger::where('token',$token)->first();
            $campagnes = Campagne::orderBy('created_at','DESC')->where('verger_id',$item->id)->get();
            return [
                'item'=>$item,
                'campagnes'=> CampagneResource::collection($campagnes)->toJson()
            ];
        });
        tenancy()->initialize($tenant);

        $item = $data['item'];
        $campagnes = json_decode($data['campagnes'],true);
        $produits = ProduitPhytoSanitaire::where('active',1)->get();
        $travaux = TypeTravailVerger::where('active',1)->get();

		return view('Sectoriel/Cooperatives/verger')->with(compact('item','tenant_id','campagnes','produits','travaux'));
	}

    public function addTravailCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        //dd($data);
        $tenant->run(function()use($data,){
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
        });
        tenancy()->initialize($tenant);
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

        public function addVisiteCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        //dd($data);
        $tenant->run(function()use($data,){
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
        });
        tenancy()->initialize($tenant);
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

    public function setRendementCampagne(){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        //dd($data);
        $tenant->run(function()use($data,){
            $campagne = Campagne::find($data['campagne_id']);
            $campagne->rendement = $data['rendement'];
            $campagne->save();
        });
        tenancy()->initialize($tenant);
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }

    public function addTraitementCampagne(Request $request){
        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        //dd($data);
        $tenant->run(function()use($data,){
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
        });
        tenancy()->initialize($tenant);
        Session::flash('success','Enregistrement effectue avec succes');
        return back();
    }


    public function addVerger(Request $request){

        $data = request()->except('tenant_id');
        $tenant_id = request()->tenant_id;
        $tenant = Tenant::where('token',$tenant_id)->first();
        $data['user_id'] = auth()->user()->id;
        $photo = $request->photo;
        $tenant->run(function()use($data,$photo){
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
        });
    tenancy()->initialize($tenant);

    return back();

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


}
