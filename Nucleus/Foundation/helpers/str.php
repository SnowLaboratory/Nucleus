<?php

function str_after($str, $prefix)
{
    $len = strlen($prefix);
    $index = strpos($str, $prefix);
    if ($index === false) return $str;
    return substr($str, $index, $len) == $prefix
        ? substr($str, $index + $len)
        : $str;
}

function str_before($str, $prefix)
{
    $len = strlen($prefix);
    $index = strpos($str, $prefix);
    if ($index === false) return $str;
    return substr($str, $index, $len) == $prefix
        ? substr($str, 0, $index)
        : $str;
}

function str_between($str, $prefix, $ending)
{
    return str_before(str_after($str, $prefix), $ending);
}

function str_quoted($quoted)
{
    $result = json_decode($quoted, true) ?? trim($quoted);
    return !is_array($result) ? $result : trim($quoted);
}

function str_capitalize($str, $separators=["\t", "\r", "\n", "\f", "\v", "-", "_", " "])
{
    return ucwords($str, implode($separators));
}

function str_kabob($str, $separator)
{
    return str_replace(["\t", "\r", "\n", "\f", "\v", "-", "_", " "], $separator, $str);
}

function str_pascal($str)
{
    return str_kabob(str_capitalize($str), "");
}

function str_dsn(array|object|string $driver, array|object|string $data=[])
{
    
    if (is_string($driver) && is_string($data))
    {
        return implode(":", [$driver, $data]);
    }

    if (is_string($driver))
    {
        return implode(":", [$driver, http_build_query($data, "", ";")]);
    }

    return http_build_query($driver, "", ";");
}

function str_ends_with_any(string $str, array $needles)
{
    foreach($needles as $needle)
    {
        if (str_ends_with($str, $needle))
        {
            return true;
        }
    }

    return false;
}

function str_plural($str)
{
    $es = ['s', 'sh', 'ch', 'x', 'z'];

    if (str_ends_with_any($str, $es)) {
        return $str . 'es';
    }

    if (str_ends_with($str, 'f')) {
        if (in_array($str, ['roof', 'belief', 'chef', 'chief']))
        {
            return $str . 's';
        }
        return substr($str, 0, -1) . 'ves';
    }

    if (str_ends_with($str, 'fe')) {
        return substr($str, 0, -2) . 'ves';
    }

    if (str_ends_with($str, 'y')) {
        $prefix = substr($str, -2, -1);
        if (in_array($prefix, ['a', 'e', 'i', 'o', 'u']))
        {
            return $str . 's';
        }
        return substr($str, 0, -1) . 'ies';
    }

    return $str . 's';
}

function str_ucsplit($string)
{
    return preg_split('/(?=\p{Lu})/u', $string, -1, PREG_SPLIT_NO_EMPTY);
}