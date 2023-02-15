<?php

namespace Nucleus\Routing;

use Nucleus\Helpers\Traits\CallableEnum;

enum Pattern: string
{
    use CallableEnum;

    case ANY_REQUIRED = '([^\/]+)';

    case ANY_OPTIONAL = '?([^\/]+)?';
}