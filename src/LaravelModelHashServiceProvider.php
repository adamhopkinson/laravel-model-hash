<?php

namespace AdamHopkinson\LaravelModelHash;

use Illuminate\Support\ServiceProvider;

class LaravelModelHashServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes(
            [
                __DIR__.'/../config/config.php' => config_path('laravelmodelhash.php'),
            ],
            'config'
        );

        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php',
            'laravelmodelhash'
        );
    }

    public function boot(): void
    {
        //
    }
}
