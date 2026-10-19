<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use App\Services\Inertia\PilotagePresenter;
use App\Services\TableauDeBordService;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class TableauDeBordController extends Controller
{
    public function __construct(private readonly TableauDeBordService $service) {}

    /**
     * Page Inertia : une famille affichée à la fois (paramètre `famille`), filtres de période `from` / `to`.
     * Les endpoints JSON par famille (ci-dessous) restent disponibles.
     */
    public function index(Request $request, PilotagePresenter $presenter)
    {
        $familles = $this->familles();
        $codes = array_column($familles, 'code');
        $active = in_array($request->query('famille'), $codes, true) ? (string) $request->query('famille') : $codes[0];
        $scope = $this->scopeFromRequest($request);

        $prefix = UserWorkspaceContextResolver::routePrefix($request->user());

        return Inertia::render('Pilotage/Index', [
            'familles' => $familles,
            'active' => $active,
            'filters' => [
                'from' => (string) $request->query('from', ''),
                'to' => (string) $request->query('to', ''),
            ],
            'perimetre' => ! empty($scope['agence_id']) ? optional(Agence::find($scope['agence_id']))->name : null,
            'data' => $presenter->famille($active, $scope),
            'baseUrl' => route('tdb.index'),
            'breadcrumb' => [
                ['label' => 'Angara', 'href' => $prefix && Route::has($prefix.'.dashboard') ? route($prefix.'.dashboard') : url('/')],
                ['label' => 'Tableaux de bord'],
            ],
        ]);
    }

    public function operationnel(Request $request): JsonResponse
    {
        return response()->json($this->service->operationnel($this->scopeFromRequest($request)));
    }

    public function portefeuille(Request $request): JsonResponse
    {
        return response()->json($this->service->portefeuille($this->scopeFromRequest($request)));
    }

    public function risques(Request $request): JsonResponse
    {
        return response()->json($this->service->risques($this->scopeFromRequest($request)));
    }

    public function strategique(Request $request): JsonResponse
    {
        return response()->json($this->service->strategique($this->scopeFromRequest($request)));
    }

    public function programmes(Request $request): JsonResponse
    {
        return response()->json($this->service->programmes($this->scopeFromRequest($request)));
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function familles(): array
    {
        return [
            ['code' => 'operationnel', 'label' => 'Opérationnel', 'description' => 'Activité quotidienne — dossiers en cours, en attente, délais, rejets'],
            ['code' => 'portefeuille', 'label' => 'Portefeuille', 'description' => 'Volume crédits, encours, répartitions filière / secteur'],
            ['code' => 'risques', 'label' => 'Risques', 'description' => 'Dossiers à risque, alertes ; impayés non disponibles avec le schéma actuel'],
            ['code' => 'strategique', 'label' => 'Stratégique', 'description' => 'Performance globale, croissance, impact des programmes'],
            ['code' => 'programmes', 'label' => 'Programmes', 'description' => 'Bénéficiaires, montants financés, impact économique'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function scopeFromRequest(Request $request): array
    {
        $scope = [];
        if ($request->filled('agence_id')) {
            $scope['agence_id'] = (int) $request->input('agence_id');
        }
        if ($request->filled('from')) {
            $scope['from'] = (string) $request->input('from');
        }
        if ($request->filled('to')) {
            $scope['to'] = (string) $request->input('to');
        }

        $user = $request->user();
        $rolesAgenceScope = [
            (int) config('angara.role_chef_agence'),
            (int) config('angara.role_gestionnaire'),
        ];
        $userRoleId = (int) ($user->role_id ?? 0);
        if ($user && in_array($userRoleId, $rolesAgenceScope, true) && empty($scope['agence_id']) && $user->agence_id) {
            $scope['agence_id'] = $user->agence_id;
        }

        return $scope;
    }
}
