<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\FichierType;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    use StoresDossierPieces;

    public function index()
    {
        return view('/Gestionnaire/Dossiers/index');
    }

    public function fetchAll(){
        $items = $this->baseQuery()->orderBy('created_at','DESC')->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    private function baseQuery()
    {
        return Dossier::where('gestionnaire_id', auth()->user()->id);
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
                $q->whereHas('entreprise', function ($e) use ($search) {
                    $e->where('name', 'like', "%{$search}%");
                })->orWhereHas('programme', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                })->orWhereHas('instructionProgrammes.programme', function ($p) use ($search) {
                    $p->where('name', 'like', "%{$search}%");
                })->orWhereHas('analyste', function ($a) use ($search) {
                    $a->where('name', 'like', "%{$search}%");
                });
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->with(['entreprise','programme','instructionProgrammes.programme','analyste','agence'])->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
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
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = Dossier::where('gestionnaire_id', auth()->user()->id)->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
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

    public function getGrilleAnalyse($token)
    {
        $item = Dossier::query()
            ->where('token', $token)
            ->where('gestionnaire_id', auth()->id())
            ->with(['fichiersDossier.type', 'fichiersDossier.uploadedBy'])
            ->first();
        if (! $item) {
            return back();
        }

        return view('Gestionnaire/Dossiers/analyse_critique', compact('item'));
    }

    public function setAnalyse(Request $request)
    {
        $sequence = $request->sequence;
        $content = $request->content;
        $dossier_id = $request->dossier_id;
        $dossier = Dossier::where('id', $dossier_id)->where('gestionnaire_id', auth()->user()->id)->first();
        if (!$dossier) return back();

        if ($sequence == 8) {
            $dossier->update(['conclusions_gestionnaire' => $content]);
        }
        return redirect()->back()->with('success', 'Recommandations enregistrées.');
    }

    public function show($token){
        $item = Dossier::query()
            ->where('token', $token)
            ->where('gestionnaire_id', auth()->user()->id)
            ->with([
                'entreprise',
                'programme',
                'instructionProgrammes.programme',
                'analyste',
                'agence',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
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

        $fichierTypes = FichierType::query()->orderBy('name')->get(['id', 'name']);

        return view('Gestionnaire/Dossiers/show', compact('item', 'indicateurs', 'criteres', 'sme', 'banques', 'instructionConsultation', 'fichierTypes'));
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('gestionnaire_id', auth()->id())
            ->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'gestionnaire.dossiers.show', $dossier);
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
