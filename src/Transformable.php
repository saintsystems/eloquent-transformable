<?php

namespace SaintSystems\Eloquent\Transformable;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

trait Transformable
{
    protected $transform = [];

    public function __construct(array $attributes = [])
    {
        if (is_array($this->transform) && count($this->transform) > 0) {
            $transformed = $this->getTransformedAttributes();
            if (empty($this->visible)) {
                $this->visible = $transformed;
            }
            if (empty($this->appends)) {
                $this->appends = $transformed;
            }
        }

        parent::__construct($attributes);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasGetMutator($key)
    {
        return $this->keyExistsInTransformationMap($key) || parent::hasGetMutator($key);
    }

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasSetMutator($key)
    {
        return $this->keyExistsInTransformationMap($key) || parent::hasSetMutator($key);
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateAttribute($key, $value)
    {
        if ($this->keyExistsInTransformationMap($key)) {
            if (is_callable($this->transform[$key])) {
                call_user_func($this->transform[$key]);
            }
            return $this->{$this->transform[$key]};
        }
        return parent::mutateAttribute($key, $value);
    }

    /**
     * Set a given attribute on the model.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if ($this->keyExistsInTransformationMap($key)) {
            if (method_exists($this, 'set' . Str::studly($key) . 'Attribute')) {
                parent::setAttribute($key, $value);
            }
            $key = $this->transform[$key];
        }
        return parent::setAttribute($key, $value);
    }

    public function keyExistsInTransformationMap($key)
    {
        return array_key_exists($key, $this->transform);
    }

    public function getTransformationMapInverse()
    {
        return array_flip($this->transform);
    }

    public function getTransformedAttributes()
    {
        return array_keys($this->transform);
    }

    /**
     * Get a new query builder instance for the connection.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected function newBaseQueryBuilder()
    {
        $connection = $this->getConnection();
        $builder = new TransformableQueryBuilder($connection, $connection->getQueryGrammar(), $connection-> getPostProcessor());
        $builder->setTransform($this->transform);
        return $builder;
    }

    /**
     * Qualify the given column name by the model's table.
     *
     * @param  string  $column
     * @return string
     */
    public function qualifyColumn($column)
    {
        if(preg_match('/^\[?\w+\]?\.\[?(\w+)\]?$/', $column, $matches)) {
            $match = $matches[1];
            if (array_key_exists($match, $this->transform)) {
                $column = str_replace($match, $this->transform[$match], $column);
            }
        }

        if ($this->keyExistsInTransformationMap($column)) {
            $column = $this->transform[$column];
        }

        return $this->getTable().'.'.$column;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        $primaryKey = $this->getKeyName();
        if ($this->keyExistsInTransformationMap( $primaryKey)) {
            $primaryKey = $this->transform[$primaryKey];
        }
        $query->where($primaryKey, '=', $this->getKeyForSaveQuery());

        return $query;
    }

    /**
     * Get the default foreign key name for the model.
     *
     * @return string
     */
    public function getForeignKey()
    {
        $foreignKey = Str::snake(class_basename($this)).'_'.$this->getKeyName();
        if ($this->keyExistsInTransformationMap($this->getKeyName())) {
            return $this->transform[$this->getKeyName()];
        }
        return $foreignKey;
    }

}
