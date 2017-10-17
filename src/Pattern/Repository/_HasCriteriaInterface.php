<?php

namespace LaravelTreats\Pattern\Repository;

use LaravelTreats\Pattern\Repository\Criteria\_CriteriaInterface;

interface _HasCriteriaInterface
{
    /**
     * Set all criteria settings back to the default.
     *
     * @return $this
     */
    public function resetScope();

    /**
     * Set a flag to skip all collected criteria.
     *
     * @param bool $status Should criteria be skipped?
     * @return $this
     */
    public function skipCriteria($status = true);

    /**
     * Get the current list of criteria objects.
     *
     * @return Illuminate\Support\Collection
     */
    public function getCriteria();

    /**
     * Apply a single criteria to the model.
     *
     * @param _CriteriaInterface $criteria
     * @return $this
     */
    public function getByCriteria(_CriteriaInterface $criteria);

    /**
     * Add a criteria to the collection.
     *
     * @param _CriteriaInterface $criteria
     * @return $this
     */
    public function pushCriteria(_CriteriaInterface $criteria);

    /**
     * Apply all collected criteria to the model.
     *
     * @return $this
     */
    public function  applyCriteria();
}
