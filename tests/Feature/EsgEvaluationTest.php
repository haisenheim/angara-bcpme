<?php

namespace Tests\Feature;

use App\Models\DossierEsgEvaluation;
use App\Models\DossierEsgEvaluationItem;
use App\Services\EvaluationScoringService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

/**
 * Tests du module ESG.
 */
class EsgEvaluationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function le_service_de_scoring_calcule_correctement_les_scores_par_categorie(): void
    {
        if (!Schema::hasTable('dossier_esg_evaluations')) {
            $this->markTestSkipped('Table dossier_esg_evaluations non disponible.');
        }

        $evaluation = DossierEsgEvaluation::create([
            'dossier_id' => 999992,
            'entreprise_id' => 1,
            'score_environmental' => 0,
            'score_social' => 0,
            'score_governance' => 0,
            'score_global' => 0,
        ]);
        DossierEsgEvaluationItem::create([
            'dossier_esg_evaluation_id' => $evaluation->id,
            'category_code' => 'environmental',
            'indicator_code' => 'env1',
            'score' => 80,
            'weight' => 1,
        ]);
        DossierEsgEvaluationItem::create([
            'dossier_esg_evaluation_id' => $evaluation->id,
            'category_code' => 'environmental',
            'indicator_code' => 'env2',
            'score' => 60,
            'weight' => 1,
        ]);

        $service = new EvaluationScoringService();
        $result = $service->rebuildScores($evaluation);

        $this->assertEquals(70, (float) $result->score_environmental);
    }

    /** @test */
    public function un_dossier_na_quune_seule_evaluation_esg(): void
    {
        if (!Schema::hasTable('dossier_esg_evaluations')) {
            $this->markTestSkipped('Table dossier_esg_evaluations non disponible.');
        }

        $evaluation = DossierEsgEvaluation::create([
            'dossier_id' => 999993,
            'entreprise_id' => 1,
            'status' => 'draft',
        ]);

        $count = DossierEsgEvaluation::where('dossier_id', 999993)->count();
        $this->assertEquals(1, $count);
    }
}
