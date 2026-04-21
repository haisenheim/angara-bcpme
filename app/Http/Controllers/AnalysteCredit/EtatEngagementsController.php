<?php

namespace App\Http\Controllers\AnalysteCredit;

use App\Http\Controllers\Controller;
use App\Models\Dossier;
use App\Models\Entreprise;
use Illuminate\Http\Request;

class EtatEngagementsController extends Controller
{
    public function edit(string $token)
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $dossiers = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->with(['programme'])
            ->orderByDesc('id')
            ->get();

        if ($dossiers->isEmpty()) {
            abort(403);
        }

        return view('AnalysteCredit.etat_engagements.edit', [
            'entreprise' => $entreprise,
            'dossiers' => $dossiers,
        ]);
    }

    public function update(Request $request, string $token)
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();

        $dossiers = Dossier::query()
            ->where('entreprise_id', $entreprise->id)
            ->where('reng_analyste_credit_user_id', auth()->id())
            ->whereNotNull('juridique_submitted_to_engagements_at')
            ->orderByDesc('id')
            ->get(['id', 'token']);

        if ($dossiers->isEmpty()) {
            abort(403);
        }

        $rules = [];
        foreach ($dossiers as $d) {
            $rules['etat.'.$d->token] = 'nullable|string|max:65535';
        }

        $validated = $request->validate($rules);

        foreach ($dossiers as $d) {
            $html = $validated['etat'][$d->token] ?? null;
            $dossier = Dossier::query()
                ->whereKey($d->id)
                ->where('reng_analyste_credit_user_id', auth()->id())
                ->firstOrFail();
            $dossier->reng_etat_engagements_client = $html;
            $dossier->save();
        }

        return redirect()
            ->route('analyste-credit.entreprises.etat-engagements', $token)
            ->with('success', 'État des engagements du client enregistré.');
    }
}
