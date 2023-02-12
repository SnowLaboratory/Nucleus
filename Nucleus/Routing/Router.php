<?php

namespace Nucleus\Routing;

class Router {

    private $uri;
    private $method;
    private $routes;

    public function __construct(){
        $this->uri = data_get(parse_url(server('REQUEST_URI')), 'path', '/');
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);   
        $this->routes = [];
    }

    public function uri($uri=null)
    {
        $uri = rtrim($uri ?? $this->uri, '/');
        return empty($uri) ? '/' : $uri;
    }

    protected function add_route(string $path, array $data) {
        $this->routes[$path] = $data;
    }
    
    protected function verify_route(string $path)
    {
        if (!array_key_exists($path, $this->routes)) return false;
    
        $route = $this->routes[$path];
        $methods = data_get($route, 'methods', []);
        if (!in_array($this->method, $methods)) return false;
    
        return $route;
    }

    public function define_route(string $path, callable $handler, array|string $method = "get")
    {
        if (is_string($method))
        {
            $method = [$method];
        }
    
        $this->add_route($this->uri($path), [
            'methods' => $method,
            'handler' => $handler,
        ]);
    }

    public function load_routes(string $routes, $prefix='/')
    {
        $route = new Route($this);
        $route->prefix($prefix);
        // extract(compact('route'));
        require_once $routes;
    }

    public function boot()
    {
        if (!($route = $this->verify_route($this->uri()))) abort_raw(404, 'page not found');

        $handler = data_get($route, 'handler');

        call_user_func($handler);
    }
}