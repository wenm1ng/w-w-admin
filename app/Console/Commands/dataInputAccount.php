<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Admin\Services\report\ReportService;

class dataInputAccount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dataInputAccount';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '用户数据 入库';

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
    public function handle(ReportService $service)
    {
        $service->getClentData();
        return true;
    }
}
