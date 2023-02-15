<?php

namespace Nucleus\Database\PDO\Connectors;

use Nucleus\Database\Concerns\Connector as Driver;
use Nucleus\Database\PDO\Connector;
use PDO;

class SqliteConnector implements Driver
{
    public function __construct(
        private Connector $connector
    ){}

    public function pdo() : PDO
    {
        $database = $this->connector->get('database');

        if (!\file_exists($database))
        {
            $path = database_path($database);

            if (!\file_exists($path)) {
                file_put_contents($path, "");
                $database = $path;
            }
        }

        $dsn = str_dsn(
            driver: $this->connector->name,
            data: $database
        );

        return new PDO($dsn);
    }
}