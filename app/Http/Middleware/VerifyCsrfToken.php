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
        // Routes d'authentification publiques exclues de la vérification CSRF
        // pour éviter une erreur 419 quand la page est restée ouverte trop
        // longtemps et que la session (donc le jeton CSRF) a expiré côté serveur.
        //
        // Sécurité préservée :
        // - logout : action idempotente ; le middleware `auth` redirige vers /login
        //            si la session n'est plus valide.
        // - login  : protégée par les identifiants saisis et le rate-limiter
        //            Fortify (5 tentatives / minute / IP+email).
        // - forgot-password : endpoint public, déjà rate-limité et lié à un envoi
        //            de mail signé.
        // - reset-password  : protégée par le token signé présent dans l'URL.
        // - two-factor-challenge : protégée par le code OTP et le rate-limiter.
        'logout',
        'login',
        'forgot-password',
        'reset-password',
        'two-factor-challenge',
    ];
}
