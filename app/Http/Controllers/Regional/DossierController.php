<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
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
        // Même périmètre que fetchAll : dossiers de la direction régionale (representation_id).
        $scope = Dossier::query()->where('representation_id', auth()->user()->representation_id);

        return \Inertia\Inertia::render('Dossiers/Index', app(InstructionDossierListPresenter::class)->present($request, $scope, clone $scope, [
            'showRoute' => 'regional.dossiers.show',
            'dashboard' => 'regional.dashboard',
            'title' => 'Dossiers d\'instruction',
            'subtitle' => 'Liste de tous les dossiers d\'instruction de la direction régionale.',
            'columns' => ['programme', 'signataire', 'entreprise', 'gestionnaire', 'agence', 'direction', 'secteur', 'statut', 'date'],
            'filters' => [],
            'stats' => false,
            'searchPlaceholder' => 'Entreprise, programme, analyste, gestionnaire…',
        ]));
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->where('representation_id',auth()->user()->representation_id)->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token)
    {
        $item = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
            ->with(array_merge(DossierInstructionPagePresenter::RELATIONS, ['programme']))
            ->firstOrFail();

        // Consultation : grille et rapport en lecture (les boutons de saisie de l'ancien écran pointaient vers
        // des routes inaccessibles à ce profil), dépôt de pièces conservé.
        $ws = app(DossierWorkspacePresenter::class);
        $notation = $ws->notation($item);
        $props = $ws->fiche($item, ['route' => 'regional', 'label' => 'Responsable régional', 'title' => 'Responsable régional'], auth()->user(), true, [
            'store' => 'regional.dossier.pieces.store',
            'update' => 'regional.dossier.pieces.update',
            'destroy' => 'regional.dossier.pieces.destroy',
            'upload' => true,
            'manageOwn' => true,
        ], 'regional.dossiers.index', 'Dossiers d\'instruction', 'regional.entreprises.show');
        $props['workspace'] = $ws->consultation($item, $notation, null, null);
        $props['links']['engagements'] = $item->entreprise ? ($notation['engagementGridUrl'] ?? null) : null;

        return \Inertia\Inertia::render('Dossiers/Show', $props);
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
            ->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'regional.dossiers.show', $dossier);
    }

    public function destroyDossierPiece(string $token, int $piece)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
            ->firstOrFail();

        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceDestroy($dossier, $fichier, 'regional.dossiers.show', $dossier);
    }

    public function updateDossierPiece(Request $request, string $token, int $piece)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
            ->firstOrFail();

        $fichier = $this->findDossierPiece($dossier, $piece);

        return $this->completeDossierPieceUpdate($request, $dossier, $fichier, 'regional.dossiers.show', $dossier);
    }
}
