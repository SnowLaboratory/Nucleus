<?php

namespace Nucleus\Database\PDO;

use Exception;
use Nucleus\Database\Concerns\Connector as Driver;
use PDO;

class Connector
{
    public $driver;

    public $name;

    public $pdo;

    public $dsn;

    protected $config;

    private static $drivers = [];

    public function __construct(array $config)
    {
        $this->config = $config;

        $this->dsn = array_only($config, ['host', 'charset', 'port']);
        $this->dsn['dbname'] = data_get($config, 'database');
        $this->dsn['unix_socket'] = data_get($config, 'socket');

        $this->name = data_get($config, 'driver');

        $this->pdo = $this->getDriver()->pdo();
    }

    public function get($key, $default=null)
    {
        return data_get($this->config, $key, $default);
    }

    public function getConnector() : string
    {
        if (!array_key_exists($this->name, static::$drivers)){
            throw new Exception(\sprintf(
                "Unknown PDO driver [%s]\n%s",
                $this->name,
                'Register using Connector::register($name, $class)'
            ));
        }

        return static::$drivers[$this->name];
    }

    public function getDriver() : Driver
    {
        $connector = $this->getConnector();

        try {
            return new $connector($this);
        } catch (Exception $error)
        {
            throw new Exception(\sprintf(
                "Failed to create PDO driver [%s]\n%s",
                $this->name, $error->getMessage()
            ));
        }
    }

    public static function register(string $driver, string $connector)
    {
        if (!is_a($connector, Driver::class, true))
        {
            throw new Exception(\sprintf(
                "Class [%s] does not implement %s",
                $connector, Driver::class
            ));
        }

        static::$drivers[$driver] = $connector;
    }
}