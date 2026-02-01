<?php

namespace App\Console\Commands;


use App\Exports\CepingCountExport;
use App\Exports\CepingCountGbExport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Tests\Models\User;

/**
 * 统计收集数据信息
 * Class CountInfo
 * @package App\Console\Commands
 */
class ExportCepingCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:ceping_count';

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

    public function handle()
    {
        $this->export();
        $this->exportGb();
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

}
