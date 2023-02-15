<?php

namespace Nucleus\Database\PDO\Connectors;

use Nucleus\Database\Concerns\Connector as Driver;
use Nucleus\Database\PDO\Connector;
use PDO;

class MySqlConnector implements Driver
{
    public function __construct(
        private Connector $connector
    ){}

    public function pdo() : PDO
    {
        $dsn = str_dsn(
            driver: $this->connector->name,
            data: $this->connector->dsn
        );

        $username = $this->connector->get('username');
        $password = $this->connector->get('password');
        $options = $this->connector->get('options');

        return new PDO($dsn, $username, $password, $options);
    }
}