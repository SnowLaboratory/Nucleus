<?php

function class_basename($class)
{
    $class = is_object($class) ? get_class($class) : $class;

    return basename(str_replace('\\', '/', $class));
}