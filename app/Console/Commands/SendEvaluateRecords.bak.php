<?php

namespace App\Console\Commands;

use App\Models\CompanyConfig;
use App\Models\EvaluateDetail;
use App\Models\EvaluateRecord;
use App\Models\Member;
use App\Models\RoleUser;
use App\Models\EvaluateConfig;
use App\Models\UserMap;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Tests\Models\User;

class SendEvaluateRecordsBank extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'evaluate:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '每月定时生成评价模板给指定人';

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
        // 插入脚本

    }

    public function useMap()
    {

        $clients = UserMap::query()->where('dept', '<>', '重客中心')->get()->toArray();
        $num     = 0;
        foreach ($clients as $client) {
            $roleId = 5;// 行业总监
            $user   = Member::query()
                ->where('name', $client['member_name'])
                ->where('department', $client['dept'])
                ->first();
            if (trim($client['job']) == '客户经理') {
                $roleId = 2;
            }
            if (!empty($user) && !empty($user['id'])) {
                $user = $user->toArray();
                $num++;
                $inserRole = [
                    'user_id'    => $user['id'],
                    'role_id'    => $roleId,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
                RoleUser::query()->insert($inserRole);
            } else {
                Log::info('---------错误行业数据-----', $client);
            }


            dump($num);
        }
    }

    public function send()
    {
        // 获取模板数据
        $configs = EvaluateConfig::query()->get(['dimension', 'measure', 'score'])->toArray();
        // 获取评价人数据（客户经理、行业总监、分管领导）
        //$evaluators = Admin::user()->inRoles(['CustomerManager', 'Director', 'Leadership']);
        $evaluators = Member::query()
            ->join('admin_role_users', 'admin_users.id', '=', 'admin_role_users.user_id')
            ->whereIn('admin_role_users.role_id', [2, 5, 6])
            ->get(['id', 'department', 'name'])
            ->toArray();
        // 获取评估人信息
        $supporters = Member::query()->where('map_area', '<>', '')->get(['id', 'map_area'])->toArray();
        // TODO 插入一条评价记录
        $evaluators = collect($evaluators)->groupBy('department')->toArray();
        $supporters = collect($supporters)->groupBy('map_area')->toArray();

        if (false) {
            foreach ($evaluators as $department => $evaluator) {
                foreach ($evaluator as $evaluatorVal) {
                    $evaluatorId = $evaluatorVal['id'];
                    if (!empty($supporters[$department])) {
                        $supporter = $supporters[$department];
                        foreach ($supporter as $supporterVal) {
                            $examineeId       = $supporterVal['id'];
                            $records          = [
                                'evaluator'      => $evaluatorId,// 考核人
                                'examinee'       => $examineeId,// 被考核人
                                'evaluate_at'    => date('Y-m'),
                                'evaluate_month' => date('m'),
                                'is_know'        => EvaluateRecord::IS_KNOW_YES,
                                'total_score'    => 0,
                                'evaluate_state' => EvaluateRecord::EVALUATE_STATE_INIT,
                                'created_at'     => date('Y-m-d H:i:s'),
                                'updated_at'     => date('Y-m-d H:i:s'),
                            ];
                            $evaluateRecordId = EvaluateRecord::query()->insertGetId($records);
                            //  插入评价记录对应评价模板数据
                            array_walk($configs, function (&$configVal) use ($evaluateRecordId) {
                                $configVal['evaluate_id'] = $evaluateRecordId;
                                $configVal['created_at']  = date('Y-m-d H:i:s');
                                $configVal['updated_at']  = date('Y-m-d H:i:s');
                            });
                            EvaluateDetail::query()->insert($configs);
                        }
                    }
                }
            }
        }
    }
}
