<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestResultsRecord extends Model
{
    protected $table = 'strengths_test_results_records';

    protected $fillable = [
        'test_type', 'result_code', 'openid', 'session_id', 'answers_snapshot', 'is_paid', 'paid_at',
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
