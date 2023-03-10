<?php

namespace App\Providers;

use Nucleus\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->router()->load_routes(
            routes: app_path('http/routes/web.php'),
            prefix: '/'
        );

        $this->router()->load_routes(
            routes: app_path('http/routes/api.php'),
            prefix: '/api'
        );
    }
}