<?php

namespace App\Http\Controllers\RoleSpace;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\DossierEntreeRelation;
use App\Models\Entreprise;
use App\Models\EntreprisePieceExigible;
use App\Services\DossierInstructionStatutService;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    use ResolvesRoleSpace;

    public function index()
    {
        return view('RoleSpace.dashboard', [
            'space' => $this->resolveSpace(),
        ]);
    }

    public function stats()
    {
        $space = $this->resolveSpace();

        $stats = [
            'entreprises' => Entreprise::query()->count(),
            'dossiers' => Dossier::query()->count(),
            'pieces' => EntreprisePieceExigible::query()->whereNotNull('fichier_id')->count(),
        ];

        if ($space['route'] === 'respexp') {
            $base = Dossier::query()->whereNull('juridique_instruction_submitted_at');

            $stats['dossiers_a_affecter'] = (clone $base)->whereNull('analyste_id')->count();
            $stats['dossiers_en_instruction'] = (clone $base)
                ->whereNotNull('analyste_id')
                ->whereNull('exploitation_analyste_transmitted_to_exploitation_at')
                ->count();
            $stats['dossiers_a_valider'] = (clone $base)
                ->whereNotNull('exploitation_analyste_transmitted_to_exploitation_at')
                ->whereNull('exploitation_avis_credit_at')
                ->count();
            $stats['avis_credit_a_saisir'] = (clone $base)
                ->whereNotNull('exploitation_analyste_transmitted_to_exploitation_at')
                ->whereNull('exploitation_avis_credit_at')
                ->count();
            $stats['validation_engagements_a_faire'] = (clone $base)
                ->whereNotNull('exploitation_avis_credit_at')
                ->whereNull('exploitation_engagements_decision_at')
                ->count();
            $stats['a_soumettre_juridique'] = (clone $base)
                ->whereNotNull('exploitation_avis_credit_at')
                ->whereNotNull('exploitation_engagements_decision_at')
                ->where('exploitation_engagements_decision', 'accord')
                ->whereNull('juridique_instruction_submitted_at')
                ->count();
        } elseif ($space['route'] === 'reng') {
            $base = Dossier::query()
                ->whereNotNull('juridique_submitted_to_engagements_at')
                ->whereNull('reng_submitted_to_risques_at');

            $stats['a_affecter_analyste_credit'] = (clone $base)->whereNull('reng_analyste_credit_assigned_at')->count();
            $stats['analyste_credit_en_cours'] = (clone $base)
                ->whereNotNull('reng_analyste_credit_assigned_at')
                ->whereNull('reng_analyste_credit_submitted_at')
                ->count();
            $stats['a_valider_avis_engagements'] = (clone $base)
                ->whereNotNull('reng_analyste_credit_submitted_at')
                ->whereNull('reng_responsable_avis_at')
                ->count();
            $stats['a_soumettre_risques'] = (clone $base)
                ->whereNotNull('reng_responsable_avis_at')
                ->whereNull('reng_submitted_to_risques_at')
                ->count();
        } elseif ($space['route'] === 'rerx') {
            $base = Dossier::query()
                ->whereNotNull('reng_submitted_to_risques_at')
                ->whereNull('rerx_submitted_to_direction_at');

            $stats['a_affecter_analyste_risques'] = (clone $base)->whereNull('rerx_analyste_risques_assigned_at')->count();
            $stats['analyste_risques_en_cours'] = (clone $base)
                ->whereNotNull('rerx_analyste_risques_assigned_at')
                ->whereNull('rerx_analyste_risques_submitted_at')
                ->count();
            $stats['a_valider_avis_risques'] = (clone $base)
                ->whereNotNull('rerx_analyste_risques_submitted_at')
                ->whereNull('rerx_responsable_avis_at')
                ->count();
            $stats['a_soumettre_direction'] = (clone $base)
                ->whereNotNull('rerx_responsable_avis_at')
                ->whereNull('rerx_submitted_to_direction_at')
                ->count();
        } elseif (in_array($space['route'], ['dg', 'dga'], true)) {
            $baseValides = Dossier::query()->instructionValidesParChefAgence();
            $stats['prospects_soumis'] = Entreprise::query()->submittedProspect()->count();
            $stats['clients'] = Entreprise::query()->where('prospect', false)->count();
            $stats['dossiers_valides_agence'] = (clone $baseValides)->count();
            $stats['dossiers_attente_direction'] = Dossier::query()
                ->instructionValidesParChefAgence()
                ->awaitingDirectionGeneralConclusion()
                ->count();
            $stats['dossiers_instruction_en_cours'] = (clone $baseValides)
                ->whereInstructionStatut(DossierInstructionStatutService::CODE_EN_COURS)
                ->count();
            $stats['dossiers_instruction_clos'] = (clone $baseValides)
                ->whereInstructionStatut(DossierInstructionStatutService::CODE_VALIDE)
                ->count();
            $sommeSol = (float) (clone $baseValides)->sum('engagements_sollicites_total');
            $sommeEnc = (float) (clone $baseValides)->sum('engagements_en_cours_total');
            $stats['somme_engagements_sollicites_valides_agence'] = number_format($sommeSol, 0, ',', ' ').' XAF';
            $stats['somme_engagements_en_cours_valides_agence'] = number_format($sommeEnc, 0, ',', ' ').' XAF';
        }

        return response()->json($stats);
    }

    public function todos()
    {
        $space = $this->resolveSpace();

        $query = Dossier::query();
        $orderCol = null;
        $kindFor = null;

        if ($space['route'] === 'respexp') {
            // Priorité: dossiers transmis (attente avis crédit) puis ceux sans analyste.
            $query->whereNull('juridique_instruction_submitted_at')
                ->where(function ($q) {
                    $q->where(function ($inner) {
                        $inner->whereNotNull('exploitation_analyste_transmitted_to_exploitation_at')
                            ->whereNull('exploitation_avis_credit_at');
                    })->orWhere(function ($inner) {
                        $inner->whereNull('analyste_id');
                    });
                });
            $orderCol = 'exploitation_analyste_transmitted_to_exploitation_at';
            $kindFor = function (Dossier $d): array {
                if ($d->analyste_id === null) {
                    return ['label' => 'À affecter', 'variant' => 'warning'];
                }
                if ($d->exploitation_analyste_transmitted_to_exploitation_at !== null && $d->exploitation_avis_credit_at === null) {
                    return ['label' => 'Avis crédit', 'variant' => 'primary'];
                }
                if ($d->exploitation_avis_credit_at !== null && $d->exploitation_engagements_decision_at === null) {
                    return ['label' => 'Valider engagements', 'variant' => 'info'];
                }

                return ['label' => 'À traiter', 'variant' => 'secondary'];
            };
        } elseif ($space['route'] === 'reng') {
            $query->whereNotNull('juridique_submitted_to_engagements_at')
                ->whereNull('reng_submitted_to_risques_at')
                ->where(function ($q) {
                    $q->whereNull('reng_analyste_credit_assigned_at')
                        ->orWhere(function ($inner) {
                            $inner->whereNotNull('reng_analyste_credit_submitted_at')
                                ->whereNull('reng_responsable_avis_at');
                        });
                });
            $orderCol = 'juridique_submitted_to_engagements_at';
            $kindFor = function (Dossier $d): array {
                if ($d->reng_analyste_credit_assigned_at === null) {
                    return ['label' => 'À affecter', 'variant' => 'warning'];
                }
                if ($d->reng_analyste_credit_submitted_at !== null && $d->reng_responsable_avis_at === null) {
                    return ['label' => 'À valider avis', 'variant' => 'primary'];
                }
                if ($d->reng_responsable_avis_at !== null && $d->reng_submitted_to_risques_at === null) {
                    return ['label' => 'À transmettre', 'variant' => 'info'];
                }

                return ['label' => 'À traiter', 'variant' => 'secondary'];
            };
        } elseif ($space['route'] === 'rerx') {
            $query->whereNotNull('reng_submitted_to_risques_at')
                ->whereNull('rerx_submitted_to_direction_at')
                ->where(function ($q) {
                    $q->whereNull('rerx_analyste_risques_assigned_at')
                        ->orWhere(function ($inner) {
                            $inner->whereNotNull('rerx_analyste_risques_submitted_at')
                                ->whereNull('rerx_responsable_avis_at');
                        });
                });
            $orderCol = 'reng_submitted_to_risques_at';
            $kindFor = function (Dossier $d): array {
                if ($d->rerx_analyste_risques_assigned_at === null) {
                    return ['label' => 'À affecter', 'variant' => 'warning'];
                }
                if ($d->rerx_analyste_risques_submitted_at !== null && $d->rerx_responsable_avis_at === null) {
                    return ['label' => 'À valider avis', 'variant' => 'primary'];
                }
                if ($d->rerx_responsable_avis_at !== null && $d->rerx_submitted_to_direction_at === null) {
                    return ['label' => 'À transmettre', 'variant' => 'info'];
                }

                return ['label' => 'À traiter', 'variant' => 'secondary'];
            };
        } else {
            return response()->json(['todos' => []]);
        }

        $rows = $query
            ->with(['entreprise', 'programme', 'instructionProgrammes.programme'])
            ->orderBy($orderCol ?? 'updated_at', 'asc')
            ->orderBy('updated_at', 'asc')
            ->limit(10)
            ->get()
            ->map(function (Dossier $d) use ($space, $kindFor) {
                $at = match ($space['route']) {
                    'respexp' => $d->exploitation_analyste_transmitted_to_exploitation_at,
                    'reng' => $d->juridique_submitted_to_engagements_at,
                    'rerx' => $d->reng_submitted_to_risques_at,
                    default => null,
                };
                $dt = $at instanceof Carbon ? $at : null;
                $kind = null;
                if (is_callable($kindFor)) {
                    $kind = $kindFor($d);
                }

                return [
                    'token' => $d->token,
                    'entreprise' => $d->entreprise?->name ?? '—',
                    'programmes' => method_exists($d, 'programmesLabel') ? $d->programmesLabel() : ($d->programme?->name ?? '—'),
                    'when_human' => $dt?->diffForHumans(),
                    'kind' => $kind,
                ];
            })
            ->values();

        return response()->json(['todos' => $rows]);
    }

    /**
     * Données agrégées pour le tableau de bord direction (DG / DGA) : graphiques et listes.
     */
    public function insights()
    {
        $space = $this->resolveSpace();

        if (! in_array($space['route'], ['dg', 'dga'], true)) {
            return response()->json([]);
        }

        $struct = [
            'structure' => Entreprise::query()
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_STRUCTURE)
                ->count(),
            'en_cours' => Entreprise::query()
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_EN_COURS)
                ->count(),
            'attente' => Entreprise::query()
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_ATTENTE)
                ->count(),
            'rejetee' => Entreprise::query()
                ->whereNotNull('promu_client_at')
                ->whereClientStructurationStatus(DossierEntreeRelation::CLIENT_STRUCT_STATUS_REJETEE)
                ->count(),
        ];

        $base = Dossier::query()->instructionValidesParChefAgence();

        $pipelineOpen = function (\Illuminate\Contracts\Database\Query\Builder $q): void {
            $q->whereNull('instruction_closure_validated_at')
                ->whereNull('instruction_closure_rejected_at');
        };

        $pipeline = [
            'exploitation' => (clone $base)->where($pipelineOpen)->whereNull('juridique_instruction_submitted_at')->count(),
            'juridique' => (clone $base)->where($pipelineOpen)
                ->whereNotNull('juridique_instruction_submitted_at')
                ->whereNull('juridique_submitted_to_engagements_at')
                ->count(),
            'engagements' => (clone $base)->where($pipelineOpen)
                ->whereNotNull('juridique_submitted_to_engagements_at')
                ->whereNull('reng_submitted_to_risques_at')
                ->count(),
            'risques' => (clone $base)->where($pipelineOpen)
                ->whereNotNull('reng_submitted_to_risques_at')
                ->whereNull('rerx_submitted_to_direction_at')
                ->count(),
            'direction' => (clone $base)->where($pipelineOpen)
                ->whereNotNull('rerx_submitted_to_direction_at')
                ->count(),
            'clos' => (clone $base)->whereNotNull('instruction_closure_validated_at')->count(),
        ];

        $trends = [];
        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = $start->copy()->endOfMonth();
            $trends[] = [
                'label' => $start->translatedFormat('M Y'),
                'count' => Dossier::query()
                    ->instructionValidesParChefAgence()
                    ->whereBetween('created_at', [$start, $end])
                    ->count(),
            ];
        }

        $portefeuille = [
            'prospects_soumis' => Entreprise::query()->submittedProspect()->count(),
            'clients' => Entreprise::query()->where('prospect', false)->count(),
        ];

        $attenteDirection = Dossier::query()
            ->instructionValidesParChefAgence()
            ->awaitingDirectionGeneralConclusion()
            ->with(['entreprise', 'instructionProgrammes.programme', 'programme'])
            ->orderBy('rerx_submitted_to_direction_at', 'asc')
            ->limit(8)
            ->get()
            ->map(function (Dossier $d) {
                return [
                    'token' => $d->token,
                    'entreprise' => $d->entreprise?->name ?? '—',
                    'programmes' => method_exists($d, 'programmesLabel') ? $d->programmesLabel() : ($d->programme?->name ?? '—'),
                    'since' => $d->rerx_submitted_to_direction_at instanceof Carbon
                        ? $d->rerx_submitted_to_direction_at->diffForHumans()
                        : null,
                ];
            })
            ->values();

        return response()->json([
            'structuration' => $struct,
            'pipeline' => $pipeline,
            'trends' => $trends,
            'portefeuille' => $portefeuille,
            'attente_direction' => $attenteDirection,
        ]);
    }
}
