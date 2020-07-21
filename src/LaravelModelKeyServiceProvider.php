<?php

namespace AdamHopkinson\LaravelModelKey;

use Illuminate\Support\ServiceProvider;

class LaravelModelKeyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/config.php' => config_path('laravelmodelkey.php'),
            ],
            'config'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php',
            'laravelmodelkey'
        );
    }

    public function boot()
    {
        //
    }
}