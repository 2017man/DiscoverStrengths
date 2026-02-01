<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestType as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestType extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
