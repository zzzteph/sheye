<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Schedule;
use App\Models\Template;
use App\Jobs\LaunchScanJob;
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
    protected $description = 'Command description';

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
		$schedulers=Schedule::where('frequency',$this->argument('frequency'))->get();
		foreach ($schedulers as $schedule) 
		{
			if($schedule->scope!==null)
			{
					LaunchScanJob::dispatch($schedule->scope,$schedule->template_id);
			}
		}
		
		
		

    }
}
