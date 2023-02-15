<?php

namespace Nucleus\Routing;

use Nucleus\Foundation\Introspection;

class Router {

    private $uri;
    private $method;
    private $routes;
    private $default;

    public function __construct(){
        $this->default = '/';
        $this->uri = data_get(parse_url(server('REQUEST_URI', $this->default)), 'path', $this->default);
        $this->method = strtolower(server('REQUEST_METHOD', 'get'));   
        $this->routes = [];
    }

    public function uri($uri=null)
    {
        $uri = rtrim($uri ?? $this->uri, '/');
        return empty($uri) ? '/' : $uri;
    }

    private function add_route(string $path, array $data) {
        $this->routes[$path] = $data;
    }

    private function matched_route(string $path): ?array
    {
        foreach ($this->routes as $route)
        {
            $methods = data_get($route, 'methods', []);
            if (!in_array($this->method, $methods)) continue;

            $pattern = data_get($route, 'pattern', '');
            $matched = preg_match($pattern, $path, $matches) === 1;
            if (!$matched) continue;

            return array_merge($route, [
                "matches" => array_slice($matches, 1)
            ]);
        }

        return null;
    }
    
    private function verify_route(string $path)
    {
        return $this->matched_route($path);
    }

    public function define_route(string $path, callable|array $handler, array|string $method = "get")
    {
        if (is_string($method))
        {
            $method = [$method];
        }
    
        $this->add_route($this->uri($path), [
            'methods' => $method,
            'handler' => make_callable($handler),
            'pattern' => RouteParser::toRegex($path, $handler),
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
        if (!isset($_SERVER['HTTP_USER_AGENT'])) return;
        if (!($route = $this->matched_route($this->uri()))) abort_raw(404, 'page not found');

        $handler = data_get($route, 'handler');
        $args = data_get($route, 'matches');

        $resolver = new RouteResolver($route);

        $reflector = new Introspection([$resolver, 'resolve']);
        $reflector->invoke($handler, ...$args);
    }
}