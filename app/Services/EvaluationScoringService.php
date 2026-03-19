<?php

namespace App\Services;

use App\Models\DossierEsgEvaluation;
use App\Models\DossierEsgEvaluationItem;
use App\Models\EvaluationCategory;
use App\Models\EvaluationScoreThreshold;
use App\Models\EvaluationSetting;

class EvaluationScoringService
{
    /**
     * Recalcule les sous-scores par catégorie et le score global.
     * Si des items existent : calcule à partir des items.
     * Sinon : conserve les scores manuels (E, S, G, Financier, Conformité) et recalcule uniquement le global et les dérivés.
     */
    public function rebuildScores(DossierEsgEvaluation $evaluation): DossierEsgEvaluation
    {
        $items = $evaluation->items;
        $hasItems = $items->isNotEmpty();

        if ($hasItems) {
            $categoryScores = [];
            $categoryWeights = [];

            foreach ($items->groupBy('category_code') as $categoryCode => $categoryItems) {
                $weightedSum = 0;
                $totalWeight = 0;

                foreach ($categoryItems as $item) {
                    $weight = (float) ($item->weight ?: 1);
                    $score = (float) ($item->score ?? 0);
                    $weightedSum += $score * $weight;
                    $totalWeight += $weight;
                }

                $categoryScore = $totalWeight > 0 ? $weightedSum / $totalWeight : 0;
                $categoryScores[$categoryCode] = round($categoryScore, 2);

                $category = EvaluationCategory::where('code', $categoryCode)->first();
                $categoryWeights[$categoryCode] = $category ? (float) $category->weight : 1;
            }

            $scoreEnvironmental = $categoryScores['environmental'] ?? $categoryScores['env'] ?? 0;
            $scoreSocial = $categoryScores['social'] ?? $categoryScores['soc'] ?? 0;
            $scoreGovernance = $categoryScores['governance'] ?? $categoryScores['gov'] ?? 0;
            $scoreFinancial = $categoryScores['financial'] ?? $categoryScores['fin'] ?? 0;
            $scoreCompliance = $categoryScores['compliance'] ?? $categoryScores['comp'] ?? 0;

            $totalWeight = array_sum($categoryWeights);
            $globalWeightedSum = 0;
            foreach ($categoryScores as $code => $score) {
                $w = $categoryWeights[$code] ?? 1;
                $globalWeightedSum += $score * $w;
            }
            $scoreGlobal = $totalWeight > 0 ? round($globalWeightedSum / $totalWeight, 2) : 0;
        } else {
            // Pas d'items : utiliser les scores manuels saisis par l'analyste
            $scoreEnvironmental = (float) ($evaluation->score_environmental ?? 0);
            $scoreSocial = (float) ($evaluation->score_social ?? 0);
            $scoreGovernance = (float) ($evaluation->score_governance ?? 0);
            $scoreFinancial = (float) ($evaluation->score_financial ?? 0);
            $scoreCompliance = (float) ($evaluation->score_compliance ?? 0);

            // Score global = moyenne pondérée des 5 piliers (pondérations par défaut : 1 chacun)
            $categoryWeights = $this->getDefaultPillarWeights();
            $totalWeight = $categoryWeights['environmental'] + $categoryWeights['social'] + $categoryWeights['governance']
                + $categoryWeights['financial'] + $categoryWeights['compliance'];
            $scoreGlobal = $totalWeight > 0
                ? round(($scoreEnvironmental * $categoryWeights['environmental'] + $scoreSocial * $categoryWeights['social']
                    + $scoreGovernance * $categoryWeights['governance'] + $scoreFinancial * $categoryWeights['financial']
                    + $scoreCompliance * $categoryWeights['compliance']) / $totalWeight, 2)
                : 0;
        }

        $evaluation->update([
            'score_environmental' => $scoreEnvironmental,
            'score_social' => $scoreSocial,
            'score_governance' => $scoreGovernance,
            'score_financial' => $scoreFinancial,
            'score_compliance' => $scoreCompliance,
            'score_global' => $scoreGlobal,
            'risk_level' => $this->determineRiskLevel($scoreGlobal),
            'bankability_level' => $this->determineBankabilityLevel($scoreGlobal),
            'eligibility_blending' => $this->determineEligibilityBlending($evaluation, $scoreGlobal),
            'eligibility_guarantee' => $this->determineEligibilityGuarantee($evaluation, $scoreGlobal),
            'eligibility_global_gateway' => $this->determineEligibilityGlobalGateway($evaluation, $scoreGlobal),
            'exclusion_flag' => $this->determineExclusionFlag($evaluation, $scoreGlobal),
            'minimum_compliance_passed' => $this->determineMinimumCompliance($evaluation, $scoreGlobal),
        ]);

        return $evaluation->fresh();
    }

    /**
     * Détermine le niveau de risque à partir du score global.
     */
    public function determineRiskLevel(float $scoreGlobal): ?string
    {
        $threshold = EvaluationScoreThreshold::where('threshold_type', 'risk')
            ->where('is_active', true)
            ->where('min_value', '<=', $scoreGlobal)
            ->where('max_value', '>=', $scoreGlobal)
            ->orderBy('min_value', 'desc')
            ->first();

        return $threshold?->label;
    }

    /**
     * Détermine le niveau de bancabilité.
     */
    public function determineBankabilityLevel(float $scoreGlobal): ?string
    {
        $threshold = EvaluationScoreThreshold::where('threshold_type', 'bankability')
            ->where('is_active', true)
            ->where('min_value', '<=', $scoreGlobal)
            ->where('max_value', '>=', $scoreGlobal)
            ->orderBy('min_value', 'desc')
            ->first();

        return $threshold?->label;
    }

    /**
     * Éligibilité blending (instruments européens).
     */
    public function determineEligibilityBlending(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        $minScore = (float) EvaluationSetting::getValue('eligibility_blending_min_score', 60);
        return $scoreGlobal >= $minScore && $this->passesMinimumCompliance($evaluation, $scoreGlobal);
    }

    /**
     * Éligibilité garantie.
     */
    public function determineEligibilityGuarantee(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        $minScore = (float) EvaluationSetting::getValue('eligibility_guarantee_min_score', 50);
        return $scoreGlobal >= $minScore && $this->passesMinimumCompliance($evaluation, $scoreGlobal);
    }

    /**
     * Éligibilité Global Gateway.
     */
    public function determineEligibilityGlobalGateway(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        $minScore = (float) EvaluationSetting::getValue('eligibility_global_gateway_min_score', 55);
        return $scoreGlobal >= $minScore && $this->passesMinimumCompliance($evaluation, $scoreGlobal);
    }

    /**
     * Flag d'exclusion (score trop bas ou non-conformité).
     */
    public function determineExclusionFlag(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        $minScore = (float) EvaluationSetting::getValue('exclusion_min_score', 30);
        return $scoreGlobal < $minScore || !$this->passesMinimumCompliance($evaluation, $scoreGlobal);
    }

    /**
     * Conformité minimale passée.
     */
    public function determineMinimumCompliance(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        return $this->passesMinimumCompliance($evaluation, $scoreGlobal);
    }

    protected function passesMinimumCompliance(DossierEsgEvaluation $evaluation, float $scoreGlobal): bool
    {
        $minScore = (float) EvaluationSetting::getValue('minimum_compliance_score', 40);
        $minEnv = (float) EvaluationSetting::getValue('minimum_environmental_score', 30);
        $minSoc = (float) EvaluationSetting::getValue('minimum_social_score', 30);
        $minGov = (float) EvaluationSetting::getValue('minimum_governance_score', 30);

        return $scoreGlobal >= $minScore
            && $evaluation->score_environmental >= $minEnv
            && $evaluation->score_social >= $minSoc
            && $evaluation->score_governance >= $minGov;
    }

    /**
     * Pondérations par défaut des 5 piliers (scores manuels sans items).
     */
    protected function getDefaultPillarWeights(): array
    {
        $defaults = [
            'environmental' => 1.0,
            'social' => 1.0,
            'governance' => 1.0,
            'financial' => 1.0,
            'compliance' => 1.0,
        ];
        $categories = EvaluationCategory::all();
        foreach ($categories as $cat) {
            $code = strtolower($cat->code);
            if (isset($defaults[$code])) {
                $defaults[$code] = (float) ($cat->weight ?? 1);
            }
        }
        return $defaults;
    }
}
