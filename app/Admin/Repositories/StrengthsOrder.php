<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsOrder extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
