<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        /**
         * Quand une page reste ouverte trop longtemps, le token CSRF peut expirer.
         * Plutôt que d'afficher "419 Page Expired", on renvoie l'utilisateur vers
         * la page de connexion (ou on relance la requête précédente) avec un
         * message explicite.
         *
         * Note : les routes d'authentification (logout, login, forgot-password)
         * sont en plus exclues de la vérification CSRF dans
         * `App\Http\Middleware\VerifyCsrfToken` pour garantir que ces flux
         * fonctionnent même quand la session a déjà expiré.
         */
        $this->renderable(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Page expirée. Veuillez recharger la page et réessayer.',
                ], 419);
            }

            // On préserve la saisie utilisateur (sauf champs sensibles) pour
            // éviter qu'un formulaire long à remplir soit perdu sur expiration.
            return redirect()
                ->route('login')
                ->withInput($request->except($this->dontFlash))
                ->with('warning', 'Votre session a expiré. Veuillez vous reconnecter pour continuer.');
        });

        /**
         * Page d’erreur 500 personnalisée en mode debug (détails techniques + déconnexion).
         * Prioritaire sur Ignition pour les erreurs HTTP ≥ 500 et les exceptions non HTTP.
         */
        $this->renderable(function (Throwable $e, Request $request) {
            if (! config('app.debug') || $request->expectsJson()) {
                return null;
            }

            if ($e instanceof ValidationException
                || $e instanceof AuthenticationException
                || $e instanceof HttpResponseException) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            if ($status < 500) {
                return null;
            }

            return response()->view('errors.500', [
                'exception' => $e,
                'httpStatus' => $status,
            ], $status);
        });
    }
}
