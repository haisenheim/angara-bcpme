<?php

namespace App\Services;

use App\Models\Dossier;
use App\Models\DossierInstructionProgramme;
use App\Models\Entreprise;
use App\Models\Programme;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Indicateurs centralisés pour les 5 familles de tableaux de bord
 * définies dans `prompt.txt` (l. 86-105) :
 *  1. Opérationnels   — activité quotidienne (AF, CF, intervenants).
 *  2. Portefeuille    — engagements & clients (CA, REXP, RENG, RISQ).
 *  3. Risques         — exposition & alertes (RISQ, DG, conseil).
 *  4. Stratégiques    — pilotage banque (DG, conseil).
 *  5. Programmes      — suivi des dispositifs d'appui.
 *
 * Ce service ne fait QUE l'agrégation. Les contrôleurs en charge des
 * dashboards consomment les méthodes pertinentes pour leur profil.
 */
class TableauDeBordService
{
    /**
     * Famille 1 — Opérationnels.
     * Périmètre dossier d'instruction (workflow 5 pôles).
     *
     * @param  array<string, mixed>  $scope  Filtres optionnels (agence_id, gestionnaire_id, analyste_id, …)
     * @return array<string, mixed>
     */
    public function operationnel(array $scope = []): array
    {
        $base = $this->scopeDossier($scope);

        $enCours = (clone $base)->whereNotNull('instruction_agence_validated_at')
            ->whereNull('instruction_closure_validated_at')
            ->whereNull('instruction_closure_rejected_at')
            ->count();

        $enAttenteValidationCa = (clone $base)
            ->whereNotNull('chef_filiere_submitted_to_agence_at')
            ->whereNull('instruction_agence_validated_at')
            ->whereNull('instruction_agence_rejected_at')
            ->count();

        $rejetes = (clone $base)
            ->where(function (Builder $q) {
                $q->whereNotNull('instruction_agence_rejected_at')
                    ->orWhereNotNull('instruction_closure_rejected_at');
            })
            ->count();

        return [
            'dossiers_en_cours' => $enCours,
            'dossiers_en_attente_validation' => $enAttenteValidationCa,
            'dossiers_rejetes' => $rejetes,
            'delai_moyen_traitement_jours' => $this->delaiMoyenTraitement($base),
        ];
    }

    /**
     * Famille 2 — Portefeuille.
     * Volume des engagements, encours, répartitions.
     *
     * @param  array<string, mixed>  $scope
     * @return array<string, mixed>
     */
    public function portefeuille(array $scope = []): array
    {
        $base = $this->scopeDossier($scope);

        $base = $base->whereNotNull('instruction_agence_validated_at');

        $volumeSollicites = (clone $base)->sum('engagements_sollicites_total');
        $encoursTotal = (clone $base)->sum('engagements_en_cours_total');
        $nbDossiers = (clone $base)->count();
        $nbClients = (clone $base)->distinct('entreprise_id')->count('entreprise_id');

        return [
            'volume_credits_sollicites' => (float) $volumeSollicites,
            'encours_total' => (float) $encoursTotal,
            'nb_dossiers' => $nbDossiers,
            'nb_clients' => $nbClients,
            'repartition_par_filiere' => $this->repartitionParFiliere($base),
            'repartition_par_secteur' => $this->repartitionParSecteur($scope),
            'top_clients_par_encours' => $this->topClientsParEncours($base, 10),
        ];
    }

    /**
     * Famille 3 — Risques.
     * Données dérivables de l'instruction : dossiers à risque (rejetés en cours de circuit), alertes.
     * Note : impayés / créances en souffrance ne sont pas traçables avec le schéma actuel
     *        (pas de table de remboursement). Ces métriques restent à null tant que la
     *        comptabilisation des échéances n'est pas modélisée.
     *
     * @param  array<string, mixed>  $scope
     * @return array<string, mixed>
     */
    public function risques(array $scope = []): array
    {
        $base = $this->scopeDossier($scope);

        $dossiersRejetesEnInstruction = (clone $base)
            ->where(function (Builder $q) {
                $q->whereNotNull('exploitation_analyste_rejected_at')
                    ->orWhereNotNull('juridique_analyste_rejected_at')
                    ->orWhereNotNull('reng_analyste_credit_rejected_at')
                    ->orWhereNotNull('rerx_analyste_risques_rejected_at')
                    ->orWhereNotNull('juridique_rejected_to_exploitation_at')
                    ->orWhereNotNull('engagements_rejected_to_juridique_at')
                    ->orWhereNotNull('risques_rejected_to_engagements_at')
                    ->orWhereNotNull('direction_rejected_to_risques_at');
            })
            ->count();

        $rejetClotureFinal = (clone $base)->whereNotNull('instruction_closure_rejected_at')->count();

        return [
            'dossiers_a_risque_en_instruction' => $dossiersRejetesEnInstruction,
            'rejets_clotures_finaux' => $rejetClotureFinal,
            'alertes_seuil' => $this->alertesSeuil($base),
            'taux_impayes' => null,
            'creances_en_souffrance' => null,
        ];
    }

    /**
     * Famille 4 — Stratégiques.
     *
     * @return array<string, mixed>
     */
    public function strategique(array $scope = []): array
    {
        $base = $this->scopeDossier($scope);

        $ouverts = (clone $base)
            ->whereNotNull('instruction_agence_validated_at')
            ->where('instruction_agence_validated_at', '>=', now()->subYear())
            ->count();

        $clos = (clone $base)
            ->whereNotNull('instruction_closure_validated_at')
            ->where('instruction_closure_validated_at', '>=', now()->subYear())
            ->count();

        return [
            'performance_globale' => [
                'dossiers_ouverts_12_mois' => $ouverts,
                'dossiers_clotures_12_mois' => $clos,
                'taux_cloture' => $ouverts > 0 ? round(($clos / $ouverts) * 100, 1) : null,
            ],
            'croissance_portefeuille' => $this->croissancePortefeuille($base),
            'impact_programmes' => $this->impactProgrammes($scope),
        ];
    }

    /**
     * Famille 5 — Programmes.
     *
     * @param  array<string, mixed>  $scope
     * @return array<string, mixed>
     */
    public function programmes(array $scope = []): array
    {
        $programmes = Programme::query()
            ->orderBy('name')
            ->get(['id', 'name'])
            ->map(function (Programme $p) use ($scope) {
                $linkQuery = DossierInstructionProgramme::query()
                    ->where('dossier_instruction_programmes.programme_id', $p->id)
                    ->whereHas('dossier', function (Builder $q) use ($scope) {
                        $q->whereNotNull('instruction_agence_validated_at');
                        $this->applyDossierScope($q, $scope);
                    });

                $beneficiaires = (clone $linkQuery)
                    ->join('dossiers', 'dossiers.id', '=', 'dossier_instruction_programmes.dossier_id')
                    ->distinct()
                    ->count('dossiers.entreprise_id');

                $montantFinancier = (clone $linkQuery)->sum('budget_appui_financier');
                $montantNonFinancier = (clone $linkQuery)->sum('budget_appui_non_financier');

                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'nb_beneficiaires' => $beneficiaires,
                    'montant_financier' => (float) $montantFinancier,
                    'montant_non_financier' => (float) $montantNonFinancier,
                ];
            })
            ->values();

        return [
            'programmes' => $programmes->all(),
            'totaux' => [
                'nb_programmes' => $programmes->count(),
                'total_montant_financier' => (float) $programmes->sum('montant_financier'),
                'total_montant_non_financier' => (float) $programmes->sum('montant_non_financier'),
            ],
        ];
    }

    private function scopeDossier(array $scope): Builder
    {
        $q = Dossier::query();
        $this->applyDossierScope($q, $scope);

        return $q;
    }

    private function applyDossierScope(Builder $q, array $scope): void
    {
        if (! empty($scope['agence_id'])) {
            $q->where('agence_id', $scope['agence_id']);
        }
        if (! empty($scope['gestionnaire_id'])) {
            $q->where('gestionnaire_id', $scope['gestionnaire_id']);
        }
        if (! empty($scope['analyste_id'])) {
            $q->where('analyste_id', $scope['analyste_id']);
        }
        if (! empty($scope['from'])) {
            $q->where('created_at', '>=', $this->normalizeDate($scope['from']));
        }
        if (! empty($scope['to'])) {
            $q->where('created_at', '<=', $this->normalizeDate($scope['to'])?->endOfDay());
        }
    }

    private function normalizeDate(mixed $value): ?CarbonInterface
    {
        if ($value instanceof CarbonInterface) {
            return $value;
        }

        try {
            return Carbon::parse((string) $value);
        } catch (\Throwable) {
            return null;
        }
    }

    private function delaiMoyenTraitement(Builder $base): ?float
    {
        $rows = (clone $base)
            ->whereNotNull('chef_filiere_submitted_to_agence_at')
            ->whereNotNull('instruction_closure_validated_at')
            ->limit(500)
            ->get(['chef_filiere_submitted_to_agence_at', 'instruction_closure_validated_at']);

        if ($rows->isEmpty()) {
            return null;
        }

        $total = $rows->sum(fn ($r) => $r->chef_filiere_submitted_to_agence_at->diffInDays($r->instruction_closure_validated_at));

        return round($total / $rows->count(), 1);
    }

    private function repartitionParFiliere(Builder $base): array
    {
        return (clone $base)
            ->join('entreprises', 'entreprises.id', '=', 'dossiers.entreprise_id')
            ->leftJoin('filieres', 'filieres.id', '=', 'entreprises.filiere_id')
            ->selectRaw('COALESCE(filieres.name, "Sans filière") as label, COUNT(*) as nb, COALESCE(SUM(dossiers.engagements_sollicites_total), 0) as volume')
            ->groupBy('label')
            ->orderByDesc('volume')
            ->limit(20)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'nb_dossiers' => (int) $row->nb,
                'volume' => (float) $row->volume,
            ])
            ->all();
    }

    private function repartitionParSecteur(array $scope): array
    {
        $q = Entreprise::query();
        if (! empty($scope['agence_id'])) {
            $q->where('agence_id', $scope['agence_id']);
        }
        $q->whereHas('dossiers', function (Builder $qq) use ($scope) {
            $qq->whereNotNull('instruction_agence_validated_at');
            $this->applyDossierScope($qq, $scope);
        });

        return $q
            ->leftJoin('produits', 'produits.id', '=', 'entreprises.produit_id')
            ->selectRaw('COALESCE(produits.name, "Sans secteur") as label, COUNT(DISTINCT entreprises.id) as nb')
            ->groupBy('label')
            ->orderByDesc('nb')
            ->limit(15)
            ->get()
            ->map(fn ($row) => [
                'label' => $row->label,
                'nb_clients' => (int) $row->nb,
            ])
            ->all();
    }

    private function topClientsParEncours(Builder $base, int $limit): array
    {
        return (clone $base)
            ->join('entreprises', 'entreprises.id', '=', 'dossiers.entreprise_id')
            ->selectRaw('entreprises.id, entreprises.name, COALESCE(SUM(dossiers.engagements_en_cours_total), 0) as encours, COUNT(dossiers.id) as nb_dossiers')
            ->groupBy('entreprises.id', 'entreprises.name')
            ->orderByDesc('encours')
            ->limit($limit)
            ->get()
            ->map(fn ($row) => [
                'id' => (int) $row->id,
                'name' => (string) $row->name,
                'encours' => (float) $row->encours,
                'nb_dossiers' => (int) $row->nb_dossiers,
            ])
            ->all();
    }

    private function alertesSeuil(Builder $base): array
    {
        $seuil = (float) config('angara.alerte_engagement_seuil', 100_000_000);
        $nb = (clone $base)
            ->whereNotNull('instruction_agence_validated_at')
            ->where('engagements_sollicites_total', '>=', $seuil)
            ->whereNull('instruction_closure_validated_at')
            ->count();

        return [
            'seuil' => $seuil,
            'dossiers_au_dessus_seuil' => $nb,
        ];
    }

    private function croissancePortefeuille(Builder $base): array
    {
        $months = [];
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = (clone $start)->endOfMonth();
            $months[] = [
                'mois' => $start->format('Y-m'),
                'nb_dossiers' => (clone $base)
                    ->whereBetween('instruction_agence_validated_at', [$start, $end])
                    ->count(),
                'volume' => (float) (clone $base)
                    ->whereBetween('instruction_agence_validated_at', [$start, $end])
                    ->sum('engagements_sollicites_total'),
            ];
        }

        return $months;
    }

    private function impactProgrammes(array $scope): array
    {
        $links = DossierInstructionProgramme::query()
            ->whereHas('dossier', function (Builder $q) use ($scope) {
                $q->whereNotNull('instruction_agence_validated_at');
                $this->applyDossierScope($q, $scope);
            })
            ->with('programme')
            ->get();

        $totalFinancier = (float) $links->sum('budget_appui_financier');
        $totalNonFinancier = (float) $links->sum('budget_appui_non_financier');
        $clientsTouches = $links->pluck('dossier_id')->unique()->count();

        return [
            'total_appui_financier' => $totalFinancier,
            'total_appui_non_financier' => $totalNonFinancier,
            'nb_dossiers_touches' => $clientsTouches,
        ];
    }
}
