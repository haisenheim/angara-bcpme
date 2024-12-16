<?php

namespace App\Http\Controllers\Admin;

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
        return view('/Admin/Nomenclature/filieres')->with(compact('items'));
    }

    public function getProduits(){
        $items = Produit::all();
        return view('/Admin/Nomenclature/produits')->with(compact('items'));
    }

    public function getServices(){
        $items = Service::all();
        return view('/Admin/Nomenclature/services')->with(compact('items'));
    }
}
