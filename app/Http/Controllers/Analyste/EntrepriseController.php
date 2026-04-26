<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Models\Banque;
use App\Models\Entreprise;
use App\Models\Instruction\EngagementEntreprise;
use App\Services\EngagementReportService;
use App\Services\TableDocumentExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class EntrepriseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resp = Http::get('http://localhost:8080/entreprises');
        $items = json_decode($resp->body(),true);
        //dd($items);
        return view('/Analyste/Entreprises/index')->with(compact('items'));
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        return back();
    }



    public function getEngagementReport($token)
    {
        $entreprise = Entreprise::query()->where('token', $token)->first();
        if (! $entreprise) {
            return back();
        }

        $banques = Banque::all();
        $canEdit = true;
        $setEngagementUrl = route('analyste.entreprise.set.engagement');

        return view('Analyste.Companies.engagement_report', [
            'engagements' => [],
            'entreprise' => $entreprise,
            'banques' => $banques,
            'canEdit' => $canEdit,
            'setEngagementUrl' => $setEngagementUrl,
        ]);
    }

    public function fetchEngagementReport(Request $request, string $token): JsonResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

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
        // token is kept for a consistent URL shape; no entreprise-specific options yet.
        Entreprise::query()->where('token', $token)->firstOrFail();

        $banques = Banque::query()->select(['id', 'name'])->orderBy('name')->get();

        return response()->json([
            'banques' => $banques->map(fn (Banque $b) => ['id' => $b->id, 'label' => $b->name])->values(),
        ]);
    }

    public function fetchEngagementReportStats(Request $request, string $token): JsonResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $banqueId = $request->integer('banque_id') ?: null;
        $search = trim((string) $request->input('search.value', $request->query('search', '')));

        $service = app(EngagementReportService::class);
        $stats = $service->buildUiStatsForEntreprise($entreprise->id, $banqueId, $search);

        return response()->json($stats);
    }

    public function exportEngagementReport(Request $request, string $token)
    {
        $format = strtolower((string) $request->query('format', 'xlsx'));
        if (! in_array($format, ['xlsx', 'pdf'], true)) {
            abort(400, 'Format invalide');
        }

        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $banqueId = $request->integer('banque_id') ?: null;
        $search = trim((string) $request->input('search.value', $request->query('search', '')));

        $service = app(EngagementReportService::class);
        $table = $service->buildExportTableForEntreprise($entreprise->id, $banqueId, $search);

        $subtitle = $entreprise->name;

        return TableDocumentExportService::downloadFormatted(
            $table['rows'],
            $table['headers'],
            $format,
            'analyste-engagements_'.$entreprise->token,
            'Analyste — état des engagements',
            $subtitle
        );
    }

    public function setEngagement(Request $request){
        $data = $request->all();
        EngagementEntreprise::updateOrCreate(
          [
            'banque_id'=>$data['banque_id'],
            'entreprise_id'=>$data['entreprise_id'],
            'engagement_id'=>$data['engagement_id'],
          ],
          $data
        );
        return back();
    }





    public function setAnalyse(){
        $data = request()->except('_token');
        //dd($data);
        $resp = Http::post('http://localhost:8080/entreprise/dossier/analyse',$data);
        return back();
    }

    public function  enable($id){

        return back();
    }

    public function  disable($id){

        return back();
    }




}
