<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestQuestionsSection extends Model
{
    protected $table = 'strengths_test_questions_section';

    protected $fillable = [
        'test_type', 'section_code', 'section_title', 'has_questions', 'sort',
    ];

    protected $casts = [
        'has_questions' => 'integer',
        'sort' => 'integer',
    ];

    public function questions()
    {
        return $this->hasMany(StrengthsTestQuestion::class, 'section_code', 'section_code')
            ->where('test_type', $this->test_type);
    }
}
