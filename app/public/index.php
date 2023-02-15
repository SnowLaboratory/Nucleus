<?php

use App\Providers\RouteServiceProvider;

define('APP_START', microtime(true));
define('NUCLEUS', __DIR__ . '../../../Nucleus');
define('NUCLEUS_ROUTE_DIR', __DIR__ . '../Http/Routes');

require_once NUCLEUS . "/bootstrap.php";

$app = new Nucleus();
$app->boot();



