<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        'admin' => \App\Http\Middleware\Admin::class,
        'pca' => \App\Http\Middleware\Pca::class,
        'adm' => \App\Http\Middleware\Adm::class,
        'dg' => \App\Http\Middleware\Dg::class,
        'dga' => \App\Http\Middleware\Dga::class,
        'respexp' => \App\Http\Middleware\RespExp::class,
        'respaud' => \App\Http\Middleware\RespAud::class,
        'respci' => \App\Http\Middleware\RespCi::class,
        'reri' => \App\Http\Middleware\ReRi::class,
        'reng' => \App\Http\Middleware\ReRi::class,
        'reju' => \App\Http\Middleware\ReJu::class,
        'reconf' => \App\Http\Middleware\ResponsableConformite::class,
        'rerx' => \App\Http\Middleware\ResponsableRisques::class,
        'regional' => \App\Http\Middleware\Regional::class,
        'ca' => \App\Http\Middleware\Ca::class,
        'gestionnaire' => \App\Http\Middleware\Gestionnaire::class,
        'analyste' => \App\Http\Middleware\Analyste::class,
        'analyste.risques' => \App\Http\Middleware\AnalysteRisques::class,
        'analyste.credit' => \App\Http\Middleware\AnalysteCredit::class,
        'analyste.juridique' => \App\Http\Middleware\AnalysteJuridique::class,
        'analyste.conformite' => \App\Http\Middleware\AnalysteConformite::class,
        'chef.agence' => \App\Http\Middleware\Ca::class,
        'chef.filiere' => \App\Http\Middleware\ChefFiliere::class,
        'auditeur' => \App\Http\Middleware\Auditeur::class,
        'controleur' => \App\Http\Middleware\Controleur::class,

    ];
}
