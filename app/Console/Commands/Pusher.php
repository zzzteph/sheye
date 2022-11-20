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
		foreach ($users as $user) //get users before running
		{

			$user_package[$user->id]=env('MAX_TASKS')-$user->count_active_tasks;	

			if($max<$user_package[$user->id])$max=$user_package[$user->id];
			echo "USER:".$user->id.":".$user_package[$user->id].PHP_EOL;
			
		}
		echo "Maximum:$max".PHP_EOL;
		
		for($i=0;$i<$max;$i++)
			foreach ($users as $user) 
			{
				if($user_package[$user->id]<=0)continue;
				foreach($user->command_queues()->where('status','todo')->get() as $queue)
				{
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

				foreach($user->queues()->where('status','todo')->where('object_type','scope_entry')->get() as $queue)
				{
					
					$scope_entry = ScopeEntry::find($queue->object_id);//where('id', )->get();
					if($scope_entry!==null && $scope_entry->scope!=null)
					{
						$queue->status="queued";
						$queue->save();
						//generating reflection
						$job_class = $queue->scanner_entry->scanner->class;
						$job=null;
						if($queue->scanner_entry->scanner->has_arguments)
						{
							$job_class::dispatch($queue,$queue->scanner_entry->arguments);
						}
						else
						{
							$job_class::dispatch($queue);
						}
					
			
					

						$user_package[$user->id]--;
						echo "Pushed".PHP_EOL;
						break;
					}
					else
					{
						$queue->delete();
					}
				}
				
				if($user_package[$user->id]<=0)continue;
				
				foreach($user->queues()->where('status','todo')->get() as $queue)
				{
					if($queue->object_type!='resource' && $queue->object_type!='service')continue;
					
					if($queue->object_type=='resource')
					{
						$resource = Resource::find($queue->object_id);//where('id', )->get();

						if($resource!==null && $resource->scope_entry!=null && $resource->scope_entry->scope!=null)
						{
							$queue->status="queued";
							$queue->save();
							//generating reflection
							$job_class = $queue->scanner_entry->scanner->class;
							$job=null;
							if($queue->scanner_entry->scanner->has_arguments)
							{
								$job_class::dispatch($queue,$queue->scanner_entry->arguments);
							}
							else
							{
								$job_class::dispatch($queue);
							}

							$user_package[$user->id]--;
							echo "Pushed".PHP_EOL;
							break;
						}
						else
						{
							$queue->delete();
						}

					}

					
					if($queue->object_type=='service')
					{

						$service = Service::find($queue->object_id);
					
						if($service!==null && $service->resource!=null && $service->resource->scope_entry!=null && $service->resource->scope_entry->scope!=null)
						{
							
							$queue->status="queued";
							$queue->save();
							//generating reflection
							$job_class = $queue->scanner_entry->scanner->class;
							
							if($queue->scanner_entry->scanner->has_arguments)
							{
								$job_class::dispatch($queue,$queue->scanner_entry->arguments);
							}
							else
							{
								$job_class::dispatch($queue);
							}
							
							$user_package[$user->id]--;
							echo "Pushed".PHP_EOL;
							break;
						}
						else
						{
							$queue->delete();
						}
					}	
					
				}
		
			}
    }
}
