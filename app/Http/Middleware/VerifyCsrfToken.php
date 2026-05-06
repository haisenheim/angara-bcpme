<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // La déconnexion est idempotente : on l'exclut de la vérification CSRF
        // pour éviter une erreur 419 quand la session a expiré pendant que
        // l'utilisateur avait la page ouverte. Le middleware `auth` se charge
        // de rediriger vers /login si la session n'est plus valide.
        'logout',
    ];
}
