<?php

namespace LaravelTreats\Pattern\Repository\Criteria;

interface _CriteriaInterface
{
    /**
     * Apply the criteria to a model.
     *
     * @param Illuminate\Database\Eloquent\Model|Illuminate\Database\Eloquent\Builder $model Eloquent model to which the criteria are applied.
     * @return mixed
     */
    public function apply($model);
}
