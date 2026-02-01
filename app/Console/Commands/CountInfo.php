<?php

namespace App\Console\Commands;


use App\Models\GatherInformation;
use App\Models\GatherInformationCount;
use App\Models\Member;
use App\Models\MemberRelation;
use Dcat\Admin\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\Models\User;

/**
 * 统计收集数据信息
 * Class CountInfo
 * @package App\Console\Commands
 */


class CountInfo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:count_info';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每分钟定时生成全量收集信息统计数据';

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
        $con     = [
            ['user_id', '>', 1]
        ];
        $conUser = [
            ['id', '>', 1],
        ];
        $users   = Member::query()->where($conUser)->get()->toArray();
        /**
         * 计算统计数据
         * 1 - 个人信息数
         * 2 - 个人亲属数
         * 3 - 集团单位数
         */
        $usersInfos     = GatherInformation::query()
            ->where($con)
            ->selectRaw("user_id,count(*) as num")
            ->groupBy('user_id')
            ->get()->toArray();
        $usersRelations = MemberRelation::query()->where($con)
            ->selectRaw("user_id,count(*) as num")
            ->groupBy('user_id')
            ->get()->toArray();
        $usersCompanys  = MemberRelation::query()->where($con)
            ->selectRaw("user_id,count(DISTINCT company_code) as num")
            ->groupBy('user_id')
            ->get()->toArray();

        $usersInfos     = array_column($usersInfos, null, 'user_id');
        $usersRelations = array_column($usersRelations, null, 'user_id');
        $usersCompanys  = array_column($usersCompanys, null, 'user_id');

        $inserts = [];
        foreach ($users as $user) {
            $insert                    = [];
            $insert['user_id']         = $user['id'];
            $insert['user_name']       = $user['name'];
            $insert['user_department'] = $user['department'];
            $insert['user_area']       = $user['area'];
            $insert['user_phone']      = $user['phone'];
            $insert['user_job_number'] = $user['job_number'];
            $insert['user_avatar']     = ''; // TODO

            $insert['num_infos']     = $usersInfos[$user['id']]['num'] ?? 0;
            $insert['num_company']   = $usersCompanys[$user['id']]['num'] ?? 0;
            $insert['num_relations'] = $usersRelations[$user['id']]['num'] ?? 0;
            $insert['created_at']    = date('Y-m-d H:i:s');
            $insert['updated_at']    = date('Y-m-d H:i:s');
            // 插入数据
            $inserts[] = $insert;
        }
        // 二维数组排序
        array_multisort(
            array_column($inserts, 'num_infos'),
            SORT_DESC,
            array_column($inserts, 'num_company'),
            SORT_DESC,
            array_column($inserts, 'num_relations'),
            SORT_DESC,
            array_column($inserts, 'user_job_number'),
            SORT_ASC,
            $inserts
        );
        // 添加排名
        array_walk($inserts, function (&$val, $key) {
            $val['rank'] = $key + 1;
        });
        // 插入前先清空数据
        DB::table('gather_information_count')->truncate();
        GatherInformationCount::query()->insert($inserts);
    }
}
