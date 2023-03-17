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
