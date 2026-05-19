<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
use App\Services\InstructionAnalyseCritiqueDossierDocumentService;
use App\Services\WorkflowEmailNotificationService;
use Dompdf\Canvas;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    use StoresDossierPieces;

    //
    public function index()
    {
        //
        return view('/Ca/Dossiers/index');
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->orderBy('created_at', 'DESC')->get();
        $items = DossierListResource::collection($items);

        return response()->json($items);
    }

    private function baseQuery()
    {
        return Dossier::where('agence_id', auth()->user()->agence_id);
    }

    public function fetchStats(Request $request)
    {
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'avec_analyste' => (clone $query)->whereNotNull('analyste_id')->count(),
            'sans_analyste' => (clone $query)->whereNull('analyste_id')->count(),
        ];

        return response()->json($stats);
    }

    public function fetchPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $recordsTotal = $this->baseQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('entreprise', fn ($e) => $e->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('instructionProgrammes.programme', fn ($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('analyste', fn ($a) => $a->where('name', 'like', "%{$search}%"));
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->with(['entreprise', 'programme', 'instructionProgrammes.programme', 'analyste', 'agence'])->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = DossierListResource::collection($items)->toArray($request);
        $data = array_values($resolved['data'] ?? $resolved);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    private function parseFilters(Request $request): array
    {
        return [
            'programme_id' => $request->input('programme_id'),
            'analyste_id' => $request->input('analyste_id'),
            'entity_route' => $request->input('entity_route'),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (! empty($filters['programme_id'])) {
            $pid = $filters['programme_id'];
            $query->where(function ($q) use ($pid) {
                $q->where('programme_id', $pid)
                    ->orWhereHas('instructionProgrammes', fn ($q2) => $q2->where('programme_id', $pid));
            });
        }
        if (! empty($filters['analyste_id'])) {
            $query->where('analyste_id', $filters['analyste_id']);
        }
        if (! empty($filters['entity_route'])) {
            $query->inCurrentEntity((string) $filters['entity_route']);
        }

        return $query;
    }

    public function fetchFilterOptions()
    {
        $agenceId = auth()->user()->agence_id;
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = Dossier::where('agence_id', $agenceId)->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
        $analystes = \App\Models\User::whereIn('id', $analysteIds)->get(['id', 'name']);
        $entities = [
            ['route' => 'respexp', 'label' => 'Pôle exploitation'],
            ['route' => 'juridique', 'label' => 'Pôle juridique'],
            ['route' => 'reng', 'label' => 'Pôle engagements'],
            ['route' => 'rerx', 'label' => 'Pôle risques'],
            ['route' => 'dg', 'label' => 'Direction générale'],
        ];

        return response()->json(['programmes' => $programmes, 'analystes' => $analystes, 'entities' => $entities]);
    }

    public function show($token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
                'gestionnaire',
                'analyste',
                'agence',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
                'instructionAgenceCaAvisSavedBy',
                'instructionCaTransmittedToExploitationBy',
                'exploitationAnalysteAssignedBy',
                'exploitationAnalysteTransmittedToExploitationBy',
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
                'exploitationAvisCreditUser',
                'exploitationEngagementsDecisionUser',
                'juridiqueInstructionSubmittedBy',
                'juridiqueAnalysteUser',
                'juridiqueAnalysteAssignedBy',
                'juridiqueAnalysteSubmittedToRejuBy',
                'juridiqueSubmittedToEngagementsBy',
                'rengAnalysteCreditUser',
                'rengAnalysteCreditAssignedBy',
                'rengAnalysteCreditSubmittedBy',
                'rengSubmittedToRisquesBy',
                'rerxAnalysteRisquesUser',
                'rerxAnalysteRisquesAssignedBy',
                'rerxAnalysteRisquesSubmittedBy',
                'rerxSubmittedToDirectionBy',
            ])
            ->firstOrFail();

        $presented = app(\App\Services\DossierInstructionShowPresenter::class)->presentForDossier($dossier);
        $dossier = $presented['item'];
        $criteres = $presented['criteres'];
        $indicateurs = $presented['indicateurs'];
        $indicateurReference = $presented['indicateurReference'];
        $noteFinale = $presented['noteFinale'];
        $sme = $presented['sme'];
        $smeMention = $presented['smeMention'];
        $smeDescription = $presented['smeDescription'];
        $banques = $presented['banques'];
        $engagementGridUrl = $presented['engagementGridUrl'];
        $instructionConsultation = $presented['instructionConsultation'];
        $fichierTypes = $presented['fichierTypes'];

        $structuration = app(\App\Services\StructurationClosureService::class);
        $canApproveRejectInstructionTransmission = $structuration->canChefAgenceDecide(auth()->user(), $dossier);

        $space = [
            'route' => 'ca',
            'title' => "Chef d'agence",
        ];
        $readonly = false;
        $piecesModalId = 'dossierPieceUploadModal_ca';
        $exploitationSteps = $dossier->exploitationWorkflowSteps();

        $delegation = app(\App\Services\InstructionDelegationService::class);
        $canCloseInstruction = $delegation->userCanCloseInstruction(auth()->user(), $dossier);
        $instructionClosureRuleDescription = $delegation->describeRuleForInstructionClosure($dossier);
        $instructionClosureStatutLabel = $delegation->instructionClosureStatutLabel($dossier);

        $analystesExploitation = collect();
        $analystesJuridique = collect();
        $analystesCredit = collect();
        $analystesRisques = collect();
        $respexpInstructionLocked = false;

        return view('RoleSpace.dossiers.show', compact(
            'space',
            'dossier',
            'criteres',
            'indicateurs',
            'indicateurReference',
            'noteFinale',
            'sme',
            'smeMention',
            'smeDescription',
            'banques',
            'engagementGridUrl',
            'instructionConsultation',
            'fichierTypes',
            'canApproveRejectInstructionTransmission',
            'readonly',
            'piecesModalId',
            'exploitationSteps',
            'canCloseInstruction',
            'instructionClosureRuleDescription',
            'instructionClosureStatutLabel',
            'analystesExploitation',
            'analystesJuridique',
            'analystesCredit',
            'analystesRisques',
            'respexpInstructionLocked',
        ));
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'ca.dossiers.show', $dossier);
    }

    public function saveInstructionAgenceCaAvis(Request $request, string $token)
    {
        $data = $request->validate([
            'instruction_agence_ca_avis' => 'nullable|string|max:65535',
        ]);

        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        if (! $dossier->isInstructionValidatedByAgence()) {
            return redirect()
                ->route('ca.dossiers.show', $token)
                ->with('info', 'L’avis du chef d’agence n’est disponible qu’après validation de la transmission du dossier d’instruction.');
        }

        if ($dossier->isInstructionCaTransmittedToExploitation()) {
            return redirect()
                ->route('ca.dossiers.show', $token)
                ->with('info', 'Le dossier a été transmis au responsable exploitation : l’avis du chef d’agence ne peut plus être modifié.');
        }

        $html = $data['instruction_agence_ca_avis'] ?? null;
        $dossier->update([
            'instruction_agence_ca_avis' => $html,
            'instruction_agence_ca_avis_saved_at' => now(),
            'instruction_agence_ca_avis_saved_by_user_id' => auth()->id(),
        ]);

        return redirect()
            ->route('ca.dossiers.show', $token)
            ->with('success', 'Avis du chef d’agence enregistré.');
    }

    public function transmitInstructionCaToExploitation(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->firstOrFail();

        if (! $dossier->isInstructionValidatedByAgence()) {
            return redirect()
                ->route('ca.dossiers.show', $token)
                ->with('info', 'La transmission au responsable exploitation n’est possible qu’après validation de la transmission du dossier d’instruction par l’agence.');
        }

        if ($dossier->isInstructionCaTransmittedToExploitation()) {
            return redirect()
                ->route('ca.dossiers.show', $token)
                ->with('info', 'Ce dossier a déjà été transmis au responsable exploitation.');
        }

        if (! $dossier->hasInstructionAgenceCaAvisSubstance()) {
            return redirect()
                ->route('ca.dossiers.show', $token)
                ->withErrors(['transmit' => 'Enregistrez d’abord un avis du chef d’agence (contenu non vide) avant de transmettre au responsable exploitation.']);
        }

        $dossier->update([
            'instruction_ca_transmitted_to_exploitation_at' => now(),
            'instruction_ca_transmitted_to_exploitation_by_user_id' => auth()->id(),
        ]);

        $mailer = app(WorkflowEmailNotificationService::class);
        $ctx = $mailer->contextForDossier($dossier);
        $payload = $mailer->buildPayload(
            subject: 'Transmission de dossier — responsable exploitation',
            title: 'Un dossier a été transmis au responsable exploitation',
            body: "Un dossier d’instruction a été transmis par le chef d’agence au responsable exploitation.\n\nMerci de consulter le dossier et d’effectuer les actions attendues (avis / engagements / transmission juridique).",
            ctaLabel: 'Ouvrir le dossier',
            ctaUrl: route('respexp.dossiers.show', $dossier->token),
            event: 'submit_ca_to_respexp'
        );
        $recipients = $mailer->recipientsByRole((int) config('angara.role_responsable_exploitation', 6));
        $mailer->notifyUsers($recipients, auth()->user(), $payload, $ctx);

        return redirect()
            ->route('ca.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable exploitation.');
    }

    public function getGrilleAnalyse($token)
    {
        $item = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();

        $structuration = app(\App\Services\StructurationClosureService::class);
        $canApproveRejectInstructionTransmission = $structuration->canChefAgenceDecide(auth()->user(), $item);

        return view('Ca/Dossiers/analyse_critique', compact('item', 'canApproveRejectInstructionTransmission'));
    }

    /**
     * Dossier d’analyse critique : analyse critique de l’analyste financier (rubriques alignées sur la fiche dossier).
     */
    public function dossierAnalyseCritiqueSyntheseShow(string $token)
    {
        $item = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
            ])
            ->firstOrFail();

        $doc = app(InstructionAnalyseCritiqueDossierDocumentService::class)->build($item);

        return view('Ca/Dossiers/dossier_analyse_critique', compact('item', 'doc'));
    }

    public function dossierAnalyseCritiqueSynthesePdf(string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
            ])
            ->firstOrFail();

        $doc = app(InstructionAnalyseCritiqueDossierDocumentService::class)->build($dossier);

        $logoData = '';
        $logoPath = public_path('img/logo-bcpme.png');
        if (is_readable($logoPath)) {
            $logoData = base64_encode((string) file_get_contents($logoPath));
        }

        $generatedAt = now();

        $pdf = app('dompdf.wrapper');
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadView('RoleSpace.dossiers.dossier_analyse_critique_pdf', [
            'item' => $dossier,
            'doc' => $doc,
            'logoData' => $logoData,
            'generatedAt' => $generatedAt,
        ]);
        $pdf->setCallbacks([
            [
                'event' => 'end_document',
                'f' => function (int $pageNumber, int $pageCount, Canvas $canvas, FontMetrics $fontMetrics): void {
                    $font = $fontMetrics->get_font('DejaVu Sans', 'normal');
                    $size = 8;
                    $color = [0.35, 0.35, 0.35];
                    $w = $canvas->get_width();
                    $h = $canvas->get_height();
                    $y = $h - 28;
                    $pageLabel = 'Page '.$pageNumber.' / '.$pageCount;
                    $tw = $canvas->get_text_width($pageLabel, $font, $size);
                    $canvas->text($w - $tw - 18, $y, $pageLabel, $font, $size, $color);
                    $canvas->text(18, $y, 'BC-PME — Angara', $font, $size, $color);
                },
            ],
        ]);
        $filename = 'dossier-analyse-critique-'.preg_replace('/[^a-zA-Z0-9_-]+/', '-', (string) $dossier->token).'.pdf';

        return $pdf->download($filename);
    }

    public function setAnalyse()
    {
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $dossier = Dossier::find($dossier_id);
        if (! $dossier || $dossier->agence_id != auth()->user()->agence_id) {
            return back();
        }
        $data = [];
        if ($sequence == 1) {
            $data = ['donnees_generales' => $content];
        }
        if ($sequence == 2) {
            $data = ['analyse_ensemble' => $content];
        }
        if ($sequence == 3) {
            $data = ['analyse_financiere' => $content];
        }
        if ($sequence == 4) {
            $data = ['appuis' => $content];
        }
        if ($sequence == 5) {
            $data = ['analyse_risque' => $content];
        }
        if ($sequence == 6) {
            $data = ['analyse_rentabilite' => $content];
        }
        if ($sequence == 8) {
            $data = ['conclusions_gestionnaire' => $content];
        }
        if ($sequence == 9) {
            $data = [
                'conclusions_ca' => $content,
                'conclusions_ca_saved_at' => now(),
                'conclusions_ca_saved_by_user_id' => auth()->id(),
            ];
        }
        if (! empty($data) && (int) $sequence !== 9) {
            $data['instruction_grille_last_edited_at'] = now();
            $data['instruction_grille_last_edited_by_user_id'] = auth()->id();
        }
        if (! empty($data)) {
            $dossier->update($data);
        }

        return redirect()->back()->with('success', 'Enregistrement effectué.');
    }
}
