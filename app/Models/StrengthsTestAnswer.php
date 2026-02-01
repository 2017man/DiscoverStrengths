<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestAnswer extends Model
{
    protected $table = 'strengths_test_answer';

    protected $fillable = [
        'test_type', 'result_code', 'result_name', 'summary', 'traits_summary', 'traits',
        'strengths', 'weaknesses', 'careers', 'typical_figures', 'sort', 'status',
    ];

    protected $casts = [
        'sort' => 'integer',
        'status' => 'integer',
    ];
}
