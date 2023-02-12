<?php

if (!defined('NUCLEUS_DIR')) {
    define('NUCLEUS_DIR', __DIR__ . "/../");
}

if (!defined('NUCLEUS_SHELL_DIR')) {
    define('NUCLEUS_SHELL_DIR', __DIR__ . '/../../');
}

if (!defined('NUCLEUS_APP_DIR')) {
    define('NUCLEUS_APP_DIR', NUCLEUS_SHELL_DIR . '/app');
}

if (!defined('NUCLEUS_ENV_DIR')) {
    define('NUCLEUS_ENV_DIR', NUCLEUS_SHELL_DIR);
}

if (!defined('NUCLEUS_CONFIG_DIR')) {
    define('NUCLEUS_CONFIG_DIR', NUCLEUS_APP_DIR . '/config');
}

if (!defined('NUCLEUS_PROVIDER_DIR')) {
    define('NUCLEUS_PROVIDER_DIR', NUCLEUS_APP_DIR . '/providers');
}

if (!defined('NUCLEUS_PUBLIC_DIR')) {
    define('NUCLEUS_PUBLIC_DIR', NUCLEUS_APP_DIR . '/public');
}

if (!defined('NUCLEUS_ROUTE_DIR')) {
    define('NUCLEUS_ROUTE_DIR', NUCLEUS_APP_DIR . '/routes');
}