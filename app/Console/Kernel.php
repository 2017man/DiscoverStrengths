<?php

namespace App\Console;

use App\Console\Commands\Ceping;
use App\Console\Commands\CountInfo;
use App\Console\Commands\ImportCompany;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\SendEvaluateRecords;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // 'auto:import_company'     => ImportCompany::class,

        // 'auto:ceping' => Ceping::class, // 胖行帮统计

    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 每分钟执行统计数据
        // $schedule->command('auto:count_info')->everyMinute();
        // $schedule->command('evaluate:send')->monthlyOn(8, '09:00');

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
