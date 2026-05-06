<?php

namespace App\Services;

use App\Models\AnalyseCritiqueAvis;
use App\Models\Dossier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

/**
 * Données harmonisées pour la consultation d’un dossier d’instruction (budgets multi-programmes + chronologie).
 */
class InstructionDossierConsultationService
{
    public function __construct(
        private InstructionDossierAnalyseCritiqueSyntheseService $syntheseService
    ) {}

    /**
     * @return array{
     *   has_budget_rows: bool,
     *   lignes: list<array{programme_name: string, financier: float, non_financier: float, total_ligne: float}>,
     *   totaux: array{financier: float, non_financier: float, general: float},
     *   timeline: Collection<int, array<string, mixed>>,
     *   timeline_count: int
     * }
     */
    public function build(Dossier $dossier): array
    {
        $this->syntheseService->loadDossierRelationsForTimeline($dossier);
        $dossier->loadMissing([
            'instructionProgrammes.programme',
        ]);

        $lignes = [];
        $totFin = 0.0;
        $totNonFin = 0.0;
        foreach ($dossier->instructionProgrammes as $dip) {
            $bf = (float) $dip->budget_appui_financier;
            $bnf = (float) $dip->budget_appui_non_financier;
            $totFin += $bf;
            $totNonFin += $bnf;
            $lignes[] = [
                'programme_name' => $dip->programme?->name ?? '—',
                'financier' => $bf,
                'non_financier' => $bnf,
                'total_ligne' => $bf + $bnf,
            ];
        }

        $workflowTimeline = $dossier->instructionWorkflowHistoryTimeline()->map(function (array $row) {
            $actor = $row['actor'] ?? null;
            if ($actor instanceof User && ! $actor->relationLoaded('role')) {
                $actor->loadMissing('role');
            }

            return array_merge($row, [
                'actor_role' => $actor instanceof User ? ($actor->role?->name) : null,
                'avis_source' => null,
            ]);
        });

        $avisTimeline = AnalyseCritiqueAvis::query()
            ->where('instruction_dossier_id', $dossier->id)
            ->with(['emisPar.role'])
            ->orderByDesc('emis_at')
            ->orderByDesc('id')
            ->get()
            ->map(function (AnalyseCritiqueAvis $avis) {
                $u = $avis->emisPar;

                return [
                    'at' => $avis->emis_at,
                    'sort' => 0,
                    'label' => $avis->source_label ?: $this->avisSourceTypeLabel($avis->source_type),
                    'actor' => $u,
                    'actor_role' => $u?->role?->name,
                    'body_html' => $avis->contenu,
                    'kind' => 'analyse_critique',
                    'avis_source' => $avis->source_type,
                    'avis_etat' => $avis->etat,
                ];
            });

        $instructionStartsAt = $dossier->created_at instanceof Carbon ? $dossier->created_at : null;

        $timeline = $workflowTimeline
            ->concat($avisTimeline)
            ->when($instructionStartsAt !== null, function (Collection $c) use ($instructionStartsAt) {
                return $c->filter(function (array $r) use ($instructionStartsAt) {
                    $t = $r['at'] ?? null;

                    return $t instanceof Carbon && $t->greaterThanOrEqualTo($instructionStartsAt);
                });
            })
            ->sortByDesc(function (array $r) {
                $t = $r['at'] ?? null;

                return $t instanceof Carbon ? $t->timestamp : 0;
            })
            ->values();

        return [
            'has_budget_rows' => count($lignes) > 0,
            'lignes' => $lignes,
            'totaux' => [
                'financier' => $totFin,
                'non_financier' => $totNonFin,
                'general' => $totFin + $totNonFin,
            ],
            'timeline' => $timeline,
            'timeline_count' => $timeline->count(),
        ];
    }

    private function avisSourceTypeLabel(?string $sourceType): string
    {
        return match ($sourceType) {
            AnalyseCritiqueAvis::SOURCE_JURIDIQUE => 'Pôle juridique',
            AnalyseCritiqueAvis::SOURCE_CONFORMITE => 'Conformité',
            AnalyseCritiqueAvis::SOURCE_GESTIONNAIRE => 'Gestionnaire',
            AnalyseCritiqueAvis::SOURCE_ANALYSTE => 'Analyste financier',
            AnalyseCritiqueAvis::SOURCE_CHEF_AGENCE => 'Chef d’agence',
            AnalyseCritiqueAvis::SOURCE_CHEF_FILIERE => 'Chef de filière',
            AnalyseCritiqueAvis::SOURCE_INSTRUCTION => 'Instruction',
            AnalyseCritiqueAvis::SOURCE_EER => 'Entrée en relation',
            AnalyseCritiqueAvis::SOURCE_AUTRE => 'Autre',
            default => $sourceType ?: 'Avis',
        };
    }
}
