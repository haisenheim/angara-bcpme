<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Http\Resources\EntrepriseListResource;
use App\Models\Dossier;
use App\Models\Entreprise;
use App\Models\FichierType;
use App\Services\InstructionDossierConsultationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DossierController extends Controller
{
    use StoresDossierPieces;

    //
    public function index()
    {
        //
        return view('/Admin/Dossiers/index');
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token){

        $item = Dossier::query()
            ->where('token', $token)
            ->with([
                'instructionProgrammes.programme',
                'chefFiliereSubmittedToAgenceBy',
                'instructionAgenceValidatedBy',
                'instructionAgenceRejectedBy',
                'fichiersDossier.type',
                'fichiersDossier.uploadedBy',
            ])
            ->firstOrFail();
        $instructionConsultation = app(InstructionDossierConsultationService::class)->build($item);
        $resp = Http::get('http://localhost:8080/entreprise/dossier?id='.$item->id);
        $resp = json_decode($resp->body(),true);
        //dd($resp);
        $dossier = $resp['dossier'];
        //dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        $fichierTypes = FichierType::query()->orderBy('name')->get(['id', 'name']);

        return view('/Admin/Dossiers/show', compact('item', 'dossier', 'entreprise', 'engagements', 'indicateurs', 'criteres', 'sme', 'banques', 'instructionConsultation', 'fichierTypes'));
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()->where('token', $token)->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'admin.dossiers.show', $dossier);
    }
}
