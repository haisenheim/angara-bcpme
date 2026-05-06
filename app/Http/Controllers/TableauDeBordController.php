<?php

namespace App\Http\Controllers;

use App\Services\TableauDeBordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TableauDeBordController extends Controller
{
    public function __construct(private readonly TableauDeBordService $service) {}

    public function index(Request $request)
    {
        return view('tdb.index', [
            'familles' => $this->familles(),
            'scope' => $this->scopeFromRequest($request),
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
