<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestDimensionSide extends Model
{
    protected $table = 'strengths_test_dimension_sides';

    protected $fillable = [
        'test_type', 'dimension_code', 'side_code', 'side_name', 'name_en',
        'overview', 'features', 'keywords', 'style', 'expression', 'mantra', 'sort',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];
}
