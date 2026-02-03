<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestResultsRecord extends Model
{
    protected $table = 'strengths_test_results_records';

    protected $fillable = [
        'test_type',
        'result_code',
        'openid',
        'session_id',
        'answers_snapshot',
        'e_score', 'i_score', 's_score', 'n_score', 't_score', 'f_score', 'j_score', 'p_score',
        'is_paid',
        'paid_at',
    ];

    protected $casts = [
        'is_paid' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function orders()
    {
        return $this->hasMany(StrengthsOrder::class, 'test_result_id');
    }
}
