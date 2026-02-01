<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrengthsTestDimension extends Model
{
    protected $table = 'strengths_test_dimensions';

    protected $fillable = [
        'test_type', 'dimension_code', 'dimension_aspect', 'dimension_trait_pair',
        'dimension_scope', 'dimension_summary', 'dimension_narrative', 'dimension_context', 'sort',
    ];

    protected $casts = [
        'sort' => 'integer',
    ];

    public function sides()
    {
        return $this->hasMany(StrengthsTestDimensionSide::class, 'dimension_code', 'dimension_code')
            ->where('test_type', $this->test_type);
    }
}
