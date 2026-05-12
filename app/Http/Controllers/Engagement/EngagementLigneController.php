<?php

namespace App\Http\Controllers\Engagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Engagement\StoreEngagementLigneRequest;
use App\Http\Requests\Engagement\UpdateEngagementLigneRequest;
use App\Models\Engagement\EngagementCategorie;
use App\Models\Engagement\EngagementLigne;
use App\Models\Entreprise;
use App\Services\Engagement\EngagementAccessService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

/**
 * Saisie / mise à jour / suppression des lignes d'engagement.
 *
 * Restreint aux profils habilités par {@see EngagementAccessService}
 * (analyste financier d'exploitation et analyste crédit).
 */
class EngagementLigneController extends Controller
{
    public function __construct(private readonly EngagementAccessService $access) {}

    public function store(StoreEngagementLigneRequest $request, string $token): RedirectResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $this->ensureEditable($entreprise);

        $payload = $request->payload();

        $categorie = EngagementCategorie::query()->findOrFail($payload['engagement_categorie_id']);
        if (! $categorie->is_leaf) {
            return back()->withErrors([
                'engagement_categorie_id' => "Cette catégorie n'accepte pas de saisie directe (sélectionnez un produit).",
            ]);
        }

        EngagementLigne::create(array_merge($payload, [
            'entreprise_id' => $entreprise->id,
            'created_by_user_id' => auth()->id(),
            'updated_by_user_id' => auth()->id(),
        ]));

        Session::flash('success', 'Ligne d’engagement enregistrée.');

        return back();
    }

    public function update(UpdateEngagementLigneRequest $request, string $token, int $ligne): RedirectResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $this->ensureEditable($entreprise);

        $model = EngagementLigne::query()
            ->where('id', $ligne)
            ->where('entreprise_id', $entreprise->id)
            ->firstOrFail();

        $model->fill(array_merge($request->payload(), [
            'updated_by_user_id' => auth()->id(),
        ]));
        $model->save();

        Session::flash('success', 'Ligne mise à jour.');

        return back();
    }

    public function destroy(Request $request, string $token, int $ligne): RedirectResponse
    {
        $entreprise = Entreprise::query()->where('token', $token)->firstOrFail();
        $this->ensureEditable($entreprise);

        EngagementLigne::query()
            ->where('id', $ligne)
            ->where('entreprise_id', $entreprise->id)
            ->delete();

        Session::flash('success', 'Ligne supprimée.');

        return back();
    }

    private function ensureEditable(Entreprise $entreprise): void
    {
        if (! $this->access->userPeutEcrire(auth()->user(), $entreprise)) {
            abort(403, "Vous n'avez pas le droit de modifier la grille des engagements de cette entreprise.");
        }
    }
}
