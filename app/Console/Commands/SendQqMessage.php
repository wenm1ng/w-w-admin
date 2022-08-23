<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\BigData\Services\sendmessage\SendMessageService;
class SendQqMessage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:sendnews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定时报警';

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
    public function handle(SendMessageService $service)
    {
        $service->newEarly();
        return true;
    }
}
