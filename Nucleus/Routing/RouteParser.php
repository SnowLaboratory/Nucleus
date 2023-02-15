<?php

namespace Nucleus\Routing;

use Nucleus\Foundation\Introspection;

class RouteParser
{
    public function __construct(
        private string $pattern
    ){}

    public static function toRegex(string $pattern, callable|array|object|string $callable)
    {
        $params = Introspection::fromCallable($callable)->getParameters();

        $validArgNames = array_pluck($params, 'name');

        $replacements = [];

        foreach ($validArgNames as $argName)
        {
            $replacements["{{$argName}}"] = Pattern::ANY_REQUIRED();
            $replacements["{{$argName}?}"] = Pattern::ANY_OPTIONAL();
        }

        $pattern = implode('\\/', explode('/', $pattern));

        $pattern = strtr($pattern, $replacements);

        return "/^" . $pattern . '\\/?$/';
    }
}