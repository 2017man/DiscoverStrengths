<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestType extends Model
{
    protected $table = 'strengths_test_types';

    protected $fillable = [
        'code', 'name', 'description', 'total_questions', 'estimate_minutes', 'price', 'sort', 'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_questions' => 'integer',
        'sort' => 'integer',
        'status' => 'integer',
    ];
}
