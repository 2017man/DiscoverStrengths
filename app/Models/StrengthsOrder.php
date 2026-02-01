<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsOrder extends Model
{
    protected $table = 'strengths_orders';

    protected $fillable = [
        'out_trade_no', 'test_result_id', 'test_type', 'openid', 'amount', 'status', 'pay_channel', 'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
    ];

    public function testResult()
    {
        return $this->belongsTo(StrengthsTestResultsRecord::class, 'test_result_id');
    }
}
