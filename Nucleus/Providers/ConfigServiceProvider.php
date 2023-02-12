<?php

namespace Nucleus\Providers;

use FilesystemIterator;
use Nucleus\FileSystem\ConfigReader;

class ConfigServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->singleton(ConfigReader::class, function () {
            $iterator = new FilesystemIterator(NUCLEUS_CONFIG_DIR, FilesystemIterator::SKIP_DOTS);
            $instance = new ConfigReader($iterator);
            return $instance->load();
        });

        $this->app->require(internal_path('Helpers/config.php'));
    }

    public function boot()
    {
        
    }
}