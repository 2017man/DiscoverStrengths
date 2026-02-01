<?php

namespace App\Console\Commands;


use App\Exports\CepingCountExport;
use App\Exports\CepingCountGbExport;
use App\Exports\CepingMinyiExport;
use App\Service\CepingService;
use Dcat\Admin\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

/**
 * 统计收集数据信息
 * Class CountInfo
 * @package App\Console\Commands
 */
class Ceping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:ceping';

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
        $startTime = time();
        $this->info('开始执行测评统计任务...');

        try {
            $cepingService = new CepingService();

            // 执行统计
            $this->info('开始执行 count() 统计...');
            $cepingService->count();
            $this->info('count() 统计完成');

            // 执行简要统计
            $this->info('开始执行 brief() 统计...');
            $cepingService->brief();
            $this->info('brief() 统计完成');

            // 执行民意调查统计
            $this->info('开始执行 countMinyi() 统计...');
            $cepingService->countMinyi();
            $this->info('countMinyi() 统计完成');

            // 导出数据
            $this->info('开始导出数据...');
            $this->export();
            $this->info('班子测评汇总导出完成');

            $this->exportGb();
            $this->info('干部测评汇总导出完成');

            $this->exportMinyi();
            $this->info('民意调查汇总导出完成');

            $endTime = time();
            $duration = $endTime - $startTime;
            $this->info("任务执行完成！总耗时：{$duration}秒 (" . round($duration / 60, 2) . "分钟)");
        } catch (\Exception $e) {
            $this->error('任务执行失败：' . $e->getMessage());
            $this->error('错误堆栈：' . $e->getTraceAsString());
            Log::error('Ceping任务执行失败', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }


    /**
     *  巡检记录数据的导出
     * @param Request $request
     * @return BinaryFileResponse
     */
    public function export()
    {
        $filename = '班子测评汇总';
        // 这里面有一个 InspectionItemPostExport 文件，接下来通过 php artisan make:export 命令创建
        Excel::store(new CepingCountExport(), $filename . time() . '.xlsx');
    }

    public function exportGb()
    {
        $filename = '干部测评汇总';
        // 这里面有一个 InspectionItemPostExport 文件，接下来通过 php artisan make:export 命令创建
        Excel::store(new CepingCountGbExport(), $filename . time() . '.xlsx');
    }

    public function exportMinyi()
    {
        $filename = '民意调查汇总';
        // 导出民意调查统计数据
        Excel::store(new CepingMinyiExport(), $filename . time() . '.xlsx');
    }
}
