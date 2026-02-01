<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestQuestion as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestQuestion extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
