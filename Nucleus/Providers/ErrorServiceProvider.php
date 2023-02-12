<?php

namespace Nucleus\Providers;

use Nucleus\FileSystem\Environment;
use Nucleus\Linting\Whoops;

class ErrorServiceProvider extends ServiceProvider
{

    public function register()
    {

        $this->app->singleton(Whoops::class, function () {
            return new Whoops();
        });

    }

    public function boot()
    {
        
    }
}