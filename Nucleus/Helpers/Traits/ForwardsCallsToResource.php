<?php

namespace Nucleus\Helpers\Traits;

use BadMethodCallException;
use Error;

trait ForwardsCallsToResource
{
    use ForwardsCalls;

    /**
     * Dynamically handle calls into the query instance.
     *
     * @param  string  $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $this->forwardCallTo($this->resource, $method, $parameters);

        return $this;
    }
}
