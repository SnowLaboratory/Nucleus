<?php

namespace Nucleus\Database\PDO;

abstract class Model
{
    protected $primary = 'id';

    protected $table = null;

    protected $attributes = [];

    protected $fillable = [];

    protected $builder;

    public function __construct()
    {
        if (!isset($table)) {
            $this->table = $this->getTable();
        }

        $this->builder = new Builder($this);
    }

    private function detectedTableName()
    {
        return strtolower(
            implode("_",
                str_ucsplit(
                    str_plural(
                        class_basename($this)
                    )
                )
            )
        );
    }

    public function getTable() : string
    {
        return $this->table ?? $this->detectedTableName();
    }

    public function builder(): Builder
    {
        return $this->builder;
    }

    public function save()
    {
        $primary = $this->primary;

        $this->builder()
            ->where($primary, $this->{$primary})
            ->update($this->attributes);
    }

    public function getRouteAccessor()
    {
        return $this->getPrimary();
    }

    public function getPrimary()
    {
        return $this->primary;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __get($name)
    {
        if (\array_key_exists($name, $this->attributes))
        {
            return $this->attributes[$name];
        }
    }

    public static function newInstance()
    {
        return new static;
    }

    public static function __callStatic($name, $arguments)
    {
        $model = static::newInstance();
        $builder = $model->builder();

        if (method_exists($builder, $name))
        {
            return $builder->{$name}(...$arguments);
        }
        
    }
}