<?php

namespace App\Providers;

use App\Services\Dokuwiki\Client;
use App\Services\Dokuwiki\Minutes;
use Illuminate\Support\ServiceProvider;

class DokuwikiServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(Client::class, function () {
            return new Client(
                url: config('dokuwiki.url'),
                username: config('dokuwiki.username'),
                password: config('dokuwiki.password'),
            );
        });

        $this->app->alias(Client::class, 'dokuwiki');

        $this->app->singleton(Minutes::class, function ($app) {
            return new Minutes(
                dokuwiki: $app->make(Client::class),
            );
        });

        $this->app->alias(Minutes::class, 'minutes-generator');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../../config/dokuwiki.php' => config_path('dokuwiki.php'),
        ], 'dokuwiki-config');
    }
}
