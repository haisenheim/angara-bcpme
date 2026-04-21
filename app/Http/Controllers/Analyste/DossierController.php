<?php

namespace App\Http\Controllers\Analyste;

use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
use App\Models\Instruction\Engagement;
use App\Models\Instruction\EngagementEntreprise;
use App\Models\Instruction\IndicateurFinancier;
use App\Models\User;
use App\Services\DossierInstructionShowPresenter;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class DossierController extends Controller
{
    //
    public function index()
    {
        //
        return view('/Analyste/Dossiers/index');
    }

    public function fetchAll()
    {
        $items = $this->baseQuery()->orderBy('created_at', 'DESC')->get();
        $items = DossierListResource::collection($items);

        return response()->json($items);
    }

    private function baseQuery()
    {
        $user = auth()->user();
        if ($user instanceof User && $user->isAnalysteFinancierNational()) {
            return Dossier::query();
        }

        return Dossier::where('analyste_id', auth()->user()->id);
    }

    private function authorizeAnalysteDossier(?Dossier $dossier): void
    {
        if (! $dossier) {
            abort(404);
        }
        $user = auth()->user();
        if ($user instanceof User && $user->isAnalysteFinancierNational()) {
            return;
        }
        if ((int) ($dossier->analyste_id ?? 0) !== (int) auth()->id()) {
            abort(403);
        }
    }

    /**
     * Soumission au responsable exploitation après instruction terminée (débloque avis / validation côté REXP).
     */
    public function soumettreExploitation(Dossier $dossier)
    {
        $this->authorizeAnalysteDossier($dossier);

        if (! $dossier->analyste_id) {
            return redirect()
                ->back()
                ->withErrors(['submission' => 'Le dossier doit avoir un analyste affecté avant soumission au responsable exploitation.']);
        }

        $user = auth()->user();
        $national = $user instanceof User && $user->isAnalysteFinancierNational();
        if (! $national && (int) $dossier->analyste_id !== (int) auth()->id()) {
            abort(403);
        }

        if ($dossier->isInstructionSubmittedToExploitation()) {
            return redirect()->back()->with('info', 'Ce dossier a déjà été soumis au responsable exploitation.');
        }

        $dossier->exploitation_instruction_submitted_at = now();
        $dossier->exploitation_instruction_submitted_by_user_id = auth()->id();
        $dossier->save();

        return redirect()->back()->with('success', 'Dossier soumis au responsable exploitation pour validation.');
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
                    ->orWhereHas('analyste', fn ($a) => $a->where('name', 'like', "%{$search}%"));
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
        if (! empty($filters['programme_id'])) {
            $query->where('programme_id', $filters['programme_id']);
        }
        if (! empty($filters['analyste_id'])) {
            $query->where('analyste_id', $filters['analyste_id']);
        }

        return $query;
    }

    public function fetchFilterOptions()
    {
        $programmes = \App\Models\Programme::orderBy('name')->get(['id', 'name']);
        $analysteIds = $this->baseQuery()->whereNotNull('analyste_id')->distinct()->pluck('analyste_id');
        $analystes = User::query()->whereIn('id', $analysteIds)->orderBy('name')->get(['id', 'name']);

        return response()->json(['programmes' => $programmes, 'analystes' => $analystes]);
    }

    public function getGrilleAnalyse($token)
    {
        $item = Dossier::where('token', $token)->firstOrFail();
        $this->authorizeAnalysteDossier($item);

        return view('Analyste/Dossiers/analyse_critique', compact('item'));
    }

    public function setAnalyse()
    {
        // dd(request()->all());
        $sequence = request('sequence');
        $content = request('content');
        $dossier_id = request('dossier_id');
        $this->authorizeAnalysteDossier(Dossier::query()->find($dossier_id));
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
        if ($sequence == 7) {
            $data = ['conclusions_analyste' => $content];
        }

        // dd($data);

        Dossier::updateOrCreate(['id' => $dossier_id], $data);

        return redirect()->back();

    }

    public function loadDsf(Request $request)
    {

        $dossier_id = $request->dossier_id;
        $this->authorizeAnalysteDossier(Dossier::query()->find($dossier_id));
        // $filename = $request->file('upload')->getClientOriginalName();
        $getfilePath = $request->file('upload')->getRealPath();
        $client = new Client;

        $msgServiceIndisponible = 'Le service d’analyse des fichiers DSF est momentanément indisponible : le microservice chargé du traitement du fichier ne répond pas (il est probablement arrêté ou inaccessible). '
            .'Veuillez réessayer plus tard ou contacter l’administrateur technique pour vérifier que ce service est bien démarré.';

        $msgFichierInvalide = 'Le fichier transmis ne correspond pas au format ou à la structure attendue par le service d’analyse DSF. '
            .'Vérifiez l’extension, le modèle de classeur (feuilles, colonnes) et que les données respectent le gabarit prévu. '
            .'Si besoin, demandez un modèle ou une procédure d’import à votre référent métier.';

        $msgErreurTraitementServeur = 'Une erreur s’est produite lors du traitement du fichier sur le service d’analyse DSF (réponse du microservice). '
            .'Ce message est distinct d’une simple coupure réseau : réessayez plus tard ou contactez l’administrateur technique si le problème persiste.';

        try {
            $resp = $client->request('POST', 'http://localhost:8080/dossier', [
                'multipart' => [
                    [
                        'name' => 'upload',
                        'contents' => fopen($getfilePath, 'r'),
                    ],
                    [
                        'name' => 'dossier_id',
                        'contents' => $dossier_id,
                    ],
                    [
                        'name' => 'annee',
                        'contents' => $request->annee,
                    ],
                ],
            ]);

            $data = $resp->getBody()->getContents();
            $inds = json_decode($data, true);
            if (! is_array($inds)) {
                Session::flash('error', $msgFichierInvalide);

                return back();
            }
            if ($this->dsfResponseIndiqueFichierInvalide($inds)) {
                Session::flash('error', $msgFichierInvalide);

                return back();
            }
            foreach ($inds as $ind) {
                IndicateurFinancier::updateOrCreate(
                    ['dossier_id' => $dossier_id, 'annee' => $ind['annee']], $ind
                );
            }

            Session::flash('success', 'Enregistrement effectué avec succès!');
        } catch (ConnectException $e) {
            Session::flash('error', $msgServiceIndisponible);
        } catch (RequestException $e) {
            $status = $e->hasResponse() ? $e->getResponse()->getStatusCode() : 0;
            if (in_array($status, [400, 406, 415, 422], true)) {
                Session::flash('error', $msgFichierInvalide);
            } elseif ($status === 413) {
                Session::flash('error', 'Le fichier est trop volumineux pour être accepté par le service d’analyse. Réduisez la taille ou fractionnez les données, puis réessayez.');
            } elseif ($status >= 500 && $status < 600) {
                Session::flash('error', $msgErreurTraitementServeur);
            } elseif ($status >= 400 && $status < 500) {
                Session::flash('error', $msgFichierInvalide);
            } else {
                Session::flash('error', 'Le service d’analyse des fichiers DSF n’a pas pu traiter votre demande. Réessayez ou contactez l’administrateur technique.');
            }
        } catch (GuzzleException $e) {
            Session::flash('error', 'Le service d’analyse des fichiers DSF n’a pas pu traiter votre demande (échange réseau interrompu ou délai dépassé). '
                .'Vérifiez que le microservice est disponible, puis réessayez.');
        }

        return back();
    }

    /**
     * Réponse JSON du microservice signalant un rejet de fichier (format / structure) plutôt qu’une liste d’indicateurs.
     *
     * @param  array<string, mixed>  $payload
     */
    private function dsfResponseIndiqueFichierInvalide(array $payload): bool
    {
        if ($payload === []) {
            return false;
        }
        if (array_is_list($payload)) {
            return false;
        }
        if (array_key_exists('error', $payload) || array_key_exists('errors', $payload)) {
            return true;
        }
        if (array_key_exists('success', $payload) && $payload['success'] === false) {
            return true;
        }

        return false;
    }

    public function show($token)
    {

        $item = Dossier::query()
            ->with(['exploitationAnalysteAssignedBy', 'exploitationInstructionSubmittedBy'])
            ->where('token', $token)
            ->firstOrFail();
        $this->authorizeAnalysteDossier($item);

        return view('Analyste/Dossiers/show', app(DossierInstructionShowPresenter::class)->presentForDossier($item));
    }

    public function show_($token)
    {

        $item = Dossier::where('token', $token)->first();
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        dd(json_decode($resp->body(), true));
        $resp = json_decode($resp->body(), true);
        $dossier = $resp['dossier'];
        // dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        return view('Analyste/Dossiers/show',compact('item','dossier','entreprise','engagements','indicateurs','criteres','sme','banques'));
    }
}
