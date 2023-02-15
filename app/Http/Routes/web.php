<?php

use App\Http\Controllers\GuestController;

$route->get('/', [GuestController::class, 'welcome']);
$route->get('/users/{user}', [GuestController::class, 'user']);