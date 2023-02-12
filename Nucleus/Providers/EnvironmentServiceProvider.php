<?php

namespace Nucleus\Providers;

use Nucleus\FileSystem\Environment;
use Nucleus\Routing\Router;

class EnvironmentServiceProvider extends ServiceProvider
{

    public function register()
    {

        // todo change if in testing env
        $env = base_path('.env');

        $this->app->singleton(Environment::class, function () use ($env) {
            $instance = new Environment($env);
            return $instance->load();
        });

        $this->app->require(internal_path('Helpers/env.php'));
    }

    public function boot()
    {
        
    }
}