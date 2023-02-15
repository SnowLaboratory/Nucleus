<?php

use Nucleus\Helpers\HigherOrderTapProxy;

function tap($value, $callback = null)
{
    if (is_null($callback)) {
        return new HigherOrderTapProxy($value);
    }

    $callback($value);

    return $value;
}

function make_callable(callable|array $handler)
{
    if (\is_callable($handler)) return $handler;

    $invokeHolder = $handler[0];

    if (!\is_string($invokeHolder)) throw new Exception('Expected first handler argument to be a string');

    $instance = app()->make($invokeHolder);

    return [$instance, ...array_slice($handler, 1)];
}