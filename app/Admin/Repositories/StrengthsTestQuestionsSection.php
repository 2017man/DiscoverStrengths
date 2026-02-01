<?php

namespace App\Admin\Repositories;

use App\Models\StrengthsTestQuestionsSection as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class StrengthsTestQuestionsSection extends EloquentRepository
{
    protected $eloquentClass = Model::class;
}
