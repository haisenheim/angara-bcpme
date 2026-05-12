<?php

namespace Modules\Simulator;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\Simulator\Application\Simulator;
use Modules\Simulator\Contracts\SimulatorInterface;
use Modules\Simulator\Http\Middleware\AllowedRoles;
use Modules\Simulator\Persistence\Repositories\ScenarioRepository;
use Modules\Simulator\Support\SimulatorLayoutResolver;

class SimulatorServiceProvider extends ServiceProvider
{
    public const NAME = 'simulator';

    public function register(): void
    {
        $this->mergeConfigFrom(
            $this->modulePath('config/simulator.php'),
            self::NAME,
        );

        $this->app->singleton(ScenarioRepository::class);

        $this->app->bind(SimulatorInterface::class, Simulator::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom($this->modulePath('database/migrations'));

        $this->loadViewsFrom($this->modulePath('resources/views'), self::NAME);

        $this->loadTranslationsFrom($this->modulePath('resources/lang'), self::NAME);

        $this->registerMiddleware();

        $this->registerRoutes();

        $this->registerBladeComponents();

        $this->registerSimulatorLayoutComposer();

        $this->publishes([
            $this->modulePath('config/simulator.php') => config_path('simulator.php'),
        ], 'simulator-config');
    }

    protected function registerMiddleware(): void
    {
        $router = $this->app['router'];
        $router->aliasMiddleware('simulator.allowed', AllowedRoles::class);
    }

    protected function registerRoutes(): void
    {
        Route::middleware(['web', 'auth', 'simulator.allowed'])
            ->prefix('simulator')
            ->name('simulator.')
            ->group($this->modulePath('routes/web.php'));

        // Les endpoints "api" du simulateur sont consommes en AJAX depuis l'UI Blade
        // (session + CSRF). On les enregistre donc dans le groupe `web` pour partager
        // l'authentification de session, et non sur le guard JWT du groupe `api`.
        Route::middleware(['web', 'auth', 'simulator.allowed'])
            ->prefix('api/simulator')
            ->name('simulator.api.')
            ->group($this->modulePath('routes/api.php'));
    }

    protected function registerBladeComponents(): void
    {
        Blade::componentNamespace('Modules\\Simulator\\View\\Components', self::NAME);
    }

    protected function registerSimulatorLayoutComposer(): void
    {
        View::composer([
            'simulator::index',
            'simulator::scenarios.index',
            'simulator::scenarios.show',
        ], function ($view): void {
            $view->with(
                'simulatorLayout',
                SimulatorLayoutResolver::resolve(auth()->user()),
            );
        });
    }

    protected function modulePath(string $relative): string
    {
        return dirname(__DIR__).'/'.ltrim($relative, '/');
    }
}
