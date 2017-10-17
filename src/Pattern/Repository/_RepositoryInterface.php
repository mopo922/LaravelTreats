<?php

namespace LaravelTreats\Pattern\Repository;

interface _RepositoryInterface
{
    /**
     * Get all records of the resource.
     *
     * @param array $columns
     * @return mixed
     */
    public function all(array $columns = array('*'));

    /**
     * Paginate the resource.
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = array('*'));

    /**
     * Create a new resource record.
     *
     * @param array $data
     * @return mixed
     */
    public function create(array $data);

    /**
     * Update an existing resource record.
     *
     * @param mixed $id
     * @param array $data
     * @return mixed
     */
    public function update($id, array $data);

    /**
     * Delete a resource record.
     *
     * @param mixed $id
     * @return mixed
     */
    public function delete($id);

    /**
     * Find a specific resource record by ID.
     *
     * @param mixed $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = ['*']);

    /**
     * Find the first resource record by attribute.
     *
     * @param string $attribute
     * @param mixed $value
     * @param array $columns
     * @return mixed
     */
    public function findBy(string $attribute, $value, array $columns = ['*']);
}
