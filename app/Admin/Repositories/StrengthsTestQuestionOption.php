<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestQuestionOption as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestQuestionOption extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
