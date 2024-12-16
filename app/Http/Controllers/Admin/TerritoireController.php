<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocaliteListResource;
use App\Models\Agence;
use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\Region;
use App\Models\Representation;
use App\Models\Village;
use Illuminate\Http\Request;

class TerritoireController extends Controller
{
    //

    public function index(){

        return view('/Admin/Territoire/index');
    }



    public function getCommunes(){
        $items = Arrondissement::all();
        return view('/Admin/Territoire/communes')->with(compact('items'));
    }

    public function getDepartements(){
        $items = Departement::all();
        return view('/Admin/Terroitoire/departements')->with(compact('items'));
    }

    public function getRegions(){
        $items = Region::all();
        return view('/Admin/Terroitoire/regions')->with(compact('items'));
    }

    public function getRepresentations(){
        $items = Representation::all();
        return view('/Admin/Terroitoire/representations')->with(compact('items'));
    }

    public function getAgences(){
        $items = Agence::all();
        return view('/Admin/Terroitoire/agences')->with(compact('items'));
    }

    public function getQuartiers(){
        $items = Quartier::all();
        return view('/Admin/Terroitoire/quartiers')->with(compact('items'));
    }

    public function getVillages(){
        $items = Village::all();
        return view('/Admin/Terroitoire/villages')->with(compact('items'));
    }
}
