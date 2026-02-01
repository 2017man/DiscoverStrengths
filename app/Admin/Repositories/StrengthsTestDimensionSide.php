<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestDimensionSide as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestDimensionSide extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
