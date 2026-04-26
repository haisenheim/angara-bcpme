<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Instruction\EngagementEntreprise;
use App\Services\EngagementReportService;
use App\Services\TableDocumentExportService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EntrepriseController extends Controller
{
    public function getEngagementReport(string $token)
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $allowed = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->exists();
        if (! $allowed) {
            abort(403);
        }

        $engagements = [];
        $banques = Banque::all();
        $canEdit = true;
        $setEngagementUrl = route('analyste-credit.entreprise.set.engagement');

        return view('AnalysteCredit.Companies.engagement_report', compact(
            'engagements',
            'entreprise',
            'banques',
            'canEdit',
            'setEngagementUrl'
        ));
    }

    public function fetchEngagementReport(Request $request, string $token): JsonResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $allowed = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->exists();
        if (! $allowed) {
            abort(403);
        }

        $draw = (int) $request->query('draw', 1);
        $start = max(0, (int) $request->query('start', 0));
        $length = max(1, min(200, (int) $request->query('length', 25)));

        $banqueId = $request->integer('banque_id') ?: null;
        $search = trim((string) $request->input('search.value', $request->query('search', '')));

        $service = app(EngagementReportService::class);
        $all = $service->buildUiRowsForEntreprise($entreprise->id, null, '');
        $filtered = $service->buildUiRowsForEntreprise($entreprise->id, $banqueId, $search);
        $page = array_slice($filtered, $start, $length);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => count($all),
            'recordsFiltered' => count($filtered),
            'data' => $page,
        ]);
    }

    public function fetchEngagementReportFilterOptions(Request $request, string $token): JsonResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $allowed = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->exists();
        if (! $allowed) {
            abort(403);
        }

        $banques = Banque::query()->select(['id', 'name'])->orderBy('name')->get();

        return response()->json([
            'banques' => $banques->map(fn (Banque $b) => ['id' => $b->id, 'label' => $b->name])->values(),
        ]);
    }

    public function fetchEngagementReportStats(Request $request, string $token): JsonResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $allowed = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->exists();
        if (! $allowed) {
            abort(403);
        }

        $banqueId = $request->integer('banque_id') ?: null;
        $search = trim((string) $request->input('search.value', $request->query('search', '')));

        $service = app(EngagementReportService::class);
        $stats = $service->buildUiStatsForEntreprise($entreprise->id, $banqueId, $search);

        return response()->json($stats);
    }

    public function setEngagement(Request $request)
    {
        $data = $request->all();
        EngagementEntreprise::updateOrCreate(
            [
                'banque_id' => $data['banque_id'],
                'entreprise_id' => $data['entreprise_id'],
                'engagement_id' => $data['engagement_id'],
            ],
            $data
        );

        return back();
    }

    public function exportEngagementReport(Request $request, string $token)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $allowed = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->exists();
        if (! $allowed) {
            abort(403);
        }

        $banqueId = $request->integer('banque_id') ?: null;
        $search = trim((string) $request->input('search.value', $request->query('search', '')));
        $service = app(EngagementReportService::class);
        $table = $service->buildExportTableForEntreprise($entreprise->id, $banqueId, $search);

        $subtitle = $entreprise->name;

        return TableDocumentExportService::downloadFormatted(
            $table['rows'],
            $table['headers'],
            $format,
            'analyste-credit-engagements_'.$entreprise->token,
            'Analyste crédit — état des engagements',
            $subtitle
        );
    }
}
