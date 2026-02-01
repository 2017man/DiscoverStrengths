<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestDimension as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestDimension extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
