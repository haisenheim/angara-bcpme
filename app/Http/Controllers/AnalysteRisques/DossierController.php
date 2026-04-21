<?php

namespace App\Http\Controllers\AnalysteRisques;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use Illuminate\Http\Request;

class DossierController extends Controller
{
    public function saveDraft(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('rerx_analyste_risques_user_id', auth()->id())
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('analyste-risques.dossiers.show', $token)
                ->withErrors(['draft' => 'Le dossier a déjà été soumis au responsable risques.']);
        }

        $validated = $request->validate([
            'rerx_analyse_risques' => 'nullable|string|max:65535',
            'rerx_analyste_risques_avis' => 'nullable|string|max:65535',
        ]);

        $dossier->fill($validated);
        $dossier->save();

        return redirect()
            ->route('analyste-risques.dossiers.show', $token)
            ->with('success', 'Brouillon enregistré.');
    }

    public function submitToRerx(Request $request, string $token)
    {
        $dossier = Dossier::query()
            ->where('token', $token)
            ->where('rerx_analyste_risques_user_id', auth()->id())
            ->whereNotNull('reng_submitted_to_risques_at')
            ->firstOrFail();

        if ($dossier->isRerxAnalysteRisquesSubmittedToRerx()) {
            return redirect()
                ->route('analyste-risques.dossiers.show', $token)
                ->withErrors(['submit' => 'Ce dossier a déjà été transmis au responsable risques.']);
        }

        $validated = $request->validate([
            'rerx_analyse_risques' => 'required|string|max:65535',
            'rerx_analyste_risques_avis' => 'required|string|max:65535',
        ]);

        $dossier->rerx_analyse_risques = $validated['rerx_analyse_risques'];
        $dossier->rerx_analyste_risques_avis = $validated['rerx_analyste_risques_avis'];

        if (! $dossier->hasRerxAnalysteRisquesBundleFilledForSubmit()) {
            return redirect()
                ->route('analyste-risques.dossiers.show', $token)
                ->withErrors(['submit' => 'Renseignez l’analyse des risques et votre avis.'])
                ->withInput();
        }

        $dossier->rerx_analyste_risques_submitted_at = now();
        $dossier->rerx_analyste_risques_submitted_by_user_id = auth()->id();
        $dossier->save();

        return redirect()
            ->route('analyste-risques.dossiers.show', $token)
            ->with('success', 'Dossier transmis au responsable risques.');
    }
}
