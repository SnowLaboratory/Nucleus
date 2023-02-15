<?php

namespace Nucleus\Database\Concerns;

use Nucleus\Database\PDO\Connector;
use Nucleus\Database\PDO\Model;
use Nucleus\Database\PDO\SqlAction;

interface SqlQueries
{
    public function __construct(Model $model);

    public function connector(): Connector;

    public function setAction(SqlAction $action): void;
}