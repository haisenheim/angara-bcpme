<?php

namespace Modules\Simulator\Contracts;

use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use Modules\Simulator\Persistence\Models\Scenario;

/**
 * Public contract of the Simulator module.
 * The rest of the application MUST only depend on this interface.
 */
interface SimulatorInterface
{
    /**
     * Compute an amortization schedule without persisting anything.
     */
    public function simulate(SimulationInput $input): SimulationResult;

    /**
     * Compute an amortization schedule and persist it as a Scenario.
     * Exactly one of $dossierId or $dossierProgrammeId may be provided (or none for a free scenario).
     */
    public function persist(
        SimulationInput $input,
        ?int $dossierId = null,
        ?int $dossierProgrammeId = null,
        ?string $name = null,
        ?int $userId = null,
    ): Scenario;

    public function attachToDossier(Scenario $scenario, int $dossierId, ?int $userId = null): Scenario;

    public function attachToDossierProgramme(Scenario $scenario, int $dossierProgrammeId, ?int $userId = null): Scenario;

    public function detach(Scenario $scenario, ?int $userId = null): Scenario;

    public function submit(Scenario $scenario, int $userId): Scenario;

    public function validate(Scenario $scenario, int $userId): Scenario;

    public function reject(Scenario $scenario, int $userId, string $reason): Scenario;
}
