<?php

namespace Modules\Simulator\Tests\Feature;

use Modules\Simulator\Application\Simulator;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Domain\Services\AmortizationCalculator;
use Modules\Simulator\Persistence\Models\Scenario;
use Modules\Simulator\Persistence\Repositories\ScenarioRepository;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Tests of Simulator workflow methods (submit/validate/reject/attach/detach)
 * using in-memory Eloquent models so we don't depend on the database.
 *
 * @group simulator
 */
class SimulatorServiceLogicTest extends TestCase
{
    private function makeSimulator(): Simulator
    {
        return new Simulator(
            new AmortizationCalculator,
            new class extends ScenarioRepository
            {
                public function create(SimulationInput $input, $result, ?int $dossierId, ?int $dossierProgrammeId, string $name, ?int $userId): Scenario
                {
                    $s = new Scenario;
                    $s->id = 42;
                    $s->token = 'fake-token';
                    $s->name = $name;
                    $s->status = 'draft';
                    $s->is_locked = false;
                    $s->dossier_id = $dossierId;
                    $s->dossier_instruction_programme_id = $dossierProgrammeId;

                    return $s;
                }
            },
        );
    }

    private function makeInput(): SimulationInput
    {
        return new SimulationInput(
            principal: 1_000_000.0,
            annualRate: 8.0,
            termPeriods: 12,
            periodicity: Periodicity::MONTHLY,
            amortizationType: AmortizationType::CONSTANT,
        );
    }

    public function test_persist_rejects_when_both_attachments_provided(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->makeSimulator()->persist($this->makeInput(), dossierId: 1, dossierProgrammeId: 2);
    }

    public function test_validate_rejects_a_draft_scenario(): void
    {
        $simulator = $this->makeSimulator();
        $scenario = new Scenario;
        $scenario->status = 'draft';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Seul un scenario soumis peut etre valide');

        $simulator->validate($scenario, 1);
    }

    public function test_reject_rejects_a_draft_scenario(): void
    {
        $simulator = $this->makeSimulator();
        $scenario = new Scenario;
        $scenario->status = 'draft';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Seul un scenario soumis peut etre rejete');

        $simulator->reject($scenario, 1, 'motif');
    }

    public function test_submit_rejects_a_validated_scenario(): void
    {
        $simulator = $this->makeSimulator();
        $scenario = new Scenario;
        $scenario->status = 'validated';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Seul un scenario en brouillon peut etre soumis');

        $simulator->submit($scenario, 1);
    }

    public function test_detach_rejects_locked_scenario(): void
    {
        $simulator = $this->makeSimulator();
        $scenario = new Scenario;
        $scenario->status = 'submitted';
        $scenario->is_locked = true;
        $scenario->token = 'abc';

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('verrouille');

        $simulator->detach($scenario);
    }
}
