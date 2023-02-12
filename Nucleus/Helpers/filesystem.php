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

function base_path($dir='')
{
    return extends_path(NUCLEUS_SHELL_DIR, $dir);
}

function internal_path($dir='')
{
    return extends_path(NUCLEUS_DIR, $dir);
}

function app_path($dir='')
{
    return extends_path(NUCLEUS_APP_DIR, $dir);
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

function delTree($dir)
{
    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );
    
    foreach ($files as $fileinfo) {
        $todo = ($fileinfo->isDir() ? 'rmdir' : 'unlink');
        $todo($fileinfo->getRealPath());
    }
    
    rmdir($dir);
}

function caching_key(string $key)
{
    return $key;
}

function cache_until(string $as, mixed $data, string $expires)
{
    $name = caching_key($as);
    $expires_at = strtotime($expires);
    file_put_contents(
        MICRO . "/Cache/$name.cache.php",
        "<?php return [" .
            "'expires_at' => ". $expires_at .",".
            "'data' => unserialize(base64_decode(\"" . base64_encode(serialize($data)) .
        "\"))];",
    );
    return $data;
}

function cache_pull(string $key, $default=null)
{
    $name = caching_key($key);
    $path = MICRO . "/Cache/$name.cache.php";
    if (file_exists($path)) {
        $entry = require $path;
    } else {
        $entry = null;
    }
    
    if (data_get($entry, 'expires_at', 0) > strtotime('now'))
    {
        return data_get($entry, 'data', $default);
    }

    return $default;
}

function cache_remember($key, $expires, Closure $callback)
{
    if (is_null($result = cache_pull($key)))
    {
        $result = call_user_func($callback);
        return cache_until($key, $result, $expires);
    }

    return $result;
}

function cache_forget($key)
{
    $key = caching_key($key);
    $file = MICRO."/Cache/$key.cache.php";
    if (file_exists($file)) {
        unlink($file);
    }
}
