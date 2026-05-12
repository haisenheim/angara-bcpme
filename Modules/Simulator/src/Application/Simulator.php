<?php

namespace Modules\Simulator\Application;

use InvalidArgumentException;
use Modules\Simulator\Contracts\SimulatorInterface;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use Modules\Simulator\Domain\Enums\ScenarioStatus;
use Modules\Simulator\Domain\Services\AmortizationCalculator;
use Modules\Simulator\Persistence\Models\Scenario;
use Modules\Simulator\Persistence\Repositories\ScenarioRepository;
use RuntimeException;

class Simulator implements SimulatorInterface
{
    public function __construct(
        private readonly AmortizationCalculator $calculator,
        private readonly ScenarioRepository $repository,
    ) {}

    public function simulate(SimulationInput $input): SimulationResult
    {
        return $this->calculator->compute($input);
    }

    public function persist(
        SimulationInput $input,
        ?int $dossierId = null,
        ?int $dossierProgrammeId = null,
        ?string $name = null,
        ?int $userId = null,
    ): Scenario {
        if ($dossierId !== null && $dossierProgrammeId !== null) {
            throw new InvalidArgumentException(
                'Un scenario ne peut etre attache qu a un dossier OU a une ligne dossier-programme, pas aux deux.'
            );
        }

        $result = $this->calculator->compute($input);
        $name = $name ?: $this->defaultName($input);

        return $this->repository->create(
            input: $input,
            result: $result,
            dossierId: $dossierId,
            dossierProgrammeId: $dossierProgrammeId,
            name: $name,
            userId: $userId,
        );
    }

    public function attachToDossier(Scenario $scenario, int $dossierId, ?int $userId = null): Scenario
    {
        $this->ensureUnlocked($scenario);

        $scenario->dossier_id = $dossierId;
        $scenario->dossier_instruction_programme_id = null;
        if ($userId !== null) {
            $scenario->updated_by_user_id = $userId;
        }
        $scenario->save();

        return $scenario;
    }

    public function attachToDossierProgramme(Scenario $scenario, int $dossierProgrammeId, ?int $userId = null): Scenario
    {
        $this->ensureUnlocked($scenario);

        $scenario->dossier_instruction_programme_id = $dossierProgrammeId;
        $scenario->dossier_id = null;
        if ($userId !== null) {
            $scenario->updated_by_user_id = $userId;
        }
        $scenario->save();

        return $scenario;
    }

    public function detach(Scenario $scenario, ?int $userId = null): Scenario
    {
        $this->ensureUnlocked($scenario);

        $scenario->dossier_id = null;
        $scenario->dossier_instruction_programme_id = null;
        if ($userId !== null) {
            $scenario->updated_by_user_id = $userId;
        }
        $scenario->save();

        return $scenario;
    }

    public function submit(Scenario $scenario, int $userId): Scenario
    {
        if ($scenario->status !== ScenarioStatus::DRAFT->value) {
            throw new RuntimeException(sprintf(
                'Seul un scenario en brouillon peut etre soumis. Statut actuel : %s.',
                $scenario->status,
            ));
        }

        $scenario->status = ScenarioStatus::SUBMITTED->value;
        $scenario->is_locked = true;
        $scenario->submitted_at = now();
        $scenario->submitted_by_user_id = $userId;
        $scenario->updated_by_user_id = $userId;
        $scenario->save();

        return $scenario;
    }

    public function validate(Scenario $scenario, int $userId): Scenario
    {
        if ($scenario->status !== ScenarioStatus::SUBMITTED->value) {
            throw new RuntimeException(sprintf(
                'Seul un scenario soumis peut etre valide. Statut actuel : %s.',
                $scenario->status,
            ));
        }

        $scenario->status = ScenarioStatus::VALIDATED->value;
        $scenario->validated_at = now();
        $scenario->validated_by_user_id = $userId;
        $scenario->updated_by_user_id = $userId;
        $scenario->save();

        return $scenario;
    }

    public function reject(Scenario $scenario, int $userId, string $reason): Scenario
    {
        if ($scenario->status !== ScenarioStatus::SUBMITTED->value) {
            throw new RuntimeException(sprintf(
                'Seul un scenario soumis peut etre rejete. Statut actuel : %s.',
                $scenario->status,
            ));
        }

        $scenario->status = ScenarioStatus::REJECTED->value;
        $scenario->rejected_at = now();
        $scenario->rejected_by_user_id = $userId;
        $scenario->rejection_reason = $reason;
        $scenario->updated_by_user_id = $userId;
        $scenario->save();

        return $scenario;
    }

    private function ensureUnlocked(Scenario $scenario): void
    {
        if ($scenario->is_locked) {
            throw new RuntimeException(sprintf(
                'Le scenario %s est verrouille (statut %s) : action interdite.',
                $scenario->token,
                $scenario->status,
            ));
        }
    }

    private function defaultName(SimulationInput $input): string
    {
        return sprintf(
            'Simulation %s %s %d periodes %s',
            number_format($input->principal, 0, ',', ' '),
            $input->currency,
            $input->termPeriods,
            $input->amortizationType->value,
        );
    }
}
