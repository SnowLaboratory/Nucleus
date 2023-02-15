<?php

namespace Nucleus\Database\Concerns;

use Nucleus\Database\PDO\Model;

interface Builder
{
    public function __construct(Model $model);

    /**
     * @param  string  $field
     * @param  mixed  $value
     * @return $this
     */
    public function where($field, $operator, $value=null): static;

    /**
     * @param  string  $field
     * @param  array  $values
     * @return $this
     */
    public function whereIn($field, array $values): static;

    /**
     * @param  int  $limit
     * @return $this
     */
    public function take($limit): static;

    /**
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc'): static;


    /**
     * @param  mixed  $value
     * @param  callable  $callback
     * @param  callable  $default
     * @return mixed
     */
    public function when($value, $callback, $default = null): static;

    /**
     * @param  Closure  $callback
     * @return $this
     */
    public function tap($callback): static;

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function query($callback): static;

    public function keys(): array;

    public function first(): ?Model;

    public function get(): array;

}