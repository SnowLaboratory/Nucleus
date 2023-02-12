<?php

function server($key, $default=null)
{
    return data_get($_SERVER, $key, $default);
}

function get($key, $default=null)
{
    return data_get($_GET, $key, $default);
}

function session($key, $default=null)
{
    return data_get($_SESSION, $key, $default);
}