<?php

namespace LaravelTreats\Pattern\Repository;

use LaravelTreats\Pattern\Repository\Criteria\_CriteriaInterface;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Validator;

abstract class Repository implements _RepositoryInterface, _HasCriteriaInterface
{
    /**
     * The model instance, for persisting to the data store.
     *
     * @var Illuminate\Database\Eloquent\Model $model
     */
    protected $model;

    /**
     * Collection of criteria to apply to the queries.
     *
     * @var Illuminate\Support\Collection $criteria
     */
    protected $criteria;

    /**
     * Should the criteria be skipped?
     *
     * @var bool $skipCriteria
     */
    protected $skipCriteria = false;

    /**
     * Set up the repository with a model instance.
     *
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $this->resetScope();
        $this->makeModel();
    }

    /**
     * Child classes should specify the model class name.
     *
     * @return string
     */
    abstract protected function modelClass();

    /**
     * Child classes should specify input validation rules.
     *
     * @return array
     */
    abstract protected function validationRules();

    /**
     * Child classes can add criteria for all SELECT queries.
     *
     * @return $this
     */
    protected function addSelectCriteria()
    {
        return $this;
    }

    /**
     * Generate an instance of the model.
     *
     * @return Illuminate\Database\Eloquent\Model
     * @throws InvalidArgumentException
     */
    public function makeModel()
    {
        $model = app()->make($this->modelClass());

        if (!$model instanceof Model) {
            throw new InvalidArgumentException(
                'Class ' . $this->model() . ' must be an instance of Illuminate\\Database\\Eloquent\\Model'
            );
        }

        return $this->model = $model;
    }

    /**
     * Validate and scrub the input data.
     *
     * @param array $data Input data from the user
     * @throws \Illuminate\Validation\ValidationException
     * @return array Input array, filtered by valid data
     */
    protected function validate(array $data)
    {
        $validator = Validator::make($data, $this->validationRules());
        $validator->validate();

        return array_intersect_key($data, $this->validationRules());
    }

    /**
     * Get all records of the resource.
     *
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = ['*'])
    {
        $this->addSelectCriteria();
        $this->applyCriteria();

        foreach (request()->query() as $key => $value) {
            if ($key === 'with_trashed' && $value) {
                $this->model = $this->model->withTrashed();
            } else if (is_array($value)) {
                $this->model = $this->model->whereIn($key, $value);
            } else {
                $this->model = $this->model->where($key, $value);
            }
        }

        return $this->model->get($columns);
    }

    /**
     * Paginate the resource.
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'))
    {
        $this->addSelectCriteria();
        $this->applyCriteria();

        return $this->model->paginate($perPage, $columns);
    }

    /**
     * Create a new resource record.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($this->validate($data));
    }

    /**
     * Update an existing resource record.
     *
     * @param mixed $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data)
    {
        $model = $this->model->find($id);

        if ($this->canEdit($model)) {
            $model->fill($this->validate($data));
            $model->save();

            return $model;
        } else {
            return false;
        }
    }

    /**
     * Delete a resource record.
     *
     * @param mixed $id
     * @return mixed
     */
    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    /**
     * Find a specific resource record by ID.
     *
     * @param mixed $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*'])
    {
        $this->addSelectCriteria();
        $this->applyCriteria();

        return $this->model->find($id, $columns);
    }

    /**
     * Find the first resource record by attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $attribute, $value, array $columns = ['*'])
    {
        $this->addSelectCriteria();
        $this->applyCriteria();

        return $this->model->where($attribute, '=', $value)->first($columns);
    }

    /**
     * Set all criteria settings back to the default.
     *
     * @return $this
     */
    public function resetScope()
    {
        $this->criteria = collect([]);
        $this->skipCriteria(false);

        return $this;
    }

    /**
     * Set a flag to skip all collected criteria.
     *
     * @param bool $status Should criteria be skipped?
     * @return $this
     */
    public function skipCriteria($status = true)
    {
        $this->skipCriteria = $status;

        return $this;
    }

    /**
     * Get the current list of criteria objects.
     *
     * @return Illuminate\Support\Collection
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * Apply a single criteria to the model.
     *
     * @param _CriteriaInterface $criteria
     * @return $this
     */
    public function getByCriteria(_CriteriaInterface $criteria)
    {
        $this->model = $criteria->apply($this->model, $this);

        return $this;
    }

    /**
     * Add a criteria to the collection.
     *
     * @param _CriteriaInterface $criteria
     * @return $this
     */
    public function pushCriteria(_CriteriaInterface $criteria)
    {
        $this->criteria->push($criteria);

        return $this;
    }

    /**
     * Apply all collected criteria to the model.
     *
     * @return $this
     */
    public function applyCriteria()
    {
        if (!$this->skipCriteria) {
            foreach ($this->getCriteria() as $criteria) {
                if ($criteria instanceof _CriteriaInterface) {
                    $this->model = $criteria->apply($this->model, $this);
                }
            }
        }

        return $this;
    }

    /**
     * Check if the current User can edit the given model.
     *
     * @param Model $model The model we want to edit.
     * @return bool
     */
    protected function canEdit(Model $model)
    {
        return true;
    }
 }
