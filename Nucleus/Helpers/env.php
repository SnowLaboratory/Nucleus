<?php

use Nucleus\FileSystem\Environment;

function env($key, $default=null)
{
    return resolve(Environment::class)->get($key, $default);
}
