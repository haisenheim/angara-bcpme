<?php

namespace Modules\Simulator\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Simulator\Contracts\SimulatorInterface;
use Modules\Simulator\Domain\Enums\AmortizationType;
use Modules\Simulator\Domain\Enums\DeferralType;
use Modules\Simulator\Domain\Enums\InsuranceBasis;
use Modules\Simulator\Domain\Enums\Periodicity;
use Modules\Simulator\Exports\ScheduleExport;
use Modules\Simulator\Http\Requests\PersistScenarioRequest;
use Modules\Simulator\Http\Requests\SimulateRequest;
use Modules\Simulator\Http\Resources\ScenarioResource;
use Symfony\Component\HttpFoundation\Response;

class SimulatorController extends Controller
{
    public function __construct(private readonly SimulatorInterface $simulator) {}

    public function index(Request $request): View
    {
        $context = [
            'currencies' => (array) config('simulator.currencies', []),
            'periodicities' => Periodicity::cases(),
            'amortization_types' => AmortizationType::cases(),
            'deferral_types' => DeferralType::cases(),
            'insurance_bases' => InsuranceBasis::cases(),
            'default_currency' => (string) config('simulator.default_currency', 'XOF'),
            'usury_rate_warning' => (float) config('simulator.usury_rate_warning', 0),
            'preselected' => $this->preselectedFromQuery($request),
        ];

        return view('simulator::index', $context);
    }

    public function simulate(SimulateRequest $request): JsonResponse
    {
        $result = $this->simulator->simulate($request->toInput());

        return response()->json($result->toArray());
    }

    public function persist(PersistScenarioRequest $request): JsonResponse
    {
        $input = $request->toInput();
        $scenario = $this->simulator->persist(
            input: $input,
            dossierId: $request->filled('dossier_id') ? (int) $request->input('dossier_id') : null,
            dossierProgrammeId: $request->filled('dossier_instruction_programme_id')
                ? (int) $request->input('dossier_instruction_programme_id')
                : null,
            name: $request->input('name'),
            userId: optional($request->user())->id,
        );

        return response()->json(
            (new ScenarioResource($scenario->load('lines')))->toArray($request),
            Response::HTTP_CREATED,
        );
    }

    public function exportPdfFromInput(SimulateRequest $request): Response
    {
        $result = $this->simulator->simulate($request->toInput());

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('simulator::pdf.schedule', [
            'result' => $result,
            'scenario' => null,
        ]);

        return $pdf->download('simulation-credit.pdf');
    }

    public function exportXlsxFromInput(SimulateRequest $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $result = $this->simulator->simulate($request->toInput());

        return ScheduleExport::download($result, 'simulation-credit.xlsx');
    }

    private function preselectedFromQuery(Request $request): array
    {
        $dossierId = $request->query('dossier_id');
        $dipId = $request->query('dossier_instruction_programme_id');

        $preselected = [];
        if ($dossierId !== null && is_numeric($dossierId)) {
            $preselected['dossier_id'] = (int) $dossierId;
        }
        if ($dipId !== null && is_numeric($dipId)) {
            $preselected['dossier_instruction_programme_id'] = (int) $dipId;
            $line = \App\Models\DossierInstructionProgramme::find((int) $dipId);
            if ($line !== null) {
                $preselected['principal'] = (float) $line->budget_appui_financier;
                $preselected['dossier_id'] = (int) $line->dossier_id;
            }
        }

        if (empty($preselected['dossier_id'])) {
            $entrepriseId = $request->query('entreprise_id');
            if ($entrepriseId !== null && is_numeric($entrepriseId)) {
                $latestDossierId = \App\Models\Dossier::query()
                    ->where('entreprise_id', (int) $entrepriseId)
                    ->orderByDesc('id')
                    ->value('id');
                if ($latestDossierId) {
                    $preselected['dossier_id'] = (int) $latestDossierId;
                }
            }
        }

        return $preselected;
    }
}
