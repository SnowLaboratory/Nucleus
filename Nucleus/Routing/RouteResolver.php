<?php

namespace Nucleus\Routing;

use Nucleus\Database\PDO\Model;
use Nucleus\Foundation\Introspection;

class RouteResolver
{
    private array $matches;

    public function __construct(
        private array $route,
    ){
        $this->matches = data_get($route, 'matches');
    }

    public function resolve($class)
    {
        $value = \array_shift($this->matches);
        
        if (is_a($class, Model::class, true))
        {
            $instance = app()->make($class);
            $accessor = $instance->getRouteAccessor();
            $result = $instance->builder()->where($accessor, $value)->first();
            if (empty($result)) return \abort_raw(404, 'model not found');
            return $result;
        }

        return app()->make($class);
    }
}