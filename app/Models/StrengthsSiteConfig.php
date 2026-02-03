<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsSiteConfig extends Model
{
    protected $table = 'strengths_site_config';

    protected $fillable = [
        'stats_count', 'stats_date', 'qrcode_wechat', 'qrcode_community',
    ];
}
