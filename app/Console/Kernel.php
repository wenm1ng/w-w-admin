<?php

namespace App\Console;

use App\Console\Commands\SendQqMessage;
use App\Console\Commands\SendPlanMessage;
use App\Console\Commands\statCrontab;
use App\Console\Commands\TestCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
//        TestCommand::class,
        SendQqMessage::class,
        SendPlanMessage::class,
        statCrontab::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
//        $schedule->command('SendQqMessage')->everyMinute();
//        $schedule->command('SendPlanMessage')->everyMinute();
        $schedule->command('command:platformStatCrontab')->everyThirtyMinutes();
//        $schedule->command('dataInputAccount')->everyFiveMinutes();
//        $schedule->command('dataInputPlan')->everyFiveMinutes();
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
