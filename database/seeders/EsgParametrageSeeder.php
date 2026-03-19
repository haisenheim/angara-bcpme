<?php

namespace Database\Seeders;

use App\Models\EvaluationCategory;
use App\Models\EvaluationFramework;
use App\Models\EvaluationIndicator;
use App\Models\EvaluationScoreThreshold;
use App\Models\EvaluationSetting;
use Illuminate\Database\Seeder;

class EsgParametrageSeeder extends Seeder
{
    /**
     * Seed the ESG parametrage tables for the Admin space.
     */
    public function run(): void
    {
        $this->seedFrameworks();
        $this->seedCategories();
        $this->seedIndicators();
        $this->seedScoreThresholds();
        $this->seedSettings();
    }

    protected function seedFrameworks(): void
    {
        $frameworks = [
            [
                'code' => 'esg-angara',
                'name' => 'Référentiel ESG ANGARA',
                'description' => 'Référentiel d\'évaluation ESG pour les PME et coopératives',
                'is_active' => true,
                'applies_to' => 'pme,cooperatives',
            ],
            [
                'code' => 'eib-eligibility',
                'name' => 'Critères d\'éligibilité instruments européens',
                'description' => 'Critères pour l\'éligibilité Blending, Guarantee et Global Gateway',
                'is_active' => true,
                'applies_to' => 'eib',
            ],
        ];

        foreach ($frameworks as $data) {
            EvaluationFramework::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }

    protected function seedCategories(): void
    {
        $framework = EvaluationFramework::where('code', 'esg-angara')->first();
        $frameworkId = $framework?->id;

        $categories = [
            ['code' => 'environmental', 'name' => 'Environnement', 'weight' => 1, 'sort_order' => 1],
            ['code' => 'social', 'name' => 'Social', 'weight' => 1, 'sort_order' => 2],
            ['code' => 'governance', 'name' => 'Gouvernance', 'weight' => 1, 'sort_order' => 3],
            ['code' => 'financial', 'name' => 'Financier', 'weight' => 1, 'sort_order' => 4],
            ['code' => 'compliance', 'name' => 'Conformité', 'weight' => 1, 'sort_order' => 5],
        ];

        foreach ($categories as $data) {
            EvaluationCategory::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, ['framework_id' => $frameworkId])
            );
        }
    }

    protected function seedIndicators(): void
    {
        $categories = EvaluationCategory::all()->keyBy('code');

        $indicators = [
            // Environnement
            ['category_code' => 'environmental', 'code' => 'env_policy', 'name' => 'Politique environnementale', 'input_type' => 'boolean', 'sort_order' => 1],
            ['category_code' => 'environmental', 'code' => 'env_certification', 'name' => 'Certification environnementale', 'input_type' => 'boolean', 'sort_order' => 2],
            ['category_code' => 'environmental', 'code' => 'renewable_energy', 'name' => 'Utilisation d\'énergies renouvelables', 'input_type' => 'boolean', 'sort_order' => 3],
            ['category_code' => 'environmental', 'code' => 'carbon_footprint', 'name' => 'Mesure de l\'empreinte carbone', 'input_type' => 'boolean', 'sort_order' => 4],
            // Social
            ['category_code' => 'social', 'code' => 'women_led', 'name' => 'Dirigée par des femmes', 'input_type' => 'boolean', 'sort_order' => 1],
            ['category_code' => 'social', 'code' => 'youth_led', 'name' => 'Dirigée par des jeunes', 'input_type' => 'boolean', 'sort_order' => 2],
            ['category_code' => 'social', 'code' => 'social_protection', 'name' => 'Protection sociale des employés', 'input_type' => 'boolean', 'sort_order' => 3],
            ['category_code' => 'social', 'code' => 'training_program', 'name' => 'Programme de formation', 'input_type' => 'boolean', 'sort_order' => 4],
            // Gouvernance
            ['category_code' => 'governance', 'code' => 'has_board', 'name' => 'Conseil d\'administration', 'input_type' => 'boolean', 'sort_order' => 1],
            ['category_code' => 'governance', 'code' => 'audited_financials', 'name' => 'États financiers audités', 'input_type' => 'boolean', 'sort_order' => 2],
            ['category_code' => 'governance', 'code' => 'anti_corruption', 'name' => 'Politique anti-corruption', 'input_type' => 'boolean', 'sort_order' => 3],
            ['category_code' => 'governance', 'code' => 'esg_policy', 'name' => 'Politique ESG formalisée', 'input_type' => 'boolean', 'sort_order' => 4],
            // Financier
            ['category_code' => 'financial', 'code' => 'financial_statements', 'name' => 'États financiers disponibles', 'input_type' => 'boolean', 'sort_order' => 1],
            ['category_code' => 'financial', 'code' => 'digital_accounting', 'name' => 'Comptabilité digitale', 'input_type' => 'boolean', 'sort_order' => 2],
            // Conformité
            ['category_code' => 'compliance', 'code' => 'kyc_completed', 'name' => 'KYC complété', 'input_type' => 'boolean', 'sort_order' => 1],
            ['category_code' => 'compliance', 'code' => 'aml_check', 'name' => 'Vérification AML', 'input_type' => 'boolean', 'sort_order' => 2],
            ['category_code' => 'compliance', 'code' => 'legal_compliance', 'name' => 'Conformité légale', 'input_type' => 'boolean', 'sort_order' => 3],
        ];

        foreach ($indicators as $data) {
            $categoryCode = $data['category_code'];
            unset($data['category_code']);
            $category = $categories->get($categoryCode);

            EvaluationIndicator::updateOrCreate(
                ['code' => $data['code']],
                array_merge($data, [
                    'category_id' => $category?->id,
                    'default_weight' => 1,
                    'min_score' => 0,
                    'max_score' => 100,
                    'is_active' => true,
                ])
            );
        }
    }

    protected function seedScoreThresholds(): void
    {
        $thresholds = [
            // Niveaux de risque
            ['threshold_type' => 'risk', 'name' => 'Risque faible', 'label' => 'Faible', 'min_value' => 70, 'max_value' => 100, 'color' => '#28a745', 'sort_order' => 1],
            ['threshold_type' => 'risk', 'name' => 'Risque modéré', 'label' => 'Modéré', 'min_value' => 50, 'max_value' => 69.99, 'color' => '#ffc107', 'sort_order' => 2],
            ['threshold_type' => 'risk', 'name' => 'Risque élevé', 'label' => 'Élevé', 'min_value' => 30, 'max_value' => 49.99, 'color' => '#fd7e14', 'sort_order' => 3],
            ['threshold_type' => 'risk', 'name' => 'Risque très élevé', 'label' => 'Très élevé', 'min_value' => 0, 'max_value' => 29.99, 'color' => '#dc3545', 'sort_order' => 4],
            // Niveaux de bancabilité
            ['threshold_type' => 'bankability', 'name' => 'Très bancable', 'label' => 'Très bancable', 'min_value' => 75, 'max_value' => 100, 'color' => '#28a745', 'sort_order' => 1],
            ['threshold_type' => 'bankability', 'name' => 'Bancable', 'label' => 'Bancable', 'min_value' => 55, 'max_value' => 74.99, 'color' => '#20c997', 'sort_order' => 2],
            ['threshold_type' => 'bankability', 'name' => 'Partiellement bancable', 'label' => 'Partiellement bancable', 'min_value' => 40, 'max_value' => 54.99, 'color' => '#ffc107', 'sort_order' => 3],
            ['threshold_type' => 'bankability', 'name' => 'Non bancable', 'label' => 'Non bancable', 'min_value' => 0, 'max_value' => 39.99, 'color' => '#dc3545', 'sort_order' => 4],
        ];

        foreach ($thresholds as $data) {
            EvaluationScoreThreshold::updateOrCreate(
                [
                    'threshold_type' => $data['threshold_type'],
                    'label' => $data['label'],
                ],
                array_merge($data, ['is_active' => true])
            );
        }
    }

    protected function seedSettings(): void
    {
        $settings = [
            ['key' => 'minimum_compliance_score', 'value' => '40', 'type' => 'decimal', 'description' => 'Score global minimum pour la conformité', 'group' => 'conformite'],
            ['key' => 'minimum_environmental_score', 'value' => '30', 'type' => 'decimal', 'description' => 'Score environnemental minimum', 'group' => 'conformite'],
            ['key' => 'minimum_social_score', 'value' => '30', 'type' => 'decimal', 'description' => 'Score social minimum', 'group' => 'conformite'],
            ['key' => 'minimum_governance_score', 'value' => '30', 'type' => 'decimal', 'description' => 'Score gouvernance minimum', 'group' => 'conformite'],
            ['key' => 'exclusion_min_score', 'value' => '30', 'type' => 'decimal', 'description' => 'Score en dessous duquel exclusion automatique', 'group' => 'exclusion'],
            ['key' => 'eligibility_blending_min_score', 'value' => '60', 'type' => 'decimal', 'description' => 'Score minimum pour éligibilité Blending', 'group' => 'eligibility'],
            ['key' => 'eligibility_guarantee_min_score', 'value' => '50', 'type' => 'decimal', 'description' => 'Score minimum pour éligibilité Guarantee', 'group' => 'eligibility'],
            ['key' => 'eligibility_global_gateway_min_score', 'value' => '55', 'type' => 'decimal', 'description' => 'Score minimum pour éligibilité Global Gateway', 'group' => 'eligibility'],
        ];

        foreach ($settings as $data) {
            EvaluationSetting::updateOrCreate(
                ['key' => $data['key']],
                $data
            );
        }
    }
}
