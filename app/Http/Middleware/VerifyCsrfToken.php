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
        // Routes d'authentification exclues de la vérification CSRF pour éviter
        // une erreur 419 quand la page est restée ouverte trop longtemps et que
        // la session (et donc le jeton CSRF) a expiré côté serveur :
        //
        // - logout : action idempotente, le middleware `auth` redirige vers /login
        //            si la session n'est plus valide.
        // - login  : la requête est elle-même protégée par les identifiants saisis
        //            et le rate-limiter Fortify (5 tentatives / minute / IP+email).
        // - forgot-password : entrée publique du flux de réinitialisation.
        'logout',
        'login',
        'forgot-password',
    ];
}
