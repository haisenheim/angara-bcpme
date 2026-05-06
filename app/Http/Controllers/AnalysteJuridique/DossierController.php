<?php

namespace App\Http\Controllers\AnalysteJuridique;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Soumission de l’avis analyste juridique au responsable juridique (horodatage + identité).
     *
     * Verrouillage : conformément à la règle « une fois le dossier soumis au maillon suivant,
     * il devient impossible pour l'acteur de l'étape précédente de modifier son contenu », l'avis
     * est figé dès la première soumission au RJU. Pour le rouvrir, le RJU doit explicitement
     * rejeter l'avis (cf. workflow 2 — rejet intermédiaire).
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

        if ($dossier->isJuridiqueAnalysteAvisSubmittedToReju()) {
            return redirect()
                ->route('analyste-juridique.dossiers.show', $token)
                ->withErrors(['juridique_analyste_avis' => 'Votre avis a déjà été transmis au responsable juridique : il n’est plus modifiable depuis votre espace.']);
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

        $now = now();
        $dossier->juridique_analyste_avis = $validated['juridique_analyste_avis'];
        $dossier->juridique_analyste_avis_saved_at = $now;
        $dossier->juridique_analyste_avis_saved_by_user_id = auth()->id();
        $dossier->juridique_analyste_submitted_to_reju_at = $now;
        $dossier->juridique_analyste_submitted_to_reju_by_user_id = auth()->id();
        // Réouverture suite à rejet : on réinitialise les marqueurs de rejet (l'historique reste tracé en timeline).
        $dossier->juridique_analyste_rejected_at = null;
        $dossier->juridique_analyste_rejected_by_user_id = null;
        $dossier->juridique_analyste_reject_motif = null;
        $dossier->save();

        return redirect()
            ->route('analyste-juridique.dossiers.show', $token)
            ->with('success', 'Votre avis a été transmis au responsable juridique. Il est désormais figé jusqu’à l’étape suivante.');
    }
}
