<?php

namespace App\Http\Controllers\Regional;

use App\Http\Controllers\Concerns\StoresDossierPieces;
use App\Http\Controllers\Controller;
use App\Http\Resources\DossierListResource;
use App\Models\Dossier;
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
        return view('/Regional/Dossiers/index');
    }

    public function fetchAll(){
        $items = Dossier::orderBy('created_at','DESC')->where('representation_id',auth()->user()->representation_id)->get();
        $items = DossierListResource::collection($items);
        return response()->json($items);
    }

    public function show($token){

        $item = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
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
        $dossier = $resp['dossier'];
        //dd($dossier);
        $entreprise = $resp['entreprise'];
        $engagements = $resp['engagements'];
        $criteres = $resp['criteres'];
        $indicateurs = $dossier['indicateurs'];
        $banques = $resp['banques'];
        $sme = $resp['sme'];

        $fichierTypes = FichierType::query()->orderBy('name')->get(['id', 'name']);

        return view('/Regional/dossiers/show', compact('item', 'dossier', 'entreprise', 'engagements', 'indicateurs', 'criteres', 'sme', 'banques', 'instructionConsultation', 'fichierTypes'));
    }

    public function storeDossierPiece(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('representation_id', auth()->user()->representation_id)
            ->firstOrFail();

        return $this->completeDossierPieceUpload($request, $dossier, 'regional.dossiers.show', $dossier);
    }
}
