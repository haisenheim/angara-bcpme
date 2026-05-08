<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\Agence;
use App\Models\Representation;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HomeController extends ExtendedController
{
    //
    public function index()
    {
        $user = auth()->user();

        // [DEBUG TEMPORAIRE] Dump de l'état complet pour diagnostiquer la
        // boucle de redirection responsable juridique. À retirer après diagnostic.
        dd([
            'message' => '[DEBUG] HomeController@index — état de l\'utilisateur connecté',
            'auth' => [
                'check' => auth()->check(),
                'guard' => config('auth.defaults.guard'),
                'user_id' => auth()->id(),
                'user_is_null' => $user === null,
            ],
            'user_from_auth' => $user ? [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'role_id_type' => gettype($user->role_id),
                'active' => $user->active,
                'agence_id' => $user->agence_id,
                'organisation_type' => $user->organisation_type ?? null,
                'organisation_entite_id' => $user->organisation_entite_id ?? null,
                'token' => $user->token,
            ] : null,
            'user_from_db' => $user ? \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->first() : null,
            'profil_db' => $user ? \Illuminate\Support\Facades\DB::table('profils')->where('id', $user->role_id)->first() : null,
            'config_roles' => [
                'role_responsable_juridique' => config('angara.role_responsable_juridique'),
                'role_responsable_juridique_type' => gettype(config('angara.role_responsable_juridique')),
                'role_responsable_exploitation' => config('angara.role_responsable_exploitation'),
                'role_responsable_engagements' => config('angara.role_responsable_engagements'),
                'role_responsable_conformite' => config('angara.role_responsable_conformite'),
                'role_responsable_risques' => config('angara.role_responsable_risques'),
                'role_chef_agence' => config('angara.role_chef_agence'),
                'role_gestionnaire' => config('angara.role_gestionnaire'),
                'role_chef_filiere' => config('angara.role_chef_filiere'),
            ],
            'env_overrides' => [
                'ANGARA_ROLE_RESP_JURIDIQUE' => env('ANGARA_ROLE_RESP_JURIDIQUE'),
                'ANGARA_ROLE_RESP_EXPLOITATION' => env('ANGARA_ROLE_RESP_EXPLOITATION'),
                'ANGARA_ROLE_RESP_ENGAGEMENTS' => env('ANGARA_ROLE_RESP_ENGAGEMENTS'),
                'APP_ENV' => env('APP_ENV'),
                'APP_DEBUG' => env('APP_DEBUG'),
            ],
            'config_cached' => app()->configurationIsCached(),
            'route_cached' => app()->routesAreCached(),
            'comparison' => $user ? [
                'role_id == 10' => ($user->role_id == 10),
                'role_id === 10' => ($user->role_id === 10),
                'role_id == config(role_responsable_juridique)' => ($user->role_id == config('angara.role_responsable_juridique')),
                '(int) role_id === (int) config' => ((int) $user->role_id === (int) config('angara.role_responsable_juridique')),
            ] : null,
            'session' => [
                'id' => session()->getId(),
                'driver' => config('session.driver'),
            ],
            'request' => [
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'host' => request()->getHost(),
            ],
        ]);

        if ($user) {
            $ux = User::find($user->id);
            Session::put('user', $ux);
            $role_id = $user->role_id;
            if ($role_id == 1) {
                return redirect('/admin/dashboard');
            }
            if ($role_id == 2) {
                return redirect('/pca/dashboard');
            }
            if ($role_id == 3) {
                return redirect('/administrateur/dashboard');
            }
            if ($role_id == 4) {
                return redirect('/dg/dashboard');
            }
            if ($role_id == 5) {
                return redirect('/dga/dashboard');
            }
            if ($role_id == config('angara.role_responsable_exploitation', 6)) {
                return redirect('/respexp/dashboard');
            }
            if ($role_id == config('angara.role_responsable_audit_interne', 7)) {
                return redirect('/respaud/dashboard');
            }
            if ($role_id == config('angara.role_responsable_controle_interne', 8)) {
                return redirect('/respci/dashboard');
            }
            if ($role_id == config('angara.role_responsable_engagements', 9)) {
                return redirect('/reng/dashboard');
            }
            if ($role_id == config('angara.role_responsable_juridique')) {
                return redirect('/juridique/dashboard');
            }
            if ($role_id == config('angara.role_responsable_conformite', 11)) {
                return redirect('/conformite/dashboard');
            }
            if ($role_id == config('angara.role_responsable_risques', 12)) {
                return redirect('/rerx/dashboard');
            }
            if ($role_id == config('angara.role_responsable_regional', 14)) {
                $region = Representation::find($user->representation_id);
                Session::put('region', $region);

                return redirect('/regional/dashboard');
            }
            if ($role_id == config('angara.role_chef_agence', 15)) {
                $agence = Agence::find($user->agence_id);
                Session::put('agence', $agence);

                return redirect()->route('ca.dashboard');
            }
            if ($role_id == config('angara.role_gestionnaire', 16)) {
                $agence = Agence::find($user->agence_id);
                Session::put('agence', $agence);

                return redirect('/gestionnaire/dashboard');
            }
            if ($role_id == config('angara.role_analyste_financier', 17)) {
                $agence = Agence::find($user->agence_id);
                Session::put('agence', $agence);

                return redirect('/analyste/dashboard');
            }
            if ($role_id == config('angara.role_analyste_risques', 18)) {
                return redirect('/analyste-risques/dashboard');
            }

            if ($role_id == config('angara.role_analyste_juridique', 19)) {
                return redirect('/analyste-juridique/dashboard');
            }

            if ($role_id == config('angara.role_analyste_credit', 20)) {
                return redirect('/analyste-credit/dashboard');
            }

            if ($role_id == config('angara.role_analyste_conformite', 21)) {
                return redirect('/analyste-conformite/dashboard');
            }

            if ($role_id == config('angara.role_auditeur', 22)) {
                return redirect('/auditeur/dashboard');
            }

            if ($role_id == config('angara.role_controleur', 23)) {
                return redirect('/controleur/dashboard');
            }

            if ($role_id == config('angara.role_chef_filiere', 24)) {
                $agence = Agence::find($user->agence_id);
                Session::put('agence', $agence);

                return redirect()->route('chef-filiere.dashboard');
            }

            // Aucun dashboard ne correspond au role_id : on déconnecte pour ne pas
            // créer de boucle infinie /home → /login → /home (la route /login étant
            // 'guest', Fortify redirige tout user authentifié vers /home).
            \Illuminate\Support\Facades\Log::warning('[home] Utilisateur sans dashboard, déconnexion', [
                'user_id' => $user->id,
                'email' => $user->email,
                'role_id' => $role_id,
            ]);
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();

            return redirect()->route('login')->with('warning', "Aucun espace n'est associé à votre profil. Contactez l'administrateur.");
        }

        return redirect()->route('login');
    }

    public function profile()
    {
        $user = User::with(['role', 'agence', 'organisationEntite'])
            ->findOrFail(auth()->id());

        return view('Auth.profile', compact('user'));
    }

    public function storeProfile(UpdateProfileRequest $request)
    {
        $user = $request->user();

        if ($request->hasFile('photo')) {
            $uri = $this->entityImgCreate($request->file('photo'), 'profil', $user->token);
            if ($uri) {
                $user->photo_uri = $uri;
            } else {
                return back()
                    ->withInput()
                    ->with('warning', 'Le format de la photo n’est pas accepté (JPEG, PNG ou GIF).');
            }
        }

        $user->name = $request->validated('name');
        $user->email = $request->validated('email');

        if ($request->filled('password')) {
            $user->password = $request->input('password');
        }

        $user->save();

        $fresh = $user->fresh(['role', 'agence', 'organisationEntite']);
        $sessionUser = Session::get('user');
        if ($fresh && is_object($sessionUser) && (int) ($sessionUser->id ?? 0) === (int) $fresh->id) {
            Session::put('user', $fresh);
        }

        Session::flash('success', 'Votre profil a été mis à jour.');

        return back();
    }

    public function logout()
    {
        Auth::logout();

        return redirect('/login');
    }
}
