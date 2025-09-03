<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Http\Resources\Structuration\CampagneResource;
use App\Models\Structuration\Campagne;
use App\Models\Structuration\Entree;
use App\Models\Structuration\Membre;
use App\Models\Structuration\ProduitPhytoSanitaire;
use App\Models\Structuration\TypeTravailVerger;
use App\Models\Structuration\Verger;
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
		return view('Gestionnaire/Cooperatives/member')->with(compact('item','parts','villages','tenant_id'));
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

		return view('Gestionnaire/Cooperatives/verger')->with(compact('item','tenant_id','campagnes','produits','travaux'));
	}

    public function addKey(Request $request){
        //$data = $request->all();

    }


}
