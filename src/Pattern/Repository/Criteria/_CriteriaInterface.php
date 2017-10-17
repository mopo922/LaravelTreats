<?php

namespace App\Pattern\Repository\Criteria;

use Illuminate\Database\Eloquent\Model;

interface _CriteriaInterface
{
    /**
     * Apply the criteria to a model.
     *
     * @param Illuminate\Database\Eloquent\Model $model Eloquent model to which the criteria are applied.
     * @return mixed
     */
    public function apply(Model $model);
}
