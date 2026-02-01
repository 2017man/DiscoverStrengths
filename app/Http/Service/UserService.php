<?php


namespace App\Http\Service;


use App\Models\GatherInformation;
use App\Models\GatherInformationCount;
use App\Models\HelperDictionary;
use App\Models\HelperDictionaryInfo;
use App\Models\Member;
use App\Models\MemberRelation;

class UserService extends Service
{
    /**
     * 用户信息
     */
    public static function userInfo($userId)
    {
        $info = Member::query()->where('id', $userId)->first()->toArray();
        return $info;
    }

    /**
     * 用户统计数据
     * @param $userId
     * @return array
     */
    public static function userCount($userId)
    {
        $count = GatherInformationCount::query()->where('user_id', $userId)->first();
        if (!empty($count)) {
            return $count->toArray();
        }
        return [];
    }
}
