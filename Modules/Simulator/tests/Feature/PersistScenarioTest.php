<?php

namespace Modules\Simulator\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Modules\Simulator\Persistence\Models\Scenario;
use Tests\TestCase;

/**
 * Persist + workflow tests. Skipped if simulator tables are not present
 * (typical in CI sqlite/no-migration setups), aligned with the project's
 * existing pattern (see Tests\Feature\EsgEvaluationTest).
 *
 * @group simulator
 */
class PersistScenarioTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $connection = config('simulator.connection', 'central_app_mysql');
        if (! Schema::connection($connection)->hasTable('simulator_scenarios')) {
            $this->markTestSkipped('Tables simulator non disponibles. Lancer les migrations sur '.$connection.'.');
        }

        config()->set('simulator.allowed_roles', [16]);
        $user = new User;
        $user->id = 1;
        $user->role_id = 16;
        $this->actingAs($user);
    }

    private function basePayload(array $overrides = []): array
    {
        return array_merge([
            'principal' => 5_000_000,
            'annual_rate' => 9.5,
            'term_periods' => 24,
            'periodicity' => 'monthly',
            'amortization_type' => 'constant',
            'currency' => 'XOF',
        ], $overrides);
    }

    public function test_persist_creates_scenario_and_lines(): void
    {
        $response = $this->postJson('/api/simulator/scenarios', $this->basePayload([
            'name' => 'Test scenario constant',
        ]));

        $response->assertCreated();
        $token = $response->json('token');
        $this->assertNotEmpty($token);

        $scenario = Scenario::where('token', $token)->with('lines')->first();
        $this->assertNotNull($scenario);
        $this->assertSame('draft', $scenario->status);
        $this->assertFalse($scenario->is_locked);
        $this->assertCount(24, $scenario->lines);

        $scenario->forceDelete();
    }

    public function test_persist_rejects_when_both_attachments_provided(): void
    {
        $response = $this->postJson('/api/simulator/scenarios', $this->basePayload([
            'dossier_id' => 1,
            'dossier_instruction_programme_id' => 1,
        ]));

        $response->assertStatus(422);
    }

    public function test_submit_locks_scenario_and_blocks_param_changes(): void
    {
        $response = $this->postJson('/api/simulator/scenarios', $this->basePayload());
        $token = $response->json('token');

        $this->post('/simulator/scenarios/'.$token.'/submit')->assertRedirect();

        $scenario = Scenario::where('token', $token)->first();
        $this->assertSame('submitted', $scenario->status);
        $this->assertTrue((bool) $scenario->is_locked);

        $this->expectException(\RuntimeException::class);
        try {
            $scenario->principal = 1.0;
            $scenario->save();
        } finally {
            $scenario->refresh();
            $scenario->forceDelete();
        }
    }
}
