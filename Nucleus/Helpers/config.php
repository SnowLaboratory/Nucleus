<?php

use Nucleus\FileSystem\ConfigReader;

function config($key, $default=null)
{
    return resolve(ConfigReader::class)->get($key, $default);
}
