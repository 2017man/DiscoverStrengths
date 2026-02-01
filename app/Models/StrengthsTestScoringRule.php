<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestScoringRule extends Model
{
    protected $table = 'strengths_test_scoring_rules';

    protected $fillable = [
        'test_type', 'rule_name', 'rule_type', 'calculate_method', 'top_count', 'description',
    ];

    protected $casts = [
        'top_count' => 'integer',
    ];
}
