<?php

namespace Nucleus\Foundation;

use Exception;

class Container {

    private static $count = 0;
    private $bindings;
    private $id;

    public function __construct()
    {
        $this->id = static::$count++;
        $this->bindings[] = [];

    }

    public function bind(string $name, callable $resolver)
    {
        $this->bindings[$name] = $resolver;
    }

    public function resolve(string $name)
    {
        if (!array_key_exists($name, $this->bindings))
        {
            throw new Exception("Could not find app binding for [{$name}]");
        }

        $resolver = $this->bindings[$name];
        return call_user_func($resolver);
    }
}