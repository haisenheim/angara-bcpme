<?php

namespace App\Http\Controllers\Ca;

use App\Http\Controllers\Concerns\BuildsEntrepriseQuestionnaireResults;
use App\Http\Controllers\Controller;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Banque;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\Forme;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use App\Models\Person;
use App\Models\QuestionAnswer;
use App\Models\QuestionSousCritere;
use App\Models\Region;
use App\Models\Tier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CompanyController extends Controller
{
    use BuildsEntrepriseQuestionnaireResults;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('/Ca/Companies/index');
    }

    public function getProspects()
    {
        //
        return view('/Ca/Companies/prospects');
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    private function baseQuery()
    {
        return Entreprise::where('prospect', 0)->where('agence_id', auth()->user()->agence_id);
    }

    public function fetchStats(Request $request)
    {
        $base = $this->baseQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
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
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

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
            'region_id' => $request->input('region_id'),
            'taille' => $request->input('taille'),
            'forme_id' => $request->input('forme_id'),
            'caractere' => $request->input('caractere'),
        ];
    }

    private function applyFilters($query, array $filters)
    {
        if (! empty($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }
        if (! empty($filters['taille'])) {
            $query->where('taille', $filters['taille']);
        }
        if (! empty($filters['forme_id'])) {
            $query->where('forme_id', $filters['forme_id']);
        }
        if (! empty($filters['caractere'])) {
            $query->where('caractere', $filters['caractere']);
        }

        return $query;
    }

    public function fetchFilterOptions()
    {
        return response()->json([
            'regions' => Region::orderBy('name')->get(['id', 'name']),
            'formes' => Forme::orderBy('name')->get(['id', 'name']),
        ]);
    }

    public function fetchProspects()
    {
        $items = $this->prospectsQuery()->get();
        $items = EntrepriseListResource::collection($items);

        return response()->json($items);
    }

    private function prospectsQuery()
    {
        return Entreprise::query()
            ->where('prospect', 1)
            ->whereNotNull('prospect_submitted_at')
            ->where('agence_id', auth()->user()->agence_id);
    }

    public function fetchProspectsStats(Request $request)
    {
        $base = $this->prospectsQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $stats = [
            'total' => (clone $query)->count(),
            'par_taille' => (clone $query)->select('taille', DB::raw('count(*) as count'))->groupBy('taille')->pluck('count', 'taille')->toArray(),
            'formelles' => (clone $query)->where('caractere', 'Formel')->count(),
            'informelles' => (clone $query)->where('caractere', 'Informel')->count(),
        ];

        return response()->json($stats);
    }

    public function fetchProspectsPaginated(Request $request)
    {
        $draw = (int) $request->input('draw', 1);
        $start = (int) $request->input('start', 0);
        $length = (int) $request->input('length', 15);
        $length = min(max($length, 5), 100);
        $search = trim($request->input('search.value', ''));

        $base = $this->prospectsQuery();
        $filters = $this->parseFilters($request);
        $query = $this->applyFilters($base->clone(), $filters);

        $recordsTotal = $this->prospectsQuery()->count();
        $recordsFiltered = $query->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('rccm', 'like', "%{$search}%")
                    ->orWhere('niu', 'like', "%{$search}%")
                    ->orWhere('manager', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
            $recordsFiltered = $query->count();
        }

        $items = $query->orderBy('created_at', 'DESC')->skip($start)->take($length)->get();
        $resolved = EntrepriseListResource::collection($items)->toArray($request);
        $data = $resolved['data'] ?? $resolved;
        $data = array_values($data);

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $formes = Forme::all();

        return view('/Ca/Companies/create', compact('formes'));
    }

    private function parse($eng, $id)
    {

        $data = [
            'id' => $eng->id,
            'name' => $eng->name,
            'montant' => $eng->montant ?? 0,
            'encours_montant' => $eng->encours_montant ?? 0,
            'encours_impaye' => $eng->encours_impaye ?? 0,
            'sollicite_montant' => $eng->sollicite_montant ?? 0,
            'variation' => $eng->variation,
            'parent_id' => $eng->parent_id,
            'is_title' => $eng->is_title,
            'is_leaf' => $eng->is_leaf,
            'niveau' => $eng->niveau,
        ];
        if ($data['is_leaf']) {
            $elts = EngagementEntreprise::with('banque')->where('engagement_id', $eng->id)->where('entreprise_id', $id)->get();
            $data['encours_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_montant ?? 0);
            }, 0);
            $data['sollicite_montant'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->sollicite_montant ?? 0);
            }, 0);
            $data['encours_impaye'] = $elts->reduce(function ($carry, $item) {
                return $carry + ($item->encours_impaye ?? 0);
            }, 0);
            $data['elts'] = $elts->map(function ($elt) {
                return [
                    'banque_name' => $elt->banque?->name ?? '—',
                    'encours_montant' => $elt->encours_montant ?? 0,
                    'encours_impaye' => $elt->encours_impaye ?? 0,
                    'encours_dt_validite' => $elt->encours_dt_validite ?? '—',
                    'sollicite_montant' => $elt->sollicite_montant ?? 0,
                    'sollicite_dt_validite' => $elt->sollicite_dt_validite ?? '—',
                ];
            })->values()->toArray();
            $data['variation'] = $data['sollicite_montant'] - $data['encours_montant'];

        } else {
            $data['children'] = $eng->children->map(function ($child) use ($id) {
                return $this->parse($child, $id);
            });
            foreach ($data['children'] as $child) {
                $data['encours_montant'] += $child['encours_montant'];
                $data['sollicite_montant'] += $child['sollicite_montant'];
                $data['encours_impaye'] += $child['encours_impaye'];
                $data['variation'] += $child['variation'];
            }
        }

        return $data;
    }

    public function getEngagementReport($token)
    {
        $entreprise = Entreprise::where('token', $token)->first();
        if ($entreprise) {
            $engagements = Engagement::where('parent_id', 0)->get();
            $data = [];
            foreach ($engagements as $eng) {
                $data[] = $this->parse($eng, $entreprise->id);
            }
            // dd($data);

            $engagements = $data;
            $banques = Banque::all();

            // $engagements = EngagementEntreprise::where('entreprise_id',$entreprise->id)->get();
            return view('Ca.Companies.engagement_report', compact('engagements', 'entreprise', 'banques'));
        } else {
            return back();
        }

    }

    public function saveProgramme(Request $request)
    {
        $data = $request->all();
        $data['token'] = sha1(time().rand(1, 100));
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        Dossier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'programme_id' => $request->programme_id,
            ],
            $data
        );
        Session::flash('success', 'Enregistrement effectué avec succès!');

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $token)
    {
        $item = Entreprise::query()
            ->where('token', $token)
            ->where('agence_id', auth()->user()->agence_id)
            ->where(function ($q) {
                $q->where('prospect', 0)
                    ->orWhere(function ($q2) {
                        $q2->where('prospect', 1)->whereNotNull('prospect_submitted_at');
                    });
            })
            ->with([
                'forme',
                'filiere',
                'branche',
                'produit',
                'produits.filiere',
                'produits.branche',
                'appuis.type',
                'village',
                'quartier',
                'arrondissement',
                'departement',
                'region',
                'agence.representation',
                'tiers.person',
                'tiers.company.produit',
                'dossiers.programme',
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'promuClientUser',
                'prospectRejectedUser',
                'dossierEntreeRelation.qualificationUser',
                'dossierEntreeRelation.programmesSubmittedBy',
                'dossierEntreeRelation.qualificationValidatedByAgenceUser',
                'dossierEntreeRelation.instructionValidatedBy',
                'dossierEntreeRelation.programmeSelections.programme',
                'dossierEntreeRelation.programmeSelections.instructionDossier',
            ])
            ->firstOrFail();

        $mr = $this->buildQuestionnaireResults($item);
        $checklist = $item->piecesExigiblesChecklist();

        if ($item->prospect) {
            $item->load([
                'juridiqueAvisUser',
                'conformiteAvisUser',
                'arrondissement',
                'departement',
                'region',
                'forme',
                'agence.representation',
                'produit',
                'produits',
                'appuis.type',
                'reponses.question',
                'reponses.choice',
            ]);

            return view('Ca.Companies.show_prospect', compact('item', 'mr', 'checklist'));
        }

        return view('Ca.Companies.show', compact('item', 'mr', 'checklist'));
    }

    public function saveTiersPhysique(Request $request)
    {
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 100));
        $data['user_id'] = auth()->user()->id;
        $person = Person::where('niu', $data['niu'])->where('phone', $data['phone'])->first();
        if (! $person) {
            $person = Person::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'person_id' => $person->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function saveTiersMorale(Request $request)
    {
        // dd($request->all());
        $token = $request->token;
        $data = $request->except('lien', 'entreprise_id', 'token');
        $data['token'] = sha1(time().rand(1, 9999));
        $data['prospect'] = 1;
        $data['user_id'] = auth()->user()->id;
        $data['agence_id'] = auth()->user()->agence_id;
        $data['representation_id'] = auth()->user()->representation_id;
        $entreprise = Entreprise::where('phone', $data['phone'])->orWhere('email', $data['email'])->first();
        if (! $entreprise) {
            $entreprise = Entreprise::create($data);
        }

        Tier::updateOrCreate(
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
            ],
            [
                'entreprise_id' => $request->entreprise_id,
                'company_id' => $entreprise->id,
                'lien' => $request->lien,
            ]
        );

        Session::flash('success', 'Enregistrement effectué avec succès!');

        return redirect(route('ca.entreprises.show', $token));
    }

    public function createQuestionnaire(string $token)
    {
        //
        $item = Entreprise::where('token', $token)->first();
        if (! $item) {
            return back();
        }
        $criteres = QuestionSousCritere::all();

        return view('/Ca/Companies/questionnaire', compact('item', 'criteres'));
    }

    public function saveQuestionnaire(Request $request)
    {
        // dd($request->choices[1]);
        $choices = $request->choices;
        foreach ($choices as $choice) {
            QuestionAnswer::updateOrCreate([
                'entreprise_id' => $choice['entreprise_id'],
                'choice_id' => $choice['choice_id'],
            ], $choice);
        }

        // Session::flash('success','Enregistrement effectué avec succès!');
        return response()->json('ok');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
