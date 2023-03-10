<?php

namespace Nucleus\Providers;

use Nucleus\Routing\Router;

class RouteServiceProvider extends ServiceProvider
{

    public function __construct($app)
    {
        parent::__construct($app);

        $this->app->singleton(Router::class, function () {
            return new Router();
        });

        $this->app->require_once(internal_path('Helpers/response.php'));
    }

    public function router(): Router
    {
        return $this->app->resolve(Router::class);
    }

    public function register()
    {
        
    }

    public function boot()
    {
        $this->router()->boot();
    }
}