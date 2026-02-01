<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestResultsRecord as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestResultsRecord extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
