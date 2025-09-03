<?php

namespace App\Providers;

use App\Broadcasting\AngaraNotificationChannel;
use Illuminate\Notifications\Channels\DatabaseChannel as IlluminateDatabaseChannel;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Storage;
use League\Flysystem\Filesystem;
use Masbug\Flysystem\GoogleDriveAdapter;

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

        Storage::extend('google', function ($app, $config) {
            $adapter = new GoogleDriveAdapter($config);
            return new Filesystem($adapter);
        });
    }
}
