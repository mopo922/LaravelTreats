<?php

namespace LaravelTreats\Model\Traits;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait HasCompositePrimaryKey
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Set the keys for a save update query.
     *
     * @param  Builder $query
     * @return Builder
     * @throws Exception
     */
    protected function setKeysForSaveQuery(Builder $query)
    {
        foreach ($this->getKeyName() as $key) {
            if (isset($this->$key))
                $query->where($key, '=', $this->$key);
            else
                throw new Exception(__METHOD__ . 'Missing part of the primary key: ' . $key);
        }

        return $query;
    }

    /**
     * Execute a query for a single record by ID.
     *
     * @param  array $ids Array of keys, like [column => value].
     * @param  array $columns
     * @return mixed|static
     */
    public static function find($ids, $columns = ['*'])
    {
        $me = new self;
        $query = $me->newQuery();
        foreach ($me->getKeyName() as $key) {
            $query->where($key, '=', $ids[$key]);
        }

        return $query->first($columns);
    }

    /**
     * Get the value of the model's primary key.
     *
     * @return array
     */
    public function getKey()
    {
        $primary_keys_keys = $this->getKeyName();
        $primary_keys_values = [];
        foreach ($primary_keys_keys as $key) {
            $primary_keys_values[$key] = $this->getAttribute($key);
        }
        return $primary_keys_values;
    }

    /**
     * Find a model by its primary key or throw an exception.
     *
     * @param  array  $id
     * @param  array  $columns
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail($id, $columns = ['*'])
    {
        $result = $this->find($id, $columns);
        if (! is_null($result)) {
            return $result;
        }

        throw (new ModelNotFoundException)->setModel(
            get_class($this->model), $id
        );
    }
}
