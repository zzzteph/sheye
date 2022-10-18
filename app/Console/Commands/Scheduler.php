<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Schedule;
use App\Models\Queue;
use App\Models\Report;
use App\Models\User;
use App\Models\Service;
use App\Models\Screenshot;

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
					foreach($schedule->scope->scope_entries as $entry)
					{
						$tmp=new Queue;
						$tmp->object_type="scope_entry";
						$tmp->object_id=$entry->id;
						$tmp->user_id=$entry->scope->user_id;
						$tmp->type="asset";
						$tmp->scope_id=$entry->scope_id;
						$tmp->save();
						
						
												$tmp=new Queue;
						$tmp->object_type="scope_entry";
						$tmp->object_id=$entry->id;
						$tmp->user_id=$entry->scope->user_id;
						$tmp->type="subfinder";
						$tmp->scope_id=$entry->scope_id;
						$tmp->save();
						
						
												$tmp=new Queue;
						$tmp->object_type="scope_entry";
						$tmp->object_id=$entry->id;
						$tmp->user_id=$entry->scope->user_id;
						$tmp->type="wayback";
						$tmp->scope_id=$entry->scope_id;
						$tmp->save();
						
						
												$tmp=new Queue;
						$tmp->object_type="scope_entry";
						$tmp->object_id=$entry->id;
						$tmp->user_id=$entry->scope->user_id;
						$tmp->type="amass";
						$tmp->scope_id=$entry->scope_id;
						$tmp->save();

					}
			}
		}
		
		
		

    }
}
