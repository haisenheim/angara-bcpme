<?php

namespace App\Http\Controllers\AnalysteJuridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Soumission de l’avis analyste juridique au responsable juridique (horodatage + identité).
     */
    public function submitToReju(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('juridique_analyste_user_id', auth()->id())
            ->whereNotNull('juridique_instruction_submitted_at')
            ->firstOrFail();

        if ($dossier->isSubmittedToEngagementsFromJuridique()) {
            return redirect()
                ->route('analyste-juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_avis' => 'Le dossier a déjà été transmis au responsable engagements : l’avis juridique n’est plus modifiable.']);
        }

        $validated = $request->validate([
            'juridique_analyste_avis' => 'required|string|max:65535',
        ]);

        if (strlen(trim(strip_tags($validated['juridique_analyste_avis']))) === 0) {
            return redirect()
                ->route('analyste-juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_avis' => 'Renseignez le contenu de votre avis.'])
                ->withInput();
        }

        $alreadyTransmisAuReju = $dossier->isJuridiqueAnalysteAvisSubmittedToReju();

        $dossier->juridique_analyste_avis = $validated['juridique_analyste_avis'];
        $dossier->juridique_analyste_avis_saved_at = now();
        $dossier->juridique_analyste_avis_saved_by_user_id = auth()->id();

        if (! $alreadyTransmisAuReju) {
            $dossier->juridique_analyste_submitted_to_reju_at = now();
            $dossier->juridique_analyste_submitted_to_reju_by_user_id = auth()->id();
        }

        $dossier->save();

        $msg = $alreadyTransmisAuReju
            ? 'Votre avis a été mis à jour. Il reste modifiable tant que le responsable juridique n’a pas transmis le dossier au responsable engagements.'
            : 'Votre avis a été transmis au responsable juridique.';

        return redirect()
            ->route('analyste-juridique.dossiers.show', $token)
            ->with('success', $msg);
    }
}
