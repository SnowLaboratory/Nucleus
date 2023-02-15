<?php

namespace Nucleus\Providers;

use FilesystemIterator;
use Nucleus\Database\PDO\Connector;
use Nucleus\Database\PDO\Connectors\MySqlConnector;
use Nucleus\Database\PDO\Connectors\SqliteConnector;
use Nucleus\FileSystem\ConfigReader;

class DatabaseServiceProvider extends ServiceProvider
{

    public function register()
    {
        Connector::register('sqlite', SqliteConnector::class);
        Connector::register('mysql', MySqlConnector::class);

        $this->app->singleton(Connector::class, function () {
            $driver = config('database.default');
            $db = config("database.connections.$driver");

            return new Connector($db);
        });
    }

    public function boot()
    {
        
    }
}