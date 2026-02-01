<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestQuestion extends Model
{
    protected $table = 'strengths_test_questions';

    protected $fillable = [
        'test_type', 'section_code', 'question_number', 'question_text', 'sort',
    ];

    protected $casts = [
        'question_number' => 'integer',
        'sort' => 'integer',
    ];

    public function options()
    {
        return $this->hasMany(StrengthsTestQuestionOption::class, 'question_number', 'question_number')
            ->where('test_type', $this->test_type)
            ->where('section_code', $this->section_code);
    }
}
