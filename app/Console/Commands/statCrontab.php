<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\Services\PlatformStatCrontab;

class statCrontab extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:platformStatCrontab';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '智投手、UD等平台日统计数据';

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
     * @return int
     */
    public function handle()
    {
        adminLog('statCronTab', date('Y-m-d H:i:s').'开始');
        (new PlatformStatCrontab())->platformStatRun();
        adminLog('statCronTab', date('Y-m-d H:i:s').'结束');
        return true;
    }
}
