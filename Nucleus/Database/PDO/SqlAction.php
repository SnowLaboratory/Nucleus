<?php

namespace Nucleus\Database\PDO;

use Nucleus\Helpers\Traits\CallableEnum;

enum SqlAction: string
{
    use CallableEnum;

    case SELECT = 'SELECT';
    case INSERT = 'INSERT';
    case UPDATE = 'UPDATE';
    case DELETE = 'DELETE';
}