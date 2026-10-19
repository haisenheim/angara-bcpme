<?php

namespace App\Http\Middleware;

use App\Support\Navigation\WorkspaceNavigation;
use App\Support\UserWorkspaceContextResolver;
use Illuminate\Http\Request;
use Inertia\Middleware;

/**
 * Pages Inertia (refonte) : layout unique, menu et profil partagés par toutes les pages.
 * Appliqué route par route (alias « inertia ») pour cohabiter avec les écrans Blade.
 */
class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'inertia.app';

    /**
     * Version des assets : manifest Vite de la sortie historique public/js/modules.
     * Un changement de build force le rechargement complet des pages Inertia ouvertes.
     */
    public function version(Request $request): ?string
    {
        $manifest = public_path('js/modules/manifest.json');

        return is_file($manifest) ? md5_file($manifest) : parent::version($request);
    }

    /**
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        return array_merge(parent::share($request), [
            'auth' => fn () => $user ? [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'initials' => self::initials((string) $user->name),
                ],
                'profile' => [
                    'prefix' => UserWorkspaceContextResolver::routePrefix($user),
                    'label' => $user->role?->name ?? 'Utilisateur',
                    'agence' => $user->agence?->name,
                ],
            ] : null,
            'navigation' => fn () => $user ? WorkspaceNavigation::for($user, $request) : [],
            'flash' => fn () => [
                'success' => $request->session()->get('success') ?? $request->session()->get('status'),
                'error' => $request->session()->get('error'),
            ],
            'urls' => fn () => [
                'home' => route('home'),
                'profile' => route('profile'),
                'logout' => route('logout'),
            ],
        ]);
    }

    private static function initials(string $name): string
    {
        $parts = preg_split('/\s+/', trim($name)) ?: [];
        $letters = array_map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)), array_slice($parts, 0, 2));

        return implode('', $letters) ?: '?';
    }
}
