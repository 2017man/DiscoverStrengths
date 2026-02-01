<?php

namespace App\Admin\Repositories;

use App\Models\HelperDictionary as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class HelperDictionary extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
