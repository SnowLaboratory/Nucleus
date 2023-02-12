<?php

function data_get($arr, $key, $default=null) {
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

function array_only($arr, $keys) {
    return array_filter($arr, fn($val, $key) => in_array($key, $keys), ARRAY_FILTER_USE_BOTH);
}

function array_columns($arr, $keys) {
    return array_map(fn($item) => array_only($item, $keys), $arr);
}