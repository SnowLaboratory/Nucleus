<?php

namespace Nucleus\Routing;

use Closure;
use Exception;

class Route {

    private $router;

    private $prefix;

    public function __construct($router){
        $this->router = $router;
    }

    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    public function get($path, callable|array $handler)
    {
        $this->router->define_route(extends_path($this->prefix, $path), $handler, ['get']);
    }

    public function post($path, callable|array $handler)
    {
        $this->router->define_route($path, $handler, ['post']);
    }

    public function patch($path, callable|array $handler)
    {
        $this->router->define_route($path, $handler, ['patch']);
    }

    public function put($path, callable|array $handler)
    {
        $this->router->define_route($path, $handler, ['put']);
    }

    public function delete($path, callable|array $handler)
    {
        $this->router->define_route($path, $handler, ['delete']);
    }
}