<?php

namespace Modules\Simulator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Simulator\Contracts\SimulatorInterface;
use Modules\Simulator\Domain\DTOs\ScheduleLineDto;
use Modules\Simulator\Domain\DTOs\SimulationInput;
use Modules\Simulator\Domain\DTOs\SimulationResult;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Exports\ScheduleExport;
use Modules\Simulator\Http\Requests\AttachScenarioRequest;
use Modules\Simulator\Persistence\Models\Scenario;
use Symfony\Component\HttpFoundation\Response;

class ScenarioController extends Controller
{
    public function __construct(private readonly SimulatorInterface $simulator) {}

    public function index(Request $request): View
    {
        $userId = optional($request->user())->id;
        $scenarios = Scenario::query()
            ->when($userId !== null, fn ($q) => $q->where('created_by_user_id', $userId))
            ->latest()
            ->paginate(20);

        return view('simulator::scenarios.index', compact('scenarios'));
    }

    public function show(Scenario $scenario): View
    {
        $scenario->load('lines');

        return view('simulator::scenarios.show', [
            'scenario' => $scenario,
            'result' => $this->scenarioToResult($scenario),
        ]);
    }

    public function submit(Request $request, Scenario $scenario): RedirectResponse
    {
        $this->simulator->submit($scenario, (int) $request->user()->id);

        return back()->with('success', 'Scenario soumis. Les parametres sont desormais verrouilles.');
    }

    public function validateScenario(Request $request, Scenario $scenario): RedirectResponse
    {
        $this->simulator->validate($scenario, (int) $request->user()->id);

        return back()->with('success', 'Scenario valide.');
    }

    public function reject(Request $request, Scenario $scenario): RedirectResponse
    {
        $request->validate(['reason' => ['required', 'string', 'max:1000']]);

        $this->simulator->reject($scenario, (int) $request->user()->id, (string) $request->input('reason'));

        return back()->with('success', 'Scenario rejete.');
    }

    public function detach(AttachScenarioRequest $request, Scenario $scenario): RedirectResponse
    {
        $this->simulator->detach($scenario, optional($request->user())->id);

        return back()->with('success', 'Scenario detache du dossier.');
    }

    public function destroy(Scenario $scenario): RedirectResponse
    {
        if ($scenario->is_locked) {
            return back()->withErrors(['scenario' => 'Scenario verrouille : suppression interdite.']);
        }

        $scenario->delete();

        return redirect()->route('simulator.scenarios.index')
            ->with('success', 'Scenario supprime.');
    }

    public function pdf(Scenario $scenario): Response
    {
        $scenario->load('lines');
        $result = $this->scenarioToResult($scenario);

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('simulator::pdf.schedule', [
            'result' => $result,
            'scenario' => $scenario,
        ]);

        return $pdf->download(sprintf('scenario-%s.pdf', $scenario->token));
    }

    public function xlsx(Scenario $scenario): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $scenario->load('lines');
        $result = $this->scenarioToResult($scenario);

        return ScheduleExport::download($result, sprintf('scenario-%s.xlsx', $scenario->token));
    }

    private function scenarioToResult(Scenario $scenario): SimulationResult
    {
        $input = new SimulationInput(
            principal: (float) $scenario->principal,
            annualRate: (float) $scenario->annual_rate,
            termPeriods: (int) $scenario->term_periods,
            periodicity: Periodicity::from((string) $scenario->periodicity),
            amortizationType: AmortizationType::from((string) $scenario->amortization_type),
            deferralType: DeferralType::from((string) $scenario->deferral_type),
            deferralPeriods: (int) $scenario->deferral_periods,
            firstPeriodDate: $scenario->first_period_date
                ? new \DateTimeImmutable($scenario->first_period_date->format('Y-m-d'))
                : null,
            currency: (string) $scenario->currency,
            fxRate: $scenario->fx_rate !== null ? (float) $scenario->fx_rate : null,
            dossierFeeFixed: (float) $scenario->dossier_fee_fixed,
            dossierFeePct: (float) $scenario->dossier_fee_pct,
            insurancePct: (float) $scenario->insurance_pct,
            insuranceBasis: InsuranceBasis::from((string) $scenario->insurance_basis),
            vatRate: (float) $scenario->vat_rate,
        );

        $lines = $scenario->lines->map(fn ($line) => new ScheduleLineDto(
            periodIndex: (int) $line->period_index,
            periodDate: $line->period_date
                ? new \DateTimeImmutable($line->period_date->format('Y-m-d'))
                : null,
            capitalDueStart: (float) $line->capital_due_start,
            principalPaid: (float) $line->principal_paid,
            interestPaid: (float) $line->interest_paid,
            insurancePaid: (float) $line->insurance_paid,
            feesPaid: (float) $line->fees_paid,
            vatPaid: (float) $line->vat_paid,
            totalPayment: (float) $line->total_payment,
            capitalDueEnd: (float) $line->capital_due_end,
            isDeferred: (bool) $line->is_deferred,
        ))->all();

        return new SimulationResult(
            input: $input,
            lines: $lines,
            totalPrincipal: (float) $scenario->total_principal,
            totalInterest: (float) $scenario->total_interest,
            totalFees: (float) $scenario->total_fees,
            totalInsurance: (float) $scenario->total_insurance,
            totalVat: (float) $scenario->total_vat,
            totalDue: (float) $scenario->total_due,
            firstPayment: (float) $scenario->first_payment,
            maxPayment: (float) $scenario->max_payment,
            computedTeg: $scenario->computed_teg !== null ? (float) $scenario->computed_teg : null,
        );
    }
}
