<?php

namespace Nucleus\Providers;

use Nucleus;

abstract class ServiceProvider
{
    protected $app;

    public function __construct(Nucleus $app)
    {
        $this->app = $app;
    }

    public abstract function register();

    public abstract function boot();
}