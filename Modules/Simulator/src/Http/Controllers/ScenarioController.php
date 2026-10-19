<?php

namespace Modules\Simulator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function index(Request $request): \Inertia\Response
    {
        $userId = optional($request->user())->id;
        $scenarios = Scenario::query()
            ->when($userId !== null, fn ($q) => $q->where('created_by_user_id', $userId))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $table = \App\Support\Inertia\DataTable::make($scenarios, $request)
            ->columns([
                ['key' => 'name', 'label' => 'Scénario'],
                ['key' => 'rattachement', 'label' => 'Rattachement'],
                ['key' => 'capital', 'label' => 'Capital', 'align' => 'right'],
                ['key' => 'taux', 'label' => 'Taux', 'align' => 'right'],
                ['key' => 'periodicite', 'label' => 'Périodicité'],
                ['key' => 'teg', 'label' => 'TEG', 'align' => 'right'],
                ['key' => 'statut', 'label' => 'Statut'],
                ['key' => 'date', 'label' => 'Date'],
            ])
            ->row(fn (Scenario $s) => [
                'href' => route('simulator.scenarios.show', $s),
                'cells' => [
                    'name' => ['title' => $s->name ?: 'Scénario sans nom'],
                    'rattachement' => $s->dossier_id
                        ? ['badge' => 'Dossier #'.$s->dossier_id, 'variant' => 'info']
                        : ($s->dossier_instruction_programme_id
                            ? ['badge' => 'Ligne #'.$s->dossier_instruction_programme_id, 'variant' => 'info']
                            : ['badge' => 'Libre', 'variant' => 'neutral']),
                    'capital' => number_format((float) $s->principal, 0, ',', ' ').' '.$s->currency,
                    'taux' => number_format((float) $s->annual_rate, 2, ',', ' ').' %',
                    'periodicite' => $s->periodicityEnum()->label(),
                    'teg' => $s->computed_teg !== null ? number_format((float) $s->computed_teg, 2, ',', ' ').' %' : null,
                    'statut' => self::statusBadge((string) $s->status),
                    'date' => $s->created_at?->format('d/m/Y H:i'),
                ],
            ])
            ->empty('Aucun scénario enregistré.');

        return \Inertia\Inertia::render('Simulator/Scenarios', [
            'table' => $table->toArray(),
            'simulatorUrl' => route('simulator.index'),
            'breadcrumb' => [
                ['label' => 'Simulateur de crédit', 'href' => route('simulator.index')],
                ['label' => 'Scénarios enregistrés'],
            ],
        ]);
    }

    public function show(Scenario $scenario): \Inertia\Response
    {
        $scenario->load('lines');
        $money = fn ($v) => number_format((float) $v, 2, ',', ' ');
        $date = fn ($v) => $v ? \Carbon\Carbon::parse($v)->format('d/m/Y H:i') : null;

        $actions = [];
        $simple = fn (string $key, string $label, string $route, string $variant, string $icon, array $fields = [], ?string $confirm = null, string $method = 'post') => [
            'key' => $key, 'label' => $label, 'icon' => $icon, 'variant' => $variant, 'title' => $label,
            'description' => $confirm, 'fields' => $fields,
            'submits' => [['label' => $label, 'url' => route($route, $scenario), 'variant' => $variant === 'secondary' ? 'primary' : $variant, 'method' => $method]],
        ];
        if ($scenario->status === 'draft') {
            $actions[] = $simple('submit', 'Soumettre', 'simulator.scenarios.submit', 'primary', 'send', [], 'Après soumission, les paramètres du scénario sont verrouillés.');
        } elseif ($scenario->status === 'submitted') {
            $actions[] = $simple('validate', 'Valider', 'simulator.scenarios.validate', 'success', 'task_alt');
            $actions[] = $simple('reject', 'Rejeter', 'simulator.scenarios.reject', 'danger', 'block',
                [['name' => 'reason', 'label' => 'Motif du rejet', 'type' => 'textarea', 'required' => true]]);
        }
        if (! $scenario->is_locked) {
            if ($scenario->dossier_id || $scenario->dossier_instruction_programme_id) {
                $actions[] = $simple('detach', 'Détacher du dossier', 'simulator.scenarios.detach', 'secondary', 'link_off');
            }
            $actions[] = $simple('delete', 'Supprimer', 'simulator.scenarios.destroy', 'danger', 'delete', [], 'Le scénario sera définitivement supprimé.', 'delete');
        }

        return \Inertia\Inertia::render('Simulator/Scenario', [
            'scenario' => [
                'name' => $scenario->name ?: 'Scénario sans nom',
                'status' => self::statusBadge((string) $scenario->status),
                'locked' => (bool) $scenario->is_locked,
                'parametres' => [
                    ['Capital emprunté', $money($scenario->principal).' '.$scenario->currency],
                    ['Taux annuel', number_format((float) $scenario->annual_rate, 4, ',', ' ').' %'],
                    ['Durée (périodes)', (string) $scenario->term_periods],
                    ['Périodicité', $scenario->periodicityEnum()->label()],
                    ['Amortissement', $scenario->amortizationTypeEnum()->label()],
                    ['Différé', $scenario->deferralTypeEnum()->label().' ('.$scenario->deferral_periods.')'],
                    ['TEG calculé', $scenario->computed_teg !== null ? number_format((float) $scenario->computed_teg, 4, ',', ' ').' %' : null],
                    ['Total à rembourser', $money($scenario->total_due).' '.$scenario->currency],
                ],
                'rattachement' => $scenario->dossier_id
                    ? 'Dossier #'.$scenario->dossier_id
                    : ($scenario->dossier_instruction_programme_id ? 'Ligne dossier-programme #'.$scenario->dossier_instruction_programme_id : 'Scénario libre (non rattaché)'),
                'events' => array_values(array_filter([
                    ['label' => 'Créé le', 'at' => $date($scenario->created_at)],
                    $scenario->submitted_at ? ['label' => 'Soumis le', 'at' => $date($scenario->submitted_at)] : null,
                    $scenario->validated_at ? ['label' => 'Validé le', 'at' => $date($scenario->validated_at)] : null,
                    $scenario->rejected_at ? ['label' => 'Rejeté le', 'at' => $date($scenario->rejected_at), 'motif' => $scenario->rejection_reason] : null,
                ])),
                'lines' => $scenario->lines->map(fn ($l) => [
                    'period' => $l->period_index,
                    'date' => $l->period_date ? \Carbon\Carbon::parse($l->period_date)->format('d/m/Y') : null,
                    'deferred' => (bool) $l->is_deferred,
                    'capitalStart' => $money($l->capital_due_start),
                    'principal' => $money($l->principal_paid),
                    'interest' => $money($l->interest_paid),
                    'insurance' => $money($l->insurance_paid),
                    'fees' => $money($l->fees_paid),
                    'vat' => $money($l->vat_paid),
                    'total' => $money($l->total_payment),
                    'capitalEnd' => $money($l->capital_due_end),
                ])->values(),
            ],
            'actions' => $actions,
            'links' => [
                ['label' => 'Exporter PDF', 'icon' => 'picture_as_pdf', 'href' => route('simulator.scenarios.pdf', $scenario)],
                ['label' => 'Exporter Excel', 'icon' => 'table_view', 'href' => route('simulator.scenarios.xlsx', $scenario)],
            ],
            'breadcrumb' => [
                ['label' => 'Simulateur de crédit', 'href' => route('simulator.index')],
                ['label' => 'Scénarios', 'href' => route('simulator.scenarios.index')],
                ['label' => $scenario->name ?: 'Scénario'],
            ],
        ]);
    }

    /** Statut du scénario → badge (draft, submitted, validated, rejected). */
    private static function statusBadge(string $status): array
    {
        return match ($status) {
            'submitted' => ['badge' => 'Soumis', 'variant' => 'info'],
            'validated' => ['badge' => 'Validé', 'variant' => 'success'],
            'rejected' => ['badge' => 'Rejeté', 'variant' => 'danger'],
            default => ['badge' => 'Brouillon', 'variant' => 'neutral'],
        };
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
