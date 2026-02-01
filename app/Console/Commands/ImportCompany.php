<?php

namespace App\Console\Commands;


use App\Models\CompanyConfig;
use App\Models\GatherInformation;
use App\Models\GatherInformationCount;
use App\Models\Import;
use App\Models\Member;
use App\Models\MemberRelation;
use Dcat\Admin\Admin;
use Dcat\Admin\Models\Administrator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\Models\User;

/**
 * 统计收集数据信息
 * Class CountInfo
 * @package App\Console\Commands
 */
class ImportCompany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:import_company';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '增量更新集团单位数据';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->handleUserRole();


    }

    /**
     * 处理用户角色
     */
    public function handleUserRole()
    {
        $conCompany = [
            ['user_id', '>', 0],
        ];
        // 客户经理
        $userIds = CompanyConfig::query()->where($conCompany)->distinct()->pluck('user_id');
        foreach ($userIds as $userId) {
            $insert    = [
                'user_id'    => $userId,
                'role_id'    => 2,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
            $inserts[] = $insert;
        }

        DB::table('admin_role_users')->insert($inserts);
    }

    /**
     * 处理用户数据
     */
    public function handleUser()
    {
        $inserts   = [];
        $conImport = [
            ['tel', '<>', '']
        ];
        $imports   = Import::query()->where($conImport)->get()->toArray();
        //$users = Member::query()->where($conUser)->get()->toArray();
        // 1.需要插入的企业数据
        $delCompany = [];
        foreach ($imports as $import) {
            $insert = [];
            $up     = [];
            $exists = CompanyConfig::query()->where('code', $import['company_code'])->exists();
            $user   = Member::query()->where('phone', $import['tel'])->first();
            $isMap  = 0;

            // 存在更新不存在删除
            if ($exists) {
                $up = [
                    'area_code'     => $import['xq'],
                    'area_name'     => $import['area_name'],
                    'name'          => $import['company_name'],
                    'level'         => $import['level'],
                    'industry_code' => $import['hy_code'],
                    'industry_name' => $import['hy_name'],
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
                if (!empty($user)) {
                    $up['user_id'] = $user->id;
                    CompanyConfig::query()->where('code', $import['company_code'])->update($up);
                    $isMap = 1;
                } else {
                    $delCompany[] = $import['company_code'];
                }

            } else {
                $insert = [
                    'area_code'     => $import['xq'],
                    'area_name'     => $import['area_name'],
                    'name'          => $import['company_name'],
                    'code'          => $import['company_code'],
                    'level'         => $import['level'],
                    'industry_code' => $import['hy_code'],
                    'industry_name' => $import['hy_name'],
                    'created_at'    => date('Y-m-d H:i:s'),
                    'updated_at'    => date('Y-m-d H:i:s'),
                ];
                if (!empty($user)) {
                    $insert['user_id'] = $user->id;
                    $isMap             = 1;
                    $inserts[]         = $insert;
                }

            }
            if ($isMap) {
                Import::query()->where('company_code', $import['company_code'])->update(['is_map' => $isMap]);
            }
        }


        // 3.需要删除的
        CompanyConfig::query()->whereIn('code', $delCompany)->delete();
        // 2.需要更新的企业数据（区县、区县名称、企业名称、行业代码、行业名称、级别）
        CompanyConfig::query()->insert($inserts);
    }

}
