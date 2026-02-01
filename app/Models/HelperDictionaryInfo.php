<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class HelperDictionaryInfo extends Model
{

    protected $table = 'helper_dictionary_info';

    protected $guarded = [];

    public function dictionary()
    {
        return $this->belongsTo(HelperDictionary::class, 'dictionary_id');
    }

}
