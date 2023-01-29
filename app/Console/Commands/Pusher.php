<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Queue;
use App\Models\CommandQueue;
use App\Models\Report;
use App\Models\User;
use App\Models\Service;
use App\Models\Screenshot;

use App\Jobs\Discovery\AmassJob;
use App\Jobs\Discovery\SubfinderJob;
use App\Jobs\Discovery\WayBack;
use App\Jobs\Discovery\AssetJob;
use App\Jobs\Discovery\DnsbJob;

use App\Jobs\Commands\AmassCommand;
use App\Jobs\Commands\AssetCommand;
use App\Jobs\Commands\GauCommand;
use App\Jobs\Commands\SubfinderCommand;
use App\Jobs\Commands\DnsbCommand;
use App\Jobs\Commands\NucleiCommand;
use App\Jobs\Commands\DirsearchCommand;
use App\Jobs\Commands\NmapCommand;
use App\Jobs\Resource\NmapResourceScan;
use App\Jobs\Resource\AnalyzeService;
use App\Jobs\Resource\NucleiCriticalScan;
use App\Jobs\Resource\NucleiHighScan;
use App\Jobs\Resource\NucleiMediumScan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Bus;
class Pusher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:queues';

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

	function analyze(Queue $entry)
	{

		return AnalyzeService::dispatch($entry);
	}


	function nmap_nuclei(Queue $entry)
	{
		Bus::chain([
				new NmapResourceScan($entry),
				new NucleiCriticalScan($entry),
				new NucleiHighScan($entry),
				new NucleiMediumScan($entry)

			])->dispatch();
		
		return;
	}


	function nuclei(Queue $entry)
	{
				Bus::chain([

				new NucleiCriticalScan($entry),
				new NucleiHighScan($entry),
				new NucleiMediumScan($entry)

			])->dispatch();

		return;
	}
	function nmap(Queue $entry)
	{
		return NmapResourceScan::dispatch($entry);
	}
	function asset(Queue $entry)
	{
		return AssetJob::dispatch($entry);
	}
	function subfinder(Queue $entry)
	{
		return SubfinderJob::dispatch($entry);
	}
		function wayback(Queue $entry)
	{
		return WayBack::dispatch($entry);
	}
			function dnsb(Queue $entry)
	{
		return DnsbJob::dispatch($entry);
	}

	
	
	function amass(Queue $entry)
	{
		return AmassJob::dispatch($entry);
	}
	
	
				function nuclei_command(CommandQueue $entry)
	{
		return NucleiCommand::dispatch($entry);
	}
	

			function  dnsb_command(CommandQueue $entry)
	{
		return DnsbCommand::dispatch($entry);
	}

	
		function amass_command(CommandQueue $entry)
	{
		return AmassCommand::dispatch($entry);
	}
	
	
		function asset_command(CommandQueue $entry)
	{
		return AssetCommand::dispatch($entry);
	}
	function subfinder_command(CommandQueue $entry)
	{
		return SubfinderCommand::dispatch($entry);
	}
		function gau_command(CommandQueue $entry)
	{
		return GauCommand::dispatch($entry);
	}
			function dirsearch_command(CommandQueue $entry)
	{
		return DirsearchCommand::dispatch($entry);
	}
	
				function nmap_command(CommandQueue $entry)
	{
		return NmapCommand::dispatch($entry);
	}
	

	
    public function handle()
    {
		
		$users=array();
		$max=0;
		$users=User::all();
		Log::channel('push:queues')->debug('===============================PUSH:QUEUES=======================================================');
		Log::channel('push:queues')->debug('Starting collection users');
		Log::channel('push:queues')->debug('MAX_TASKS :'.env('MAX_TASKS'));
		foreach ($users as $user) //get users before running
		{
			
			$user_package[$user->id]=env('MAX_TASKS')-$user->count_active_tasks;	
			Log::channel('push:queues')->debug('USER:'.$user->id." has active tasks ".$user->count_active_tasks);
			Log::channel('push:queues')->debug('USER:'.$user->id." has free tasks ".$user_package[$user->id]);

			if($max<$user_package[$user->id])$max=$user_package[$user->id];
			
		}
		Log::channel('push:queues')->debug("Maximum tasks to run:".$max);
		
		for($i=0;$i<$max;$i++)
			foreach ($users as $user) 
			{
				Log::channel('push:queues')->debug("Running tasks for user:".$user->id);
				if($user_package[$user->id]<=0)continue;
				foreach($user->command_queues()->where('status','todo')->limit(env('MAX_TASKS'))->get() as $queue)
				{
					Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Command-queue task:".$queue->id." with type ".$queue->type." (".$user->id.")");
					$queue->status="queued";
					$queue->save();
					switch($queue->type)
					{
						case "assetfinder":	$this->asset_command($queue);break;
						case "amass":	$this->amass_command($queue);break;
						case "subfinder":$this->subfinder_command($queue);break;
						case "gau":$this->gau_command($queue);break;
						case "dnsb":$this->dnsb_command($queue);break;
						case "nuclei":$this->nuclei_command($queue);break;
						case "dirsearch":$this->dirsearch_command($queue);break;
						case "nmap":$this->nmap_command($queue);break;
					}
					
					$user_package[$user->id]--;
					break;					
				}
				
				if($user_package[$user->id]<=0)continue;
				Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Command queues done, running general queue");
				foreach($user->queues()->where('status','todo')->where('object_type','scope_entry')->limit(env('MAX_TASKS'))->get() as $queue)
				{
					Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Scope Entry object:".$queue->object_id);
					$scope_entry = ScopeEntry::find($queue->object_id);//where('id', )->get();
					if($scope_entry!==null && $scope_entry->scope!=null)
					{
						
						$queue->status="queued";
						$queue->save();
						Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Scope Entry object:".$queue->object_id." status:".$queue->status);
						//generating reflection
						$job_class = $queue->scanner_entry->scanner->class;
						
						Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Scope Entry object:".$queue->object_id." class :".$job_class);
						
						
						if($queue->scanner_entry->scanner->has_arguments)
						{
							$job_class::dispatch($queue,$queue->scanner_entry->arguments);
						}
						else
						{
							$job_class::dispatch($queue);
						}
					
			
					
						$user_package[$user->id]--;
						Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Scope Entry object:".$queue->object_id." dispatched");
						
						echo "Pushed".PHP_EOL;
						break;
					}
					else
					{
						Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Scope Entry object:".$queue->object_id."   was deleted due to failed to find");
						$queue->delete();
					}
				}
				
				if($user_package[$user->id]<=0)continue;
				
				foreach($user->queues()->where('status','todo')->limit(env('MAX_TASKS'))->get() as $queue)
				{

					
					if($queue->object_type!='resource' && $queue->object_type!='service')continue;
					
					if($queue->object_type=='resource')
					{
					   Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Resource Entry object:".$queue->object_id);
					
						
						
						
						$resource = Resource::find($queue->object_id);//where('id', )->get();

						if($resource!==null && $resource->scope_entry!=null && $resource->scope_entry->scope!=null)
						{
							$queue->status="queued";
							$queue->save();
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Resource Entry object:".$queue->object_id." status:".$queue->status);
							//generating reflection
							$job_class = $queue->scanner_entry->scanner->class;
						
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Resource Entry object:".$queue->object_id." class :".$job_class);
						
							if($queue->scanner_entry->scanner->has_arguments)
							{
								$job_class::dispatch($queue,$queue->scanner_entry->arguments);
							}
							else
							{
								$job_class::dispatch($queue);
							}
							$user_package[$user->id]--;
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Resource Entry object:".$queue->object_id." dispatched");
							
							echo "Pushed".PHP_EOL;
							break;
						}
						else
						{
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Resource Entry object:".$queue->object_id."   was deleted due to failed to find");
							$queue->delete();
						}

					}

					
					if($queue->object_type=='service')
					{
						Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Service Entry object:".$queue->object_id);
						$service = Service::find($queue->object_id);
					
						if($service!==null && $service->resource!=null && $service->resource->scope_entry!=null && $service->resource->scope_entry->scope!=null)
						{
							
							$queue->status="queued";
							$queue->save();
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Service Entry object:".$queue->object_id." status:".$queue->status);
							//generating reflection
							$job_class = $queue->scanner_entry->scanner->class;
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Service Entry object:".$queue->object_id." class :".$job_class);
							if($queue->scanner_entry->scanner->has_arguments)
							{
								$job_class::dispatch($queue,$queue->scanner_entry->arguments);
							}
							else
							{
								$job_class::dispatch($queue);
							}
							$user_package[$user->id]--;
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Service Entry object:".$queue->object_id." dispatched");
							
							echo "Pushed".PHP_EOL;
							break;
						}
						else
						{
							Log::channel('push:queues')->debug("User(".$user->id.",".$user_package[$user->id].")  "."Service Entry object:".$queue->object_id."   was deleted due to failed to find");
							$queue->delete();
						}
					}	
					
				}
		
			}
			
			Log::channel('push:queues')->debug('=================================================================================================');
			
			
    }
}
