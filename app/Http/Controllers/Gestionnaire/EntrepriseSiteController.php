<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\Controller;
use App\Models\Arrondissement;
use App\Models\Entreprise;
use App\Models\EntrepriseSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class EntrepriseSiteController extends Controller
{
    private function entrepriseForUser(string $token): Entreprise
    {
        $uid = (int) auth()->id();

        return Entreprise::query()
            ->where('token', $token)
            ->where(function ($q) use ($uid) {
                $q->where('gestionnaire_id', $uid)->orWhere('user_id', $uid);
            })
            ->firstOrFail();
    }

    public function store(Request $request, string $token)
    {
        $entreprise = $this->entrepriseForUser($token);

        $request->merge([
            'arrondissement_id' => $request->input('arrondissement_id') === '' ? null : $request->input('arrondissement_id'),
        ]);

        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'arrondissement_id' => ['required', 'integer', Rule::exists(Arrondissement::class, 'id')],
            'village_ou_quartier' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'string', 'max:100'],
            'longitude' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'divers' => ['nullable', 'string'],
        ], [
            'libelle.required' => 'Le nom / libellé du site est obligatoire.',
            'arrondissement_id.required' => 'La commune est obligatoire.',
        ]);

        $arr = Arrondissement::with('departement')->find((int) $validated['arrondissement_id']);
        $depId = $arr?->departement_id;
        $regId = $arr?->departement?->region_id;

        DB::connection('central_app_mysql')->transaction(function () use ($entreprise, $validated, $depId, $regId) {
            EntrepriseSite::query()->create([
                'entreprise_id' => $entreprise->id,
                'libelle' => $validated['libelle'],
                'arrondissement_id' => (int) $validated['arrondissement_id'],
                'departement_id' => $depId,
                'region_id' => $regId,
                'village_ou_quartier' => $validated['village_ou_quartier'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'] ?? null,
                'divers' => $validated['divers'] ?? null,
                'created_by_user_id' => auth()->id(),
                'updated_by_user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Site ajouté avec succès.');
    }

    public function update(Request $request, string $token, EntrepriseSite $site)
    {
        $entreprise = $this->entrepriseForUser($token);
        if ((int) $site->entreprise_id !== (int) $entreprise->id) {
            abort(404);
        }

        $request->merge([
            'arrondissement_id' => $request->input('arrondissement_id') === '' ? null : $request->input('arrondissement_id'),
        ]);

        $validated = $request->validate([
            'libelle' => ['required', 'string', 'max:255'],
            'arrondissement_id' => ['required', 'integer', Rule::exists(Arrondissement::class, 'id')],
            'village_ou_quartier' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'string', 'max:100'],
            'longitude' => ['nullable', 'string', 'max:100'],
            'telephone' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'divers' => ['nullable', 'string'],
        ]);

        $arr = Arrondissement::with('departement')->find((int) $validated['arrondissement_id']);
        $depId = $arr?->departement_id;
        $regId = $arr?->departement?->region_id;

        DB::connection('central_app_mysql')->transaction(function () use ($site, $validated, $depId, $regId) {
            $site->update([
                'libelle' => $validated['libelle'],
                'arrondissement_id' => (int) $validated['arrondissement_id'],
                'departement_id' => $depId,
                'region_id' => $regId,
                'village_ou_quartier' => $validated['village_ou_quartier'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'telephone' => $validated['telephone'] ?? null,
                'email' => $validated['email'] ?? null,
                'divers' => $validated['divers'] ?? null,
                'updated_by_user_id' => auth()->id(),
            ]);
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Site mis à jour.');
    }

    public function destroy(string $token, EntrepriseSite $site)
    {
        $entreprise = $this->entrepriseForUser($token);
        if ((int) $site->entreprise_id !== (int) $entreprise->id) {
            abort(404);
        }

        DB::connection('central_app_mysql')->transaction(function () use ($site) {
            $site->delete();
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Site supprimé.');
    }
}

