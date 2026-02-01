<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestQuestionOption extends Model
{
    protected $table = 'strengths_test_question_options';

    protected $fillable = [
        'test_type', 'section_code', 'question_number', 'option_key', 'option_text', 'dimension_side',
    ];

    protected $casts = [
        'question_number' => 'integer',
    ];
}
