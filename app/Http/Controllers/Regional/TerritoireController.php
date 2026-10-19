<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Controller;
use App\Models\Agence;
use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\Region;
use App\Models\Representation;
use App\Models\Village;
use App\Services\Inertia\TerritoirePresenter;

class TerritoireController extends Controller
{
    //

    public function index(){

        return TerritoirePresenter::render('regional');
    }

}
