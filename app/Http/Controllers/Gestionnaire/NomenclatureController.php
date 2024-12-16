<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Filiere;
use App\Models\Produit;
use App\Models\Service;
use Illuminate\Http\Request;

class NomenclatureController extends Controller
{
    //
    public function getFilieres(){
        $items = Filiere::all();
        return view('/Gestionnaire/Nomenclature/filieres')->with(compact('items'));
    }

    public function getProduits(){
        $items = Produit::all();
        return view('/Gestionnaire/Nomenclature/produits')->with(compact('items'));
    }

    public function getServices(){
        $items = Service::all();
        return view('/Gestionnaire/Nomenclature/services')->with(compact('items'));
    }
}
