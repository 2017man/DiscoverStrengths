<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class HelperDictionary extends Model
{

    protected $table = 'helper_dictionary';

    public function info()
    {
        return $this->hasMany(HelperDictionaryInfo::class, 'dictionary_id');
    }

    /**
     * 通过编码获取字典项
     * @param $code
     * @return mixed
     */
    public static function getInfoKeyVal($code)
    {
        return self::query()
            ->where('code', $code)
            ->first()
            ->info()
            ->pluck('value', 'code')
            ->toArray();
    }
}
