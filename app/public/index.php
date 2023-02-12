<?php

define('APP_START', microtime(true));
define('NUCLEUS', __DIR__ . '../../../Nucleus');

require_once NUCLEUS . "/bootstrap.php";

$app = new Nucleus();

$app->boot();



