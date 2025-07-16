<?php

namespace App\Providers;

use App\Broadcasting\AngaraNotificationChannel;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //

        $this->app->instance(IlluminateDatabaseChannel::class, new AngaraNotificationChannel());
    }
}
