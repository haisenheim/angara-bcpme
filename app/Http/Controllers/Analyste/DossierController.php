<?php

namespace App\Http\Controllers\Analyste;

use App\Helpers\DossierHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Http\Resources\EngagementEntrepriseResource;
use App\Http\Resources\EngagementResource;
use App\Imports\DsfImport;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Instruction\Critere;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use App\Models\Instruction\IndicateurFinancier;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class DossierController extends Controller
{
    //
    public function index()
    {
        //
        return view('/Analyste/Dossiers/index');
    }

    public function fetchAll(){
        $items = $this->baseQuery()->orderBy('created_at', 'DESC')->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    private function baseQuery()
    {
        return Dossier::where('analyste_id', auth()->user()->id);
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
        $analysteId = auth()->user()->id;
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = Dossier::where('analyste_id', $analysteId)->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
        $analystes = \App\Models\User::whereIn('id', $analysteIds)->get(['id', 'name']);
        return response()->json(['programmes' => $programmes, 'analystes' => $analystes]);
    }

    public function getGrilleAnalyse($token){
        $item = Dossier::where('token',$token)->first();
        return view('Analyste/Dossiers/analyse_critique',compact('item'));
    }

    public function setAnalyse(){
        //dd(request()->all());
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $data = [];
        if($sequence==1)
            $data = ['donnees_generales'=>$content];
        if($sequence==2)
            $data = ['analyse_ensemble'=>$content];
        if($sequence==3)
            $data = ['analyse_financiere'=>$content];
        if($sequence==4)
            $data = ['appuis'=>$content];
        if($sequence==5)
            $data = ['analyse_risque'=>$content];
        if($sequence==6)
            $data = ['analyse_rentabilite'=>$content];
        if($sequence==7)
            $data = ['conclusions_analyste'=>$content];

        //dd($data);

        Dossier::updateOrCreate(['id'=>$dossier_id],$data);
        return redirect()->back();

    }



    public function loadDsf(Request $request){

        $dossier_id = $request->dossier_id;
       // $filename = $request->file('upload')->getClientOriginalName();
        $getfilePath  = $request->file('upload')->getRealPath();
        $client = new Client();
        $resp = $client->request('POST','http://localhost:8080/dossier', [
            'multipart' => [
                [
                    'name'     => 'upload',
                    'contents' => fopen($getfilePath, 'r')
                ],
                [
                    'name'     => 'dossier_id',
                    'contents' => $dossier_id,
                ],
                [
                    'name'     => 'annee',
                    'contents' => $request->annee,
                ],
            ],

        ]);

        $data = $resp->getBody()->getContents();
        $inds = json_decode($data,true);
        //dd($inds);
        foreach($inds as $ind){
            IndicateurFinancier::updateOrCreate(
                ['dossier_id'=>$dossier_id,'annee'=>$ind['annee']],$ind
            );
        }

        Session::flash('success','Enregistrement effectué avec succès!');
        return back();

        //return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }

    private function parse($eng,$id){
        $data = [
            'id'=>$eng->id,
            'name'=>$eng->name,
            'montant'=>$eng->montant??0,
            'encours_montant'=>$eng->encours_montant??0,
            'encours_impaye'=>$eng->encours_impaye??0,
            'sollicite_montant'=>$eng->sollicite_montant??0,
            'parent_id'=>$eng->parent_id,
            'is_title'=>$eng->is_title,
            'is_leaf'=>$eng->is_leaf,
            'niveau'=>$eng->niveau,
        ];
        if($data['is_leaf']){
            $elts = EngagementEntreprise::where('engagement_id',$eng->id)->where('entreprise_id',$id)->get();
            $data['encours_montant'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_montant;
            },0);
            $data['sollicite_montant']= $elts->reduce(function($carry,$item){
                return $carry + $item->sollicite_montant;
            },0);
            $data['encours_impaye'] = $elts->reduce(function($carry,$item){
                return $carry + $item->encours_impaye;
            },0);
            $data['elts'] = $elts;

        }else{
            $data['children'] = $eng->children->map(function($child)use($id){
                return $this->parse($child,$id);
            });
            foreach($data['children'] as $child){
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
            }
        }
        return $data;
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

    public function show($token){

        $item = Dossier::where('token',$token)->first();
        $engagements = Engagement::where('parent_id',0)->get();
        $data = [];
        foreach($engagements as $eng){
            $data[] = $this->parse($eng,1);
        }

        $engagements = $data;

        $criteres = Critere::all();
        $id = $item->id;
        $criteres = $criteres->map(function($critere)use($id){
            //$critere->indicateurs = IndicateurFinancier::where('dossier_id',$critere->dossier_id)->where('critere_id',$critere->id)->get();
            $critere->souscriteres = $critere->sousCriteres->map(function($souscritere)use($id){
                $souscritere->reponse = $souscritere->reponses->where('dossier_id',$id)->first();
                //$souscritere->reponse->choice = $souscritere->reponse->choice;
                return $souscritere;
            });
            return $critere;
        });

        $criteres = $criteres->map(function($ct){
            return $this->parseCriteres($ct);
        });

        //dd($criteres);

        //$criteres = $resp['criteres'];

        //dd($criteres);
        $indicateurs = IndicateurFinancier::where('dossier_id',$item->id)->get();
        $banques = Banque::all();
        //dd($item->note);
        $sme = DossierHelper::getSme($item->note); //$resp['sme'];

       // dd($item['variations']);

        return view('Analyste/Dossiers/show',compact('item','indicateurs','criteres','sme','banques','engagements'));
    }

    public function show_($token){

        $item = Dossier::where('token',$token)->first();
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        dd(json_decode($resp->body(),true));
        $resp = json_decode($resp->body(),true);
        $dossier = $resp['dossier'];
        //dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }
}
