<?php


namespace App\Http\Service;


use App\Models\HelperDictionary;
use App\Models\HelperDictionaryInfo;

class HelperDictionaryService extends Service
{
    /**
     * 获取下拉数据
     * @param $code
     * @return array
     */
    public function getOptions($code)
    {
        $res  = [];
        $info = HelperDictionary::getInfoKeyVal($code);
        foreach ($info as $key => $val) {
            $item['id']   = $key;
            $item['text'] = $val;
            $res[]        = $item;
        }
        return $res;
    }


    /**
     * 获取下拉数据_API
     * @param $code
     * @return array
     */
    public function getOptionsApi($code)
    {
        $res  = [];
        $info = HelperDictionary::getInfoKeyVal($code);
        foreach ($info as $key => $val) {
            $item['value'] = $key;
            $item['text']  = $val;
            $res[]         = $item;
        }
        return $res;
    }

    /**
     * 文本形式存储
     * 获取下拉数据_API
     * @param $code
     * @return array
     */
    public function getOptionsTextApi($code)
    {
        $res  = [];
        $info = HelperDictionary::getInfoKeyVal($code);
        foreach ($info as $key => $val) {
            $item['value'] = $val;
            $item['text']  = $val;
            $res[]         = $item;
        }
        return $res;
    }
}
