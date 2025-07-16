<?php

namespace App\Http\Controllers\Sectoriel;

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
        return view('/Sectoriel/Nomenclature/filieres')->with(compact('items'));
    }

    public function getProduits(){
        $items = Produit::all();
        return view('/Sectoriel/Nomenclature/produits')->with(compact('items'));
    }

    public function getServices(){
        $items = Service::all();
        return view('/Sectoriel/Nomenclature/services')->with(compact('items'));
    }
}
