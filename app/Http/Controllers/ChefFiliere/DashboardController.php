<?php

namespace App\Http\Controllers\ChefFiliere;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;

class DashboardController extends Controller
{
    public function index()
    {
        return view('ChefFiliere.dashboard');
    }

    public function getStats()
    {
        $agenceId = auth()->user()->agence_id;

        $pendingQualif = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($e) => $e->whereNull('programmes_submitted_at'));
            })
            ->count();

        $clientsCount = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->count();

        $instructionPending = DossierEntreeRelation::query()
            ->where('statut', DossierEntreeRelation::STATUT_EN_VALIDATION_INSTRUCTION)
            ->whereNull('qualification_validated_by_agence_at')
            ->whereHas('entreprise', fn ($q) => $q->where('agence_id', $agenceId))
            ->count();

        $instructionEnCours = Dossier::query()
            ->where('agence_id', $agenceId)
            ->whereNull('instruction_closure_validated_at')
            ->whereNull('instruction_closure_rejected_at')
            ->count();

        return response()->json([
            'pending_qualif' => $pendingQualif,
            'clients_count' => $clientsCount,
            'instruction_pending' => $instructionPending,
            'instruction_en_cours' => $instructionEnCours,
        ]);
    }

    public function getInsights()
    {
        $agenceId = auth()->user()->agence_id;

        // Répartition "structuration client" (EER) basée sur le scope existant.
        $struct = [
            'structure' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_STRUCTURE)
                ->count(),
            'en_cours' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_EN_COURS)
                ->count(),
            'attente' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_ATTENTE)
                ->count(),
            'rejetee' => Entreprise::query()
                ->where('agence_id', $agenceId)
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_REJETEE)
                ->count(),
        ];

        // File "à structurer" (clients promus sans structuration complétée).
        $toStructure = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->where(function ($q) {
                $q->whereDoesntHave('dossierEntreeRelation')
                    ->orWhereHas('dossierEntreeRelation', fn ($eer) => $eer->whereNull('qualification_completed_at'));
            })
            ->orderByDesc('promu_client_at')
            ->limit(10)
            ->get(['id', 'token', 'name', 'promu_client_at']);

        // File "rejets agence à corriger" (structuration refusée, non resoumise).
        $rejectedToFix = Entreprise::query()
            ->where('agence_id', $agenceId)
            ->whereNotNull('promu_client_at')
            ->whereHas('dossierEntreeRelation', function ($eer) {
                $eer->whereNotNull('qualification_rejected_by_agence_at')
                    ->whereNull('qualification_validated_by_agence_at')
                    ->whereNull('programmes_submitted_at');
            })
            ->with(['dossierEntreeRelation:id,entreprise_id,qualification_rejected_by_agence_at,qualification_reject_motif'])
            ->orderByDesc('promu_client_at')
            ->limit(10)
            ->get(['id', 'token', 'name', 'promu_client_at']);

        return response()->json([
            'structuration' => $struct,
            'to_structure' => $toStructure,
            'rejected_to_fix' => $rejectedToFix,
        ]);
    }
}
