<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestAnswer as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestAnswer extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
