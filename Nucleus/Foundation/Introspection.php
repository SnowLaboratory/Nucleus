<?php

namespace Nucleus\Foundation;

use Closure;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;
use ReflectionObject;

class Introspection
{

    private Closure $resolver;

    public function __construct(Closure|array $resolver) {
        $this->resolver = \is_array($resolver) ? static::toClosure($resolver) : $resolver;
    }

    public static function toClosure(callable|array|string|object $callable, $instance=null) : Closure
    {
        $method = static::fromCallable($callable);
        return $method instanceof ReflectionMethod
            ? $method->getClosure($instance ?? $callable[0])
            : $method->getClosure();
    }

    public static function fromCallable(callable|array|string|object $callable) : ReflectionMethod|ReflectionFunction
    {
        if(is_array($callable)) {
            $reflector = new ReflectionMethod($callable[0], $callable[1]);
        } elseif(is_string($callable)) {
            $reflector = new ReflectionFunction($callable);
        } elseif(is_a($callable, 'Closure') || is_callable($callable, '__invoke')) {
            $objReflector = new ReflectionObject($callable);
            $reflector = $objReflector->getMethod('__invoke');
        }

        return $reflector;
    }

    public function invoke(callable|array|string|object $callable, ...$positionalArgs)
    {
        $method = static::fromCallable($callable);
        $params = $method->getParameters();
        $args = [];
        
        foreach ($params as $param)
        {
            $type = $param->getType();
            if (isset($type))
            {
                $args[] = \call_user_func($this->resolver, $type->getName());
                continue;
            }
            $args[] = array_shift($positionalArgs);
        }

        call_user_func($callable, ...$args);
    }
}