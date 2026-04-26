<?php

namespace App\Services;

use App\Models\DossierInstructionProgramme;
use App\Models\Programme;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProgrammeInstructionBudgetConsumptionService
{
    /**
     * Montants engagés sur les dossiers d’instruction dont la validation chef d’agence est acquise,
     * pour une ligne programme donnée dans {@see DossierInstructionProgramme}.
     *
     * @return array{
     *   engaged_financier: float,
     *   engaged_non_financier: float,
     *   engaged_total: float,
     *   lignes: Collection<int, object{
     *     dip_id: int,
     *     dossier_id: int,
     *     dossier_token: string,
     *     budget_appui_financier: float,
     *     budget_appui_non_financier: float,
     *     instruction_agence_validated_at: string|null,
     *     entreprise_name: string|null,
     *     entreprise_token: string|null,
     *     agence_name: string|null
     *   }>
     * }
     */
    public function summarizeValidatedAgence(Programme $programme): array
    {
        $cx = (new DossierInstructionProgramme)->getConnectionName();

        $rows = DB::connection($cx)
            ->table('dossier_instruction_programmes as dip')
            ->join('dossiers as d', 'd.id', '=', 'dip.dossier_id')
            ->leftJoin('entreprises as e', 'e.id', '=', 'd.entreprise_id')
            ->leftJoin('agences as ag', 'ag.id', '=', 'd.agence_id')
            ->where('dip.programme_id', $programme->id)
            ->whereNotNull('d.instruction_agence_validated_at')
            ->orderByDesc('d.instruction_agence_validated_at')
            ->orderByDesc('dip.id')
            ->select([
                'dip.id as dip_id',
                'dip.dossier_id',
                'dip.budget_appui_financier',
                'dip.budget_appui_non_financier',
                'd.token as dossier_token',
                'd.instruction_agence_validated_at',
                'e.name as entreprise_name',
                'e.token as entreprise_token',
                'ag.name as agence_name',
            ])
            ->get();

        $engagedFin = (float) $rows->sum(fn ($r) => (float) $r->budget_appui_financier);
        $engagedNf = (float) $rows->sum(fn ($r) => (float) $r->budget_appui_non_financier);

        $lignes = $rows->map(fn ($r) => (object) [
            'dip_id' => (int) $r->dip_id,
            'dossier_id' => (int) $r->dossier_id,
            'dossier_token' => (string) $r->dossier_token,
            'budget_appui_financier' => (float) $r->budget_appui_financier,
            'budget_appui_non_financier' => (float) $r->budget_appui_non_financier,
            'instruction_agence_validated_at' => $r->instruction_agence_validated_at,
            'entreprise_name' => $r->entreprise_name,
            'entreprise_token' => $r->entreprise_token,
            'agence_name' => $r->agence_name,
        ]);

        return [
            'engaged_financier' => $engagedFin,
            'engaged_non_financier' => $engagedNf,
            'engaged_total' => $engagedFin + $engagedNf,
            'lignes' => $lignes,
        ];
    }
}
