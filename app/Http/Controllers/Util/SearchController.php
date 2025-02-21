<?php

namespace App\Http\Controllers\Util;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocaliteListResource;
use App\Http\Resources\ObjectResource;
use App\Http\Resources\ProduitListResource;
use App\Http\Resources\ServiceListResource;
use App\Models\Agence;
use App\Models\Forme;
use App\Models\Organisme;
use App\Models\Produit;
use App\Models\Region;
use App\Models\Service;

class SearchController extends Controller
{
    //
    public function getAgencesByVilleId(){
        $id = request()->id;
        $items = Agence::where('ville_id',$id)->get();
        return response()->json($items);
    }

    public function fetchAllProduit(){
        $produits = Produit::where('parent_id',0)->get();
        return response()->json(ProduitListResource::collection($produits));
    }

    public function fetchAfs(){
        $items = Service::where('financier',1)->get();
        return response()->json(ServiceListResource::collection($items));
    }

    public function fetchAnfs(){
        $items = Service::where('financier',0)->get();
        return response()->json(ServiceListResource::collection($items));
    }

    public function getOrganismes(){
        $items = Organisme::all();
        return response()->json(ObjectResource::collection($items));
    }

    public function getLocalites(){
        $regions = Region::all();
        $localites = LocaliteListResource::collection($regions);
        return response()->json($localites);
    }

    public function loadEntrepriseData(){
        $anfs = Service::where('financier',0)->get();
        $afs = Service::where('financier',1)->get();
        $produits = Produit::where('parent_id',0)->get();
        $regions = Region::all();
        $localites = LocaliteListResource::collection($regions);
        $produits = ProduitListResource::collection($produits);
        $anfs = ServiceListResource::collection($anfs);
        $afs = ServiceListResource::collection($afs);
        $formes = Forme::all();

        return response()->json([
            'produits'=>$produits,
            'afs'=>$afs,
            'anfs'=>$anfs,
            'localites'=>$localites,
            'formes'=>$formes,
        ]);
    }

    public function loadProgrammeData(){
        $anfs = Service::where('financier',0)->get();
        $afs = Service::where('financier',1)->get();
        $produits = Produit::where('parent_id',0)->get();
        $organismes = Organisme::all();
        $organismes = ObjectResource::collection($organismes);
        $produits = ProduitListResource::collection($produits);
        $anfs = ServiceListResource::collection($anfs);
        $afs = ServiceListResource::collection($afs);

        return response()->json([
            'produits'=>$produits,
            'afs'=>$afs,
            'anfs'=>$anfs,
            'organismes'=>$organismes
        ]);
    }


}
