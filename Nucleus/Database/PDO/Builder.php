<?php

namespace Nucleus\Database\PDO;

use Exception;
use Nucleus\Database\Concerns\Builder as BuilderContract;
use Nucleus\Database\Concerns\SqlQueries;
use Nucleus\Helpers\Traits\ForwardsCallsToResource;
use PDO;

class Builder implements BuilderContract, SqlQueries
{
    use ForwardsCallsToResource;

    protected Connector $Connector;

    protected Model $resource;

    protected $wheres = [];

    protected $columns = ["*"];

    protected $setters = [];

    protected $actionBindings = [];

    protected $bindings = [];

    protected SqlAction $action;

    public function __construct(Model $model) 
    {
        $this->Connector = resolve(Connector::class);
        $this->resource = $model;
        $this->action = SqlAction::SELECT;
    }

    public function Connector(): Connector
    {
        return $this->Connector;
    }

    public function PDO(): PDO
    {
        return $this->Connector->getDriver()->PDO();
    }

    public function setAction(SqlAction $action): void
    {
        $this->action = $action;
    }

    public function getBindings(): array
    {
        return \array_merge($this->actionBindings, $this->bindings);
    }

    public function select(array $columns=["*"])
    {
        return $this->tap(function () use ($columns) {
            $this->setAction(SqlAction::SELECT);
            $this->columns = $columns;
        });
    }

    public function update(array $columns)
    {
        $this->setAction(SqlAction::UPDATE);
        $this->values($columns);
        $this->PDO()
            ->prepare($this->toSql())
            ->execute($this->getBindings());
    }

    public function insert(array $columns)
    {
        $this->setAction(SqlAction::INSERT);
        $this->values($columns);
        $this->PDO()
            ->prepare($this->toSql())
            ->execute($this->getBindings());
    }

    public function delete()
    {
        
    }

    protected function values(array|object $setters)
    {
        return $this->tap(function () use ($setters) {
            $this->setters = array_fill_values((array) $setters, '?');
            $this->actionBindings = array_values(array_values($setters));
        });
    }

    private function buildSelect(): array
    {
        $sql = [];
        $sql[] = $this->action->value;

        $sql[] = implode(', ', $this->columns);
        
        $sql[] = "FROM";
        $sql[] = $this->resource->getTable();
        return $sql;
    }

    private function buildInsert(): array
    {
        $sql = [];
        $sql[] = $this->action->value;
        $sql[] = "INTO";
        $sql[] = $this->resource->getTable();

        $sql[] = "(" . implode(', ', array_keys($this->setters)) . ")";
        $sql[] = "VALUES";
        $sql[] = "(" . implode(', ', array_values($this->setters)) . ")";

        return $sql;
    }

    private function buildUpdate(): array
    {
        $sql = [];
        $sql[] = $this->action->value;
        $sql[] = $this->resource->getTable();
        $sql[] = "SET";

        $sets = [];
        foreach($this->setters as $column => $value)
        {
            $sets[] = implode(" = ", [$column, $value]);
        }

        if (!empty($sets))
        {
            $sql[] = implode(", ", $sets);
        }
        
        return $sql;
    }

    private function buildDelete(): array
    {
        return [];
    }

    public function toSql()
    {
        $builder = match($this->action) {
            SqlAction::SELECT => [self::class, 'buildSelect'],
            SqlAction::INSERT => [self::class, 'buildInsert'],
            SqlAction::UPDATE => [self::class, 'buildUpdate'],
            SqlAction::DELETE => [self::class, 'buildDelete'],
        };

        $sql = call_user_func($builder);

        if (!empty($this->wheres))
        {
            $sql[] = "WHERE";
            $sql[] = \implode("AND", $this->wheres);
        }

        return join(" ", $sql);
    }

    /**
     * @param  string  $field
     * @param  mixed  $value
     * @return $this
     */
    public function where($field, $operator, $value=null): static 
    {
        if (!isset($value))
        {
            $value = $operator;
            $operator = '=';
        }

        return $this->tap(function () use ($field, $operator, $value) {
            $this->wheres[] = implode(" ", [$field, $operator, "?"]);
            $this->bindings[] = $value;
        });
    }

    /**
     * @param  string  $field
     * @param  array  $values
     * @return $this
     */
    public function whereIn($field, array $values): static
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }

    /**
     * @param  int  $limit
     * @return $this
     */
    public function take($limit): static
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }

    /**
     * @param  string  $column
     * @param  string  $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc'): static
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }


    /**
     * @param  mixed  $value
     * @param  callable  $callback
     * @param  callable  $default
     * @return mixed
     */
    public function when($value, $callback, $default = null): static
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }

    /**
     * @param  Closure  $callback
     * @return $this
     */
    public function tap($callback): static
    {
        return tap($this, $callback);
    }

    /**
     * @param  callable  $callback
     * @return $this
     */
    public function query($callback): static
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }

    public function keys(): array
    {
        throw new Exception("Unimplemented builder method");
        return tap($this);
    }

    public function generator($stmt)
    {
        while($row = $stmt->fetch())
        {
            yield $row;
        }
    }

    public function first(): ?Model
    {
        $stmt = $this->PDO()
            ->prepare($this->toSql());

        $stmt->execute($this->getBindings());

        $stmt->setFetchMode(PDO::FETCH_CLASS, \get_class($this->resource));

        $result = $stmt->fetch();

        $stmt->closeCursor();

        return !$result ? null : $result;
    }

    public function get(): array
    {
        $stmt = $this->PDO()
            ->prepare($this->toSql());

        $stmt->execute($this->getBindings());

        $stmt->setFetchMode(PDO::FETCH_CLASS, \get_class($this->resource));

        $results = $stmt->fetchAll();

        $stmt->closeCursor();

        return $results;
    }

    public function count(): int
    {
        return count($this->get());
    }
}