<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Concerns\ResolvesGovernanceRoutePrefix;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgrammeListResource;
use App\Models\Banque;
use App\Models\Indicateur;
use App\Models\Organisme;
use App\Models\Programme;
use App\Services\ProgrammeInstructionBudgetConsumptionService;
use Illuminate\Support\Facades\Session;

class ProgrammeController extends Controller
{
    use ResolvesGovernanceRoutePrefix;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('/Ca/Programmes/index', [
            'layout' => $this->governanceLayout(),
            'routePrefix' => $this->governanceRoutePrefix(),
        ]);
    }


    public function fetchAll(){
        $items = Programme::all();
        $items = ProgrammeListResource::collection($items);
        return response()->json($items);
    }

    public function show(string $token)
    {
        //
        $item = Programme::query()
            ->where('token', $token)
            ->with([
                'produits.filiere',
                'produits.branche',
                'appuis.type',
                'composantes',
                'resultats.indicateur',
                'entreprises',
            ])
            ->first();
        if (! $item) {
            Session::flash('error', 'Accès non autorisé à ce programme!');

            return back();
        }
        $banques = Banque::all();
        $organismes = Organisme::all();
        $indicateurs = Indicateur::all();

        $routePrefix = $this->governanceRoutePrefix();
        $instructionBudgetConsumption = in_array($routePrefix, ['dg', 'dga'], true)
            ? app(ProgrammeInstructionBudgetConsumptionService::class)->summarizeValidatedAgence($item)
            : null;

        return view('Ca/Programmes/show', [
            'item' => $item,
            'banques' => $banques,
            'organismes' => $organismes,
            'indicateurs' => $indicateurs,
            'layout' => $this->governanceLayout(),
            'routePrefix' => $routePrefix,
            'instructionBudgetConsumption' => $instructionBudgetConsumption,
        ]);
    }


}
