<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Produit;
use App\Models\Service;

class NomenclatureController extends Controller
{
    //
    public function getFilieres(){
        $items = Filiere::all();
        return view('/Program/Nomenclature/filieres')->with(compact('items'));
    }

    public function getProduits(){
        $items = Produit::all();
        return view('/Program/Nomenclature/produits')->with(compact('items'));
    }

    public function getServices(){
        $items = Service::all();
        return view('/Program/Nomenclature/services')->with(compact('items'));
    }
}
