<?php

namespace Nucleus\Helpers\Traits;

trait CallableEnum
{
    public static function __callStatic($name, $args)
    {
        if (method_exists(__CLASS__, $name)) {
            return call_user_func_array([self::class, $name], $args);
        }

        return defined("static::{$name}")
            ? constant("static::{$name}")->value
            : null;
    }

    public function __call($name, $args)
    {
        return method_exists($this, $name)
            ? $this->{$name}($args)
            : $this->{$name};
    }
}
