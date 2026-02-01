<?php

namespace App\Admin\Repositories;

use App\Models\HelperDictionaryInfo as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class HelperDictionaryInfo extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
