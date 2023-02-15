<?php

function extends_path(string $root, $dir='')
{
    if (str_ends_with($root, DIRECTORY_SEPARATOR)) 
    {
        $root = str_before($root, DIRECTORY_SEPARATOR);
    }

    return str_starts_with($dir, '/')
        ? $root . $dir
        : $root . '/' . $dir;
}

function normalize_path($path)
{
    // whether $path is unix or not
    $unipath = strlen($path) == 0 || $path[0] != '/';

    // attempts to detect if path is relative in which case, add cwd
    if (!str_contains($path, ':') && $unipath) {
        $path = getcwd() . DIRECTORY_SEPARATOR . $path;
    }
        
    // resolve path parts (single dot, double dot and double delimiters)
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    $path = implode(DIRECTORY_SEPARATOR, $absolutes);
    // resolve any symlinks
    if (file_exists($path) && linkinfo($path) > 0) $path = readlink($path);
    // put initial separator that could have been lost
    $path = !$unipath ? '/' . $path : $path;
    return $path;
};

function base_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_SHELL_DIR), $dir);
}

function internal_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_DIR), $dir);
}

function app_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_APP_DIR), $dir);
}

function config_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_CONFIG_DIR), $dir);
}

function database_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_DATABASE_DIR), $dir);
}

function routes_path($dir='')
{
    return extends_path(normalize_path(NUCLEUS_ROUTE_DIR), $dir);
}