<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestScoringRule as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestScoringRule extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
