<?php

namespace Modules\Simulator\Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * @group simulator
 */
class SimulateEndpointTest extends TestCase
{
    use DatabaseTransactions;

    private function actingAsAllowedUser(): void
    {
        config()->set('simulator.allowed_roles', [16]);
        $user = new User;
        $user->id = 1;
        $user->role_id = 16;
        $this->actingAs($user);
    }

    public function test_simulate_endpoint_returns_schedule(): void
    {
        $this->actingAsAllowedUser();

        $response = $this->postJson('/api/simulator/simulate', [
            'principal' => 10_000_000,
            'annual_rate' => 8.0,
            'term_periods' => 12,
            'periodicity' => 'monthly',
            'amortization_type' => 'constant',
            'currency' => 'XOF',
        ]);

        $response->assertOk();
        $response->assertJsonStructure([
            'input',
            'lines' => [['period_index', 'capital_due_start', 'principal_paid', 'interest_paid', 'total_payment']],
            'totals' => ['principal', 'interest', 'total_due', 'first_payment', 'max_payment', 'computed_teg'],
        ]);

        $payload = $response->json();
        $this->assertCount(12, $payload['lines']);
        $this->assertGreaterThan(0, $payload['totals']['interest']);
    }

    public function test_simulate_endpoint_validates_required_fields(): void
    {
        $this->actingAsAllowedUser();

        $response = $this->postJson('/api/simulator/simulate', [
            'principal' => 0,
            'annual_rate' => 8,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['principal', 'term_periods', 'periodicity', 'amortization_type', 'currency']);
    }

    public function test_simulate_endpoint_rejects_unauthenticated(): void
    {
        $response = $this->postJson('/api/simulator/simulate', [
            'principal' => 1_000_000,
            'annual_rate' => 8,
            'term_periods' => 12,
            'periodicity' => 'monthly',
            'amortization_type' => 'constant',
            'currency' => 'XOF',
        ]);

        $response->assertStatus(401);
    }
}
