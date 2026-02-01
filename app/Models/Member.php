<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Member extends Model
{
    protected $table = 'admin_users';

    /**
     * 亲属
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function relations()
    {
        return $this->hasMany(MemberRelation::class, 'user_id');
    }

    /**
     * 收集信息
     */
    public function informations()
    {
        return $this->hasMany(GatherInformation::class, 'user_id');
    }

    /**
     * A user has and belongs to many roles.
     *
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        $pivotTable = config('admin.database.role_users_table');

        $relatedModel = config('admin.database.roles_model');

        return $this->belongsToMany($relatedModel, $pivotTable, 'user_id', 'role_id')->withTimestamps();
    }

    public function count(): HasOne
    {
        return $this->hasOne(GatherInformationCount::class, 'user_id');
    }


    public static function getCompany()
    {
        return $data = self::query()->whereNotNull('department')->distinct()->pluck('department', 'department');
    }

    public static function userInfo($userId)
    {
        return self::with('roles')->where('id', $userId)->first()->toArray();
    }

    /**
     * 批量获取用户信息
     * @param $userIds
     * @return array
     */
    public static function userInfos($userIds)
    {
        $usersInfos = self::query()->whereIn('id', $userIds)->get()->toArray();
        return array_column($usersInfos, null, 'id');
    }

    /**
     * 获取商机管理员
     * @param $companyCode
     * @return array
     */
    public static function getBusinessManager()
    {
        $user = Member::query()
            ->join('admin_role_users', 'admin_role_users.user_id', '=', 'admin_users.id')
            ->where('admin_role_users.role_id', 8)
            ->get(['admin_users.*'])
            ->toArray();
        return $user;
    }

}
