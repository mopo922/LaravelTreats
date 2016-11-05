<?php

namespace LaravelTreats\Model;

use DB;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class Model extends \Illuminate\Database\Eloquent\Model
{
    /** @var array $rules Validation rules for model data. */
    protected static $rules = [];

    /** @var array $autofillRelationships List of many-to-many relationships that can be autofilled. */
    protected $autofillRelationships = [];

    /** @var array $autofilledRelations Current relations that have been autofilled. */
    private $autofilledRelations = [];

    /**
     * Set a given attribute on the model.
     *
     * Extends parent to allow automatic relationship attachment. Values must
     * represent the primary key of the related record.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->autofillRelationships)) {
            $value = (array)$value;

            $this->autofilledRelations[$key] = $value;
            return $this;
        } else {
            return parent::setAttribute($key, $value);
        }
    }

    /**
     * Save the model to the database.
     *
     * Extends parent to allow automatic many-to-many relationship attachment.
     *
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        return DB::transaction(function() use ($options) {
            $result = parent::save($options);

            foreach ($this->autofilledRelations as $relationship => $relations) {
                if (!empty($relations)) {
                    $this->$relationship()->sync($relations);
                }
            }

            return $result;
        });
    }
}
