<?php

namespace App\Services;

use App\Models\AnalyseCritiqueAvis;
use App\Models\Dossier;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class InstructionDossierAnalyseCritiqueSyntheseService
{
    public function loadDossierRelationsForTimeline(Dossier $dossier): void
    {
        $dossier->loadMissing([
            'chefFiliereSubmittedToAgenceBy.role',
            'instructionAgenceValidatedBy.role',
            'instructionAgenceRejectedBy.role',
            'instructionCaTransmittedToExploitationBy.role',
            'exploitationAvisCreditUser.role',
            'exploitationEngagementsDecisionUser.role',
            'exploitationAnalysteAssignedBy.role',
            'exploitationAnalysteTransmittedToExploitationBy.role',
            'juridiqueInstructionSubmittedBy.role',
            'juridiqueAnalysteAssignedBy.role',
            'juridiqueAnalysteSubmittedToRejuBy.role',
            'juridiqueSubmittedToEngagementsBy.role',
            'rengAnalysteCreditAssignedBy.role',
            'rengAnalysteCreditSubmittedBy.role',
            'rengSubmittedToRisquesBy.role',
            'rerxAnalysteRisquesAssignedBy.role',
            'rerxAnalysteRisquesSubmittedBy.role',
            'rerxSubmittedToDirectionBy.role',
        ]);
    }

    /**
     * Avis et contenus significatifs liés au dossier d’instruction, du plus ancien au plus récent.
     *
     * @return Collection<int, array{at: Carbon, label: string, body_html: ?string, author_name: string, author_profile: string, origin: string}>
     */
    public function buildOrderedEntries(Dossier $dossier): Collection
    {
        $this->loadDossierRelationsForTimeline($dossier);

        $entries = collect();

        foreach ($dossier->instructionWorkflowHistoryTimeline() as $row) {
            if (! $this->timelineRowHasSubstance($row)) {
                continue;
            }
            $at = $this->normalizeEntryAt($row['at'] ?? null);
            if ($at === null) {
                continue;
            }
            $actor = $row['actor'] ?? null;
            if ($actor instanceof User && ! $actor->relationLoaded('role')) {
                $actor->loadMissing('role');
            }
            $entries->push([
                'at' => $at,
                'micro' => (int) ($row['sort'] ?? 0),
                'label' => $row['label'],
                'body_html' => $row['body_html'],
                'author_name' => $actor instanceof User ? ($actor->name ?? '—') : '—',
                'author_profile' => $actor instanceof User ? $this->roleLabel($actor) : $this->profileFallbackFromTimelineLabel((string) $row['label']),
                'origin' => 'instruction',
            ]);
        }

        $critiqueAvis = AnalyseCritiqueAvis::query()
            ->where('instruction_dossier_id', $dossier->id)
            ->whereIn('etat', [
                AnalyseCritiqueAvis::ETAT_INTEGRE,
                AnalyseCritiqueAvis::ETAT_EMIS,
            ])
            ->with(['emisPar.role'])
            ->orderBy('emis_at')
            ->orderBy('id')
            ->get();

        foreach ($critiqueAvis as $avis) {
            $raw = (string) ($avis->contenu ?? '');
            if (strlen(trim(strip_tags($raw))) === 0) {
                continue;
            }
            $at = $this->normalizeEntryAt($avis->emis_at ?? $avis->created_at);
            if ($at === null) {
                continue;
            }
            $u = $avis->emisPar;
            $entries->push([
                'at' => $at,
                'micro' => (int) $avis->sort_order,
                'label' => $avis->source_label ?: $this->sourceTypeLabel($avis->source_type),
                'body_html' => $avis->contenu,
                'author_name' => $u ? ($u->name ?? '—') : '—',
                'author_profile' => $u ? $this->roleLabel($u) : $this->sourceTypeLabel($avis->source_type),
                'origin' => 'analyse_critique',
            ]);
        }

        return $entries
            ->sortBy(function (array $e) {
                $t = $e['at'] instanceof Carbon ? $e['at']->timestamp : 0;

                return ($t * 1000) + ($e['micro'] % 1000);
            })
            ->values()
            ->map(function (array $e) {
                unset($e['micro']);

                return $e;
            });
    }

    /**
     * Toujours un Carbon pour l’affichage (les colonnes *_at peuvent être des chaînes selon les casts / le pilote).
     */
    private function normalizeEntryAt(mixed $at): ?Carbon
    {
        if ($at === null || $at === '') {
            return null;
        }
        if ($at instanceof Carbon) {
            return $at;
        }
        try {
            return Carbon::parse($at);
        } catch (\Throwable) {
            return null;
        }
    }

    private function timelineRowHasSubstance(array $row): bool
    {
        $body = (string) ($row['body_html'] ?? '');

        return strlen(trim(strip_tags($body))) > 0;
    }

    private function roleLabel(User $user): string
    {
        return $user->role?->name ?? 'Profil non renseigné';
    }

    private function profileFallbackFromTimelineLabel(string $label): string
    {
        return 'Événement lié au dossier';
    }

    private function sourceTypeLabel(string $sourceType): string
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
            default => $sourceType ?: 'Autre',
        };
    }
}
