<?php

namespace App\Http\Controllers\Ca;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\FichierType;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;
use App\Services\InstructionDelegationService;
use App\Services\InstructionDossierAnalyseCritiqueSyntheseService;
use Dompdf\Canvas;
use Dompdf\FontMetrics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    use StoresDossierPieces;

    //
    public function index()
    {
        //
        return view('/Ca/Dossiers/index');
    }

    public function fetchAll(){
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
        if (!empty($filters['analyste_id'])) $query->where('analyste_id', $filters['analyste_id']);
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

    public function show($token){

        $item = Dossier::query()
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
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();
        $criteres = Critere::all();
        $id = $item->id;
        $criteres = $criteres->map(function($critere)use($id){
            $critere->souscriteres = $critere->sousCriteres->map(function($souscritere)use($id){
                $souscritere->reponse = $souscritere->reponses->where('dossier_id',$id)->first();
                return $souscritere;
            });
            return $critere;
        });

        $criteres = $criteres->map(function($ct){
            return $this->parseCriteres($ct);
        });

        $indicateurs = IndicateurFinancier::where('dossier_id',$item->id)->get();
        $banques = Banque::all();
        $sme = DossierHelper::getSme($item->note);
        $instructionConsultation = app(\App\Services\InstructionDossierConsultationService::class)->build($item);

        $structuration = app(\App\Services\StructurationClosureService::class);
        $canApproveRejectInstructionTransmission = $structuration->canChefAgenceDecide(auth()->user(), $item);

        $fichierTypes = FichierType::query()->orderBy('name')->get(['id', 'name']);

        return view('Ca/Dossiers/show', compact(
            'item',
            'indicateurs',
            'criteres',
            'sme',
            'banques',
            'instructionConsultation',
            'canApproveRejectInstructionTransmission',
            'fichierTypes',
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
     * Synthèse chronologique du dossier d’analyse critique (instruction + avis intégrés).
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
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();

        $entries = app(InstructionDossierAnalyseCritiqueSyntheseService::class)->buildOrderedEntries($item);

        return view('Ca/Dossiers/dossier_analyse_critique', compact('item', 'entries'));
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
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();

        $entries = app(InstructionDossierAnalyseCritiqueSyntheseService::class)->buildOrderedEntries($dossier);

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
            'entries' => $entries,
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

    public function setAnalyse(){
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $dossier = Dossier::find($dossier_id);
        if (!$dossier || $dossier->agence_id != auth()->user()->agence_id) {
            return back();
        }
        $data = [];
        if($sequence==1) $data = ['donnees_generales'=>$content];
        if($sequence==2) $data = ['analyse_ensemble'=>$content];
        if($sequence==3) $data = ['analyse_financiere'=>$content];
        if($sequence==4) $data = ['appuis'=>$content];
        if($sequence==5) $data = ['analyse_risque'=>$content];
        if($sequence==6) $data = ['analyse_rentabilite'=>$content];
        if($sequence==8) $data = ['conclusions_gestionnaire'=>$content];
        if($sequence==9) $data = [
            'conclusions_ca' => $content,
            'conclusions_ca_saved_at' => now(),
            'conclusions_ca_saved_by_user_id' => auth()->id(),
        ];
        if (! empty($data) && (int) $sequence !== 9) {
            $data['instruction_grille_last_edited_at'] = now();
            $data['instruction_grille_last_edited_by_user_id'] = auth()->id();
        }
        if (!empty($data)) {
            $dossier->update($data);
        }
        return redirect()->back()->with('success', 'Enregistrement effectué.');
    }

    private function parseCriteres(Critere $critere){
        $dsc = [];
        $note = 0;
        foreach($critere->souscriteres as $sc){
            $r = $sc->reponse;
            $ch = $r?->choice;
            if($r){
                $note += $r->value;
            }
            $dsc[] = [
                'id'=>$sc->id,
                'name'=>$sc->name,
                'critereId'=>$sc->critere_id,
                'sequence'=>$sc->sequence,
                'default'=>$sc->default,
                'note'=>$r?$r->note:0,
                'reponse'=>$r?[
                        'id'=>$r->id,
                        'dossierId'=>$r->dossier_id,
                        'critereId'=>$r->critere_id,
                        'choiceId'=>$r->choice_id,
                        'note'=>$r->note,
                        'choice'=>[
                            'id'=>$ch->id,
                            'valeur'=>$ch->valeur,
                            'note'=>$ch->note,
                            'critereId'=>$sc->critere_id,
                        ]

                ]:[],
            ];
        }

        return [
            'id'=>$critere->id,
            'name'=>$critere->name,
            'note'=>$note,
            'souscriteres'=>$dsc,
        ];
    }
}
