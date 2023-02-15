<?php

use App\Http\Controllers\GuestController;

$route->get('/', [GuestController::class, 'welcome']);
$route->get('/company/{id}/products', [GuestController::class, 'welcome2']);
$route->get('/users/{user}', [GuestController::class, 'welcome3']);