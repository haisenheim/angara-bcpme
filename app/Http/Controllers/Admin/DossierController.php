<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\FichierType;
use App\Services\Inertia\DossierInstructionPagePresenter;
use App\Services\Inertia\DossierWorkspacePresenter;
use App\Services\Inertia\InstructionDossierListPresenter;
use App\Services\InstructionDossierConsultationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    use StoresDossierPieces;

    //
    public function index(Request $request)
    {
        // Même périmètre que fetchAll (tous les dossiers) ; la recherche rapide de l'ancienne grille devient une recherche serveur.
        return \Inertia\Inertia::render('Dossiers/Index', app(InstructionDossierListPresenter::class)->present($request, Dossier::query(), Dossier::query(), [
            'showRoute' => 'admin.dossiers.show',
            'dashboard' => 'admin.dashboard',
            'title' => 'Dossiers d\'instruction',
            'subtitle' => 'Liste de tous les dossiers d\'instruction.',
            'columns' => ['programme', 'signataire', 'entreprise', 'gestionnaire', 'analyste', 'agence', 'direction', 'secteur', 'statut', 'date'],
            'filters' => [],
            'stats' => false,
            'searchPlaceholder' => 'Entreprise, programme, analyste, gestionnaire…',
        ]));
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token)
    {
        $item = Dossier::query()
            ->where('token', $token)
            ->with(array_merge(DossierInstructionPagePresenter::RELATIONS, ['programme']))
            ->firstOrFail();

        // L'ancienne fiche lisait la grille via le microservice (localhost:8080) : les mêmes données sont lues
        // en base (DossierInstructionShowPresenter). Les saisies restent celles de l'écran Blade (routes admin).
        $ws = app(DossierWorkspacePresenter::class);
        $notation = $ws->notation($item);
        $props = $ws->fiche($item, ['route' => 'admin', 'label' => 'Administrateur', 'title' => 'Administrateur'], auth()->user(), true, [
            'store' => 'admin.dossier.pieces.store',
            'update' => 'admin.dossier.pieces.update',
            'destroy' => 'admin.dossier.pieces.destroy',
            'upload' => true,
            'manageOwn' => true,
        ], 'admin.dossiers.index', 'Dossiers d\'instruction', 'admin.entreprises.show');
        $props['workspace'] = $ws->consultation($item, $notation, [
            'choicesUrl' => route('admin.instruction.critere.choices'),
            'reponseUrl' => route('admin.instruction.critere.reponse'),
            'sendProgramme' => false,
        ], route('admin.entreprise.dossier.analyse'));
        $props['links']['engagements'] = $item->entreprise ? ($notation['engagementGridUrl'] ?? null) : null;

        return \Inertia\Inertia::render('Dossiers/Show', $props);
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'admin.dossiers.show', $dossier);
    }

    public function destroyDossierPiece(string $token, int $piece)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceDestroy($dossier, $fichier, 'admin.dossiers.show', $dossier);
    }

    public function updateDossierPiece(Request $request, string $token, int $piece)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();
        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceUpdate($request, $dossier, $fichier, 'admin.dossiers.show', $dossier);
    }
}
