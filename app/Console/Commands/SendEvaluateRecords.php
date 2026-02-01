<?php

namespace App\Console\Commands;

use App\Models\EvaluateRecord;
use App\Models\Member;
use App\Models\EvaluateConfig;
use App\Utils\ChinaMobileAaas\SMS;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendEvaluateRecords extends Command
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
    protected $description = '每月1号定时生成评测数据';

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
        // 生成评测记录
        $this->createRecords();
    }

    /**
     * 生成当月记录
     */
    public function createRecords()
    {
        // 获取模板数据
        $configs   = EvaluateConfig::query()->get(['dimension', 'field', 'score', 'measure',])->toArray();
        $totalGoal = array_sum(array_column($configs, 'score'));
        // 获取评价人数据（客户经理、行业总监、分管领导）
        $evaluators = Member::query()
            ->join('admin_role_users as role', 'admin_users.id', '=', 'role.user_id')
            ->whereIn('role.role_id', [2, 5])
            ->get(['admin_users.id', 'admin_users.name', 'admin_users.department', 'admin_users.station', 'admin_users.phone'])
            ->toArray();
        // 获取支撑人员
        $dictMembers = Member::query()
            ->join('admin_role_users as role', 'admin_users.id', '=', 'role.user_id')
            ->where('role.role_id', 7)
            ->get(['admin_users.id', 'admin_users.name', 'admin_users.department', 'admin_users.station',])
            ->toArray();
        // 上个月
        $evaluateTime = Carbon::now()->subMonth(1)->toDateString();


        foreach ($evaluators as $evaluator) {
            $records = [];
            foreach ($dictMembers as $dictMember) {
                $record    = [
                    'evaluator'      => $evaluator['id'],// 考核人
                    'examinee'       => $dictMember['id'],// 被考核人
                    'evaluate_state' => EvaluateRecord::EVALUATE_STATE_TODO,
                    'evaluate_at'    => substr($evaluateTime, 0, 7),
                    'evaluate_month' => substr($evaluateTime, 5, 2),
                    'total_score'    => 0,
                    'total_goal'     => $totalGoal,
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s'),
                ];
                $records[] = $record;
            }
            // 插入记录
            EvaluateRecord::query()->insert($records);
            $this->sendSms($evaluator['phone'], $evaluator['name'], $evaluateTime, $evaluator['id'], substr($evaluateTime, 0, 7));
        }
    }

    /**
     * 发送短信
     */
    public function sendSms($mobile, $name, $evaluateTime, $customerId, $evaluateAt)
    {
        $type    = 'evaluate_console';
        $content = '尊敬的移动员工' . $name . '，您好！

根据分公司安排，现对' . substr($evaluateTime, 0, 4) . '年' . substr($evaluateTime, 5, 2) . '月政企后端支撑人员支撑情况进行评测。
评测地址为：http://36.133.101.10:82/#/pages/evaluate/index?customer_id=' . $customerId . '&evaluate_at=' . $evaluateAt . ' 
.请各位同事在【48小时内】以公平公正的态度对政企事业部员工进行客观公正的评价，以便我部及时改进工作方式，提升工作效率，***各位打分均保密***。

感谢您的支持与配合！';
        SMS::sendSMS($mobile, $content, $type);
    }

}
