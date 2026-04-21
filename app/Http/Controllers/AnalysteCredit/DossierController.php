<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    /**
     * Sauvegarde intermédiaire (sans verrouiller la soumission au responsable engagements).
     */
    public function saveDraft(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('analyste-credit.dossiers.show', $token)
                ->withErrors(['draft' => 'Le dossier a déjà été soumis au responsable engagements.']);
        }

        $validated = $request->validate([
            'reng_contre_analyse' => 'nullable|string|max:65535',
            'reng_analyste_credit_avis' => 'nullable|string|max:65535',
        ]);

        $dossier->fill($validated);
        $dossier->save();

        return redirect()
            ->route('analyste-credit.dossiers.show', $token)
            ->with('success', 'Brouillon enregistré.');
    }

    /**
     * Soumission au responsable engagements (horodatage + identité).
     */
    public function submitToReng(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->firstOrFail();

        if ($dossier->isRengAnalysteCreditSubmittedToReng()) {
            return redirect()
                ->route('analyste-credit.dossiers.show', $token)
                ->withErrors(['submit' => 'Ce dossier a déjà été transmis au responsable engagements.']);
        }

        $validated = $request->validate([
            'reng_contre_analyse' => 'required|string|max:65535',
            'reng_analyste_credit_avis' => 'required|string|max:65535',
        ]);

        $dossier->reng_contre_analyse = $validated['reng_contre_analyse'];
        $dossier->reng_analyste_credit_avis = $validated['reng_analyste_credit_avis'];

        if (! $dossier->hasRengAnalysteCreditBundleFilledForSubmit()) {
            return redirect()
                ->route('analyste-credit.dossiers.show', $token)
                ->withErrors(['submit' => 'Renseignez la contre-analyse et l’avis.'])
                ->withInput();
        }

        $dossier->reng_analyste_credit_submitted_at = now();
        $dossier->reng_analyste_credit_submitted_by_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('analyste-credit.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable engagements.');
    }
}
