<?php

namespace SaintSystems\Eloquent\Transformable;

use Closure;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Database\Grammar;
use Illuminate\Database\Query\Processors\Processor;

class TransformableQueryBuilder extends Builder
{
    protected $query;
    protected $transform = [];

    public function setTransform($transform)
    {
        $this->transform = $transform;
    }

    /**
     * Create a new query builder instance.
     *
     * @param  \Illuminate\Database\ConnectionInterface  $connection
     * @param  \Illuminate\Database\Query\Grammars\Grammar  $grammar
     * @param  \Illuminate\Database\Query\Processors\Processor  $processor
     * @return void
     */
    public function __construct(
        ConnectionInterface $connection,
        Grammar $grammar = null,
        Processor $processor = null
    ) {
        parent::__construct($connection, $grammar, $processor);
    }

    public function getTransformedColumns()
    {
        $transformedColumns = [];
        if (empty($this->columns)) return $this->columns;

        foreach ($this->columns as $column) {
            if (is_string($column)) {
                $column = $this->extractColumn($column);
            }
            $transformedColumns[] = $column;
        }
        return $transformedColumns;
    }

    public function getTransformedWheres()
    {
        $transformedWheres = [];
        if (empty($this->wheres)) return $this->wheres;
        foreach ($this->wheres as $where) {
            if (isset($where['column'])) {
                $where['column'] = $this->extractColumn($where['column']);
                $transformedWheres[] = $where;
            } else {
                $transformedWheres[] = $where;
            }
        }
        return $transformedWheres;
    }

    public function getTransformedOrders()
    {
        $transformedOrders = [];
        if (empty($this->orders)) return $this->orders;
        foreach ($this->orders as $order) {
            if (isset($order['column'])) {
                $order['column'] = $this->extractColumn($order['column']);
                $transformedOrders[] = $order;
            } else {
                $transformedOrders[] = $order;
            }
        }
        return $transformedOrders;
    }

    public function extractColumn($column)
    {
        $column = trim($column);

        if (preg_match('/\[?\w+\]?\.\[?(\w+)\]?/', $column, $matches)) {
            $match = $matches[1];
            if (array_key_exists($match, $this->transform)) {
                $column = str_replace($match, $this->transform[$match], $column);
            }
        } else {
            if (array_key_exists($column, $this->transform)) {
                $column = $this->transform[$column];
            }
        }

        return $column;
    }

    /**
     * Get the SQL representation of the query.
     *
     * @return string
     */
    public function toSql()
    {
        $this->columns = $this->getTransformedColumns();
        $this->wheres = $this->getTransformedWheres();
        $this->orders = $this->getTransformedOrders();

        return parent::toSql();
    }
}
