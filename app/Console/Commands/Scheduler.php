<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Schedule;
use App\Models\Template;
use App\Jobs\LaunchScanJob;
use Illuminate\Support\Facades\Log;
class Scheduler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler:run {frequency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run schedule tasks for the monitor';

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
		
			Log::channel('sheduler')->debug('===============================RUN_SCHEDULE_TASKS=======================================================');
		
		$schedulers=Schedule::where('frequency',$this->argument('frequency'))->get();
		foreach ($schedulers as $schedule) 
		{
			if($schedule->scope!==null)
			{
							Log::channel('sheduler')->debug($this->argument('frequency').":".$schedule->scope->name." is run with template_id=".$schedule->template_id);
		
				
				
					LaunchScanJob::dispatch($schedule->scope,$schedule->template_id);
			}
		}
		
					Log::channel('sheduler')->debug('==================================================================================================');
		
		

    }
}
