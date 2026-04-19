<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Laravel\Fortify\Contracts\LogoutResponse;

use Laravel\Fortify\Fortify;
use Illuminate\Support\Str;

class FortifyServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->instance(LogoutResponse::class, new class implements LogoutResponse {
            public function toResponse($request)
            {
                return redirect('/');
            }
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //Fortify::ignoreRoutes();

        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        Fortify::loginView(function (Request $request) {
            return view('Auth.login');
        });

        Event::listen(Attempting::class, function (Attempting $event) {
            if ($event->guard !== 'web') {
                return;
            }
            $c = $event->credentials;
            Log::debug('[auth] Soumission formulaire connexion', [
                'guard' => $event->guard,
                'email' => $c['email'] ?? null,
                'remember' => $event->remember,
                'ip' => request()->ip(),
                'host' => request()->getHost(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        Event::listen(Login::class, function (Login $event) {
            if ($event->guard !== 'web') {
                return;
            }
            Log::debug('[auth] Connexion réussie', [
                'guard' => $event->guard,
                'user_id' => $event->user->getAuthIdentifier(),
            ]);
        });

        Event::listen(Failed::class, function (Failed $event) {
            if ($event->guard !== 'web') {
                return;
            }
            Log::debug('[auth] Connexion refusée', [
                'guard' => $event->guard,
                'email' => $event->credentials['email'] ?? null,
                'user_found' => $event->user !== null,
            ]);
        });
    }
}
