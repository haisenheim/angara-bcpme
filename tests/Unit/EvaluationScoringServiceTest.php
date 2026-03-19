<?php

namespace Tests\Unit;

use App\Models\DossierEsgEvaluation;
use App\Models\DossierEsgEvaluationItem;
use App\Services\EvaluationScoringService;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EvaluationScoringServiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function rebuild_scores_calcule_la_moyenne_ponderee_des_items_par_categorie(): void
    {
        if (!Schema::hasTable('dossier_esg_evaluations')) {
            $this->markTestSkipped('Table dossier_esg_evaluations non disponible (migrations ESG).');
        }

        $evaluation = DossierEsgEvaluation::create([
            'dossier_id' => 999991,
            'entreprise_id' => 1,
            'score_environmental' => 0,
            'score_social' => 0,
            'score_governance' => 0,
        ]);

        DossierEsgEvaluationItem::create([
            'dossier_esg_evaluation_id' => $evaluation->id,
            'category_code' => 'environmental',
            'indicator_code' => 'e1',
            'score' => 100,
            'weight' => 2,
        ]);
        DossierEsgEvaluationItem::create([
            'dossier_esg_evaluation_id' => $evaluation->id,
            'category_code' => 'environmental',
            'indicator_code' => 'e2',
            'score' => 50,
            'weight' => 2,
        ]);

        $service = new EvaluationScoringService();
        $result = $service->rebuildScores($evaluation);

        $this->assertEquals(75, (float) $result->score_environmental);
    }
}
