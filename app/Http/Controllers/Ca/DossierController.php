<?php

namespace App\Http\Controllers\Ca;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\IndicateurFinancier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
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
                $q->whereHas('entreprise', fn($e) => $e->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('programme', fn($p) => $p->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('analyste', fn($a) => $a->where('name', 'like', "%{$search}%"));
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->with(['entreprise', 'programme', 'analyste', 'agence'])->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
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
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (!empty($filters['programme_id'])) $query->where('programme_id', $filters['programme_id']);
        if (!empty($filters['analyste_id'])) $query->where('analyste_id', $filters['analyste_id']);
        return $query;
    }

    public function fetchFilterOptions()
    {
        $agenceId = auth()->user()->agence_id;
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = Dossier::where('agence_id', $agenceId)->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
        $analystes = \App\Models\User::whereIn('id', $analysteIds)->get(['id', 'name']);
        return response()->json(['programmes' => $programmes, 'analystes' => $analystes]);
    }

    public function show($token){

        $item = Dossier::where('token',$token)->first();
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

        return view('Ca/Dossiers/show',compact('item','indicateurs','criteres','sme','banques'));
    }

    public function getGrilleAnalyse($token)
    {
        $item = Dossier::where('token', $token)->first();
        if (!$item || $item->agence_id != auth()->user()->agence_id) {
            return back();
        }
        return view('Ca/Dossiers/analyse_critique', compact('item'));
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
        if($sequence==7) $data = ['conclusions_gestionnaire'=>$content];
        if($sequence==8) $data = ['conclusions_ca'=>$content];
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
