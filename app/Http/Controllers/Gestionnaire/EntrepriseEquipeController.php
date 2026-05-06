<?php

namespace App\Http\Controllers\Gestionnaire;

use App\Http\Controllers\ExtendedController;
use App\Models\Entreprise;
use App\Models\EntrepriseEquipeMembre;
use App\Models\EntrepriseSite;
use App\Models\Fichier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class EntrepriseEquipeController extends ExtendedController
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
            'entreprise_site_id' => $request->input('entreprise_site_id') === '' ? null : $request->input('entreprise_site_id'),
            'associe' => (bool) $request->boolean('associe'),
            'dirigeant' => (bool) $request->boolean('dirigeant'),
        ]);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'date_naissance' => ['nullable', 'date'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'niveau_etude' => ['nullable', 'string', 'max:80'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'associe' => ['nullable', 'boolean'],
            'dirigeant' => ['nullable', 'boolean'],
            'cni_numero' => ['nullable', 'string', 'max:80'],
            'cni_expire_at' => ['nullable', 'date'],
            'cni_fichier' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'entreprise_site_id' => [
                'nullable',
                'integer',
                Rule::exists(EntrepriseSite::class, 'id')->where('entreprise_id', $entreprise->id),
            ],
            'divers' => ['nullable', 'string'],
        ], [
            'nom.required' => 'Le nom est obligatoire.',
            'telephone.required' => 'Le téléphone est obligatoire.',
        ]);

        DB::connection('central_app_mysql')->transaction(function () use ($entreprise, $validated, $request) {
            $cniFichierId = null;
            if ($request->hasFile('cni_fichier')) {
                $fileToken = sha1('cni-'.$entreprise->id.'-'.microtime(true));
                $uri = $this->storeCniFile($request->file('cni_fichier'), $fileToken);
                if ($uri) {
                    $cniFichierId = (int) Fichier::create([
                        'name' => $uri,
                        'entreprise_id' => $entreprise->id,
                        'type_id' => 0,
                        'token' => $fileToken,
                    ])->id;
                }
            }

            $payload = [
                'entreprise_id' => $entreprise->id,
                'entreprise_site_id' => $validated['entreprise_site_id'] ?? null,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? null,
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'fonction' => $validated['fonction'] ?? null,
                'niveau_etude' => $validated['niveau_etude'] ?? null,
                'specialite' => $validated['specialite'] ?? null,
                'associe' => (bool) ($validated['associe'] ?? false),
                'dirigeant' => (bool) ($validated['dirigeant'] ?? false),
                'cni_numero' => $validated['cni_numero'] ?? null,
                'cni_expire_at' => $validated['cni_expire_at'] ?? null,
                'divers' => $validated['divers'] ?? null,
                'created_by_user_id' => auth()->id(),
                'updated_by_user_id' => auth()->id(),
            ];

            if ($cniFichierId !== null && Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'cni_fichier_id')) {
                $payload['cni_fichier_id'] = $cniFichierId;
            }

            EntrepriseEquipeMembre::query()->create($payload);
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Membre ajouté à l’équipe.');
    }

    public function update(Request $request, string $token, EntrepriseEquipeMembre $membre)
    {
        $entreprise = $this->entrepriseForUser($token);
        if ((int) $membre->entreprise_id !== (int) $entreprise->id) {
            abort(404);
        }

        $request->merge([
            'entreprise_site_id' => $request->input('entreprise_site_id') === '' ? null : $request->input('entreprise_site_id'),
            'associe' => (bool) $request->boolean('associe'),
            'dirigeant' => (bool) $request->boolean('dirigeant'),
        ]);

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:50'],
            'email' => ['nullable', 'email', 'max:100'],
            'date_naissance' => ['nullable', 'date'],
            'fonction' => ['nullable', 'string', 'max:255'],
            'niveau_etude' => ['nullable', 'string', 'max:80'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'associe' => ['nullable', 'boolean'],
            'dirigeant' => ['nullable', 'boolean'],
            'cni_numero' => ['nullable', 'string', 'max:80'],
            'cni_expire_at' => ['nullable', 'date'],
            'cni_fichier' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'entreprise_site_id' => [
                'nullable',
                'integer',
                Rule::exists(EntrepriseSite::class, 'id')->where('entreprise_id', $entreprise->id),
            ],
            'divers' => ['nullable', 'string'],
        ]);

        DB::connection('central_app_mysql')->transaction(function () use ($membre, $validated, $entreprise, $request) {
            $payload = [
                'entreprise_site_id' => $validated['entreprise_site_id'] ?? null,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'] ?? null,
                'telephone' => $validated['telephone'],
                'email' => $validated['email'] ?? null,
                'date_naissance' => $validated['date_naissance'] ?? null,
                'fonction' => $validated['fonction'] ?? null,
                'niveau_etude' => $validated['niveau_etude'] ?? null,
                'specialite' => $validated['specialite'] ?? null,
                'associe' => (bool) ($validated['associe'] ?? false),
                'dirigeant' => (bool) ($validated['dirigeant'] ?? false),
                'cni_numero' => $validated['cni_numero'] ?? null,
                'cni_expire_at' => $validated['cni_expire_at'] ?? null,
                'divers' => $validated['divers'] ?? null,
                'updated_by_user_id' => auth()->id(),
            ];

            if ($request->hasFile('cni_fichier') && Schema::connection('central_app_mysql')->hasColumn('entreprise_equipe_membres', 'cni_fichier_id')) {
                $fileToken = sha1('cni-'.$entreprise->id.'-'.$membre->id.'-'.microtime(true));
                $uri = $this->storeCniFile($request->file('cni_fichier'), $fileToken);
                if ($uri) {
                    $fid = (int) Fichier::create([
                        'name' => $uri,
                        'entreprise_id' => $entreprise->id,
                        'type_id' => 0,
                        'token' => $fileToken,
                    ])->id;
                    $payload['cni_fichier_id'] = $fid;
                }
            }

            $membre->update($payload);
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Membre mis à jour.');
    }

    public function destroy(string $token, EntrepriseEquipeMembre $membre)
    {
        $entreprise = $this->entrepriseForUser($token);
        if ((int) $membre->entreprise_id !== (int) $entreprise->id) {
            abort(404);
        }

        DB::connection('central_app_mysql')->transaction(function () use ($membre) {
            $membre->delete();
        });

        return redirect()
            ->route('gestionnaire.entreprises.show', $token)
            ->with('success', 'Membre supprimé.');
    }

    private function storeCniFile(\Illuminate\Http\UploadedFile $file, string $token): ?string
    {
        $ext = $file->getClientOriginalExtension();
        $allowed = ['pdf', 'jpg', 'jpeg', 'png', 'PDF', 'JPG', 'JPEG', 'PNG'];
        if (! in_array($ext, $allowed, true)) {
            return null;
        }

        if (! file_exists(public_path('files'))) {
            mkdir(public_path('files'));
        }
        if (! file_exists(public_path('files') . '/cni')) {
            mkdir(public_path('files') . '/cni');
        }

        $nameWithExtension = $token . '.' . $ext;
        if (file_exists(public_path('files') . '/cni/' . $nameWithExtension)) {
            unlink(public_path('files') . '/cni/' . $nameWithExtension);
        }

        $uri = 'cni/' . $nameWithExtension;
        $file->move(public_path('files/cni'), $nameWithExtension);

        return $uri;
    }
}

