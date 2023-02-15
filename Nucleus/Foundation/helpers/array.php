<?php

function data_get(array|object $arr, $key, $default=null) 
{
    $obj = (array) $arr;
    $accessors = explode('.', $key);
    $lastIndex = count($accessors);
    foreach($accessors as $index => $accessor)
    {
        if (isset($obj[$accessor]))
        {
            if ($index + 1 === $lastIndex) {
                return $obj[$accessor];
            }
            $obj = (array) $obj[$accessor];
            continue;
        }
        return $default;
    }
    return $default;
}

function array_only(array|object $arr, $keys) 
{
    return array_filter((array) $arr, fn($val, $key) => in_array($key, $keys), ARRAY_FILTER_USE_BOTH);
}

function array_columns(array $arr, $keys) 
{
    return array_map(fn($item) => array_only($item, $keys), $arr);
}

function array_flatten(array $arr, $depth=INF)
{
    $result = [];

    foreach ($arr as $item) {

        if (!is_array($item)) {
            $result[] = $item;
            continue;
        }

        $values = $depth === 1
            ? array_values($item)
            : array_flatten($item, $depth - 1);

        foreach ($values as $value) {
            $result[] = $value;
        }
    }

    return $result;
}

function array_pluck(array $arr, string $key, ?string $keyBy=null)
{
    $values = array_flatten(array_columns($arr, [$key]), 1);

    if (isset($keyBy)) {
        $keys = array_flatten(array_columns($arr, [$keyBy]));
        return array_combine($keys, $values);
    }

    return $values;
}

function array_fill_values(array $arr, $value=null)
{
    $size = sizeof($arr);
    $keys = array_keys($arr) ?? array_values($arr);
    $values = array_fill(0, $size, $value);
    return array_combine($keys, $values);
}