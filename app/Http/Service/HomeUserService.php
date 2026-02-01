<?php


namespace App\Http\Service;


use App\Models\GatherInformation;
use App\Models\HelperDictionary;
use App\Models\HelperDictionaryInfo;
use App\Models\MemberRelation;

class HomeUserService extends Service
{
    /**
     * 用户统计数据
     * @param $userId
     * @return array
     */
    public static function count($userId)
    {
        $con       = ['user_id' => $userId];
        $relations = MemberRelation::query()->where($con)->count();
        $infors    = GatherInformation::query()->where($con)->count();
        $companys  = GatherInformation::query()->where($con)->groupBy('company_code')->count();
        return [
            'relations' => $relations,
            'infors'    => $infors,
            'companys'  => $companys,
        ];
    }
}
