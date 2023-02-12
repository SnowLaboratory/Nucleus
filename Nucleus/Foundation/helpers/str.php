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