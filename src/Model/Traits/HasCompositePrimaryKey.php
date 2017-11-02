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
     * Execute a refresh for a single record.
     *
     * @return Model
     */
    public function refresh()
    {
        if (!$this->exists) {
            return $this;
        }

        $this->load(array_keys($this->relations));

        $me = new self;
        $query = $me->newQuery();

        //make a "where" query with all the set primary keys to find this instance of the model
        foreach ($me->getKeyName() as $key) {
            $value = $this->getAttribute($key);
            if (isset($value)) {
                $query->where($key, '=', $value);
            }
        }
        $model = $query->first();
        $this->setRawAttributes($model->attributes);

        return $this;
    }
}
