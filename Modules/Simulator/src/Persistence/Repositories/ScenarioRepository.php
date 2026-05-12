<?php

namespace Modules\Simulator\Persistence\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Simulator\Domain\DTOs\ScheduleLineDto;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use Modules\Simulator\Persistence\Models\Scenario;

class ScenarioRepository
{
    /**
     * Persist a scenario along with its schedule lines.
     */
    public function create(
        SimulationInput $input,
        SimulationResult $result,
        ?int $dossierId,
        ?int $dossierProgrammeId,
        string $name,
        ?int $userId,
    ): Scenario {
        return DB::connection(config('simulator.connection', 'central_app_mysql'))
            ->transaction(function () use ($input, $result, $dossierId, $dossierProgrammeId, $name, $userId) {
                $scenario = Scenario::create([
                    'name' => $name,
                    'dossier_id' => $dossierId,
                    'dossier_instruction_programme_id' => $dossierProgrammeId,
                    'currency' => $input->currency,
                    'fx_rate' => $input->fxRate,
                    'principal' => $input->principal,
                    'annual_rate' => $input->annualRate,
                    'term_periods' => $input->termPeriods,
                    'periodicity' => $input->periodicity->value,
                    'amortization_type' => $input->amortizationType->value,
                    'deferral_type' => $input->deferralType->value,
                    'deferral_periods' => $input->deferralPeriods,
                    'first_period_date' => $input->firstPeriodDate?->format('Y-m-d'),
                    'dossier_fee_fixed' => $input->dossierFeeFixed,
                    'dossier_fee_pct' => $input->dossierFeePct,
                    'insurance_pct' => $input->insurancePct,
                    'insurance_basis' => $input->insuranceBasis->value,
                    'vat_rate' => $input->vatRate,
                    'computed_teg' => $result->computedTeg,
                    'total_principal' => $result->totalPrincipal,
                    'total_interest' => $result->totalInterest,
                    'total_fees' => $result->totalFees,
                    'total_insurance' => $result->totalInsurance,
                    'total_vat' => $result->totalVat,
                    'total_due' => $result->totalDue,
                    'first_payment' => $result->firstPayment,
                    'max_payment' => $result->maxPayment,
                    'status' => 'draft',
                    'is_locked' => false,
                    'created_by_user_id' => $userId,
                    'updated_by_user_id' => $userId,
                ]);

                $rows = [];
                foreach ($result->lines as $line) {
                    /** @var ScheduleLineDto $line */
                    $rows[] = [
                        'scenario_id' => $scenario->id,
                        'period_index' => $line->periodIndex,
                        'period_date' => $line->periodDate?->format('Y-m-d'),
                        'capital_due_start' => round($line->capitalDueStart, 2),
                        'principal_paid' => round($line->principalPaid, 2),
                        'interest_paid' => round($line->interestPaid, 2),
                        'insurance_paid' => round($line->insurancePaid, 2),
                        'fees_paid' => round($line->feesPaid, 2),
                        'vat_paid' => round($line->vatPaid, 2),
                        'total_payment' => round($line->totalPayment, 2),
                        'capital_due_end' => round($line->capitalDueEnd, 2),
                        'is_deferred' => $line->isDeferred,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if ($rows !== []) {
                    DB::connection($scenario->getConnectionName())
                        ->table('simulator_schedule_lines')
                        ->insert($rows);
                }

                return $scenario->fresh('lines');
            });
    }
}
