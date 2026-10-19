<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Http\Resources\LocaliteListResource;
use App\Models\Agence;
use App\Models\Arrondissement;
use App\Models\Departement;
use App\Models\Quartier;
use App\Models\Region;
use App\Models\Representation;
use App\Models\Village;
use App\Services\Inertia\TerritoirePresenter;
use Illuminate\Http\Request;

class TerritoireController extends Controller
{
    //

    public function index(){

        return TerritoirePresenter::render('gestionnaire');
    }

}
