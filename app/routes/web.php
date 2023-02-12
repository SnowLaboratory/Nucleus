<?php

$route->get('/', function () {
    echo "Hello Nucleus";
    echo "<br>";
    echo "APP_ENV: " .  config('app.env');
});