<?php

return [

    'name' => 'Egoods',

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    "providers" => [
        \App\Providers\RouteServiceProvider::class,
    ],

];