<?php

namespace Nucleus\Database\Concerns;

use Nucleus\Database\PDO\Connector as PDOConnector;
use PDO;

interface Connector {

    public function __construct(PDOConnector $config);

    public function pdo(): PDO;
}