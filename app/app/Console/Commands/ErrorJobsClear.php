<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Response;
use App\Models\Queue;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use App\Models\CommandQueue;
use Illuminate\Support\Facades\DB;

class ErrorJobsClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wipe:queue';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove jobs that stuck in queue';

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
		
		
		$queues=CommandQueue::where('status','running')->get();
		foreach($queues as $queue)
		{

			if($queue->updated_at->diffInHours(Carbon::now())>2)
			{
				$queue->status='error';
				$queue->save();
			}

		}
		
		
		
		
		$queues=Queue::where('status','running')->get();
		foreach($queues as $queue)
		{
			if($queue->object_type=='scope_entry')
			{
				if($queue->updated_at->diffInHours(Carbon::now())>1)
				{
					$queue->status='done';
					$queue->save();
				}
			}
			else
			{
				if($queue->updated_at->diffInMinutes(Carbon::now())>5)
				{
					$queue->status='done';
					$queue->save();
				}
			}
		}
		
		/*
		$max_execution_time=3600;
		foreach (User::all() as $user) //get users before running
		{
			$max_execution_time+=($user->env('MAX_TASKS')*3600);	
		}
		*/
		$queues=Queue::where('status','queued')->get();
		foreach ($queues as $queue) 
		{
			if( $queue->status!='queued')continue;
			
			if($queue->created_at->diffInSeconds(Carbon::now())>7200)
			{
				$queue->status='done';
					$queue->save();
			}
		}
		
		$queues=CommandQueue::where('status','queued')->get();
		foreach($queues as $queue)
		{

			if($queue->updated_at->diffInSeconds(Carbon::now())>7200)
			{
				$queue->status='error';
				$queue->save();
			}

		}
		
		
		
		
		
		//wipe everything
		foreach (Queue::all() as $queue) 
		{
			if($queue->object_type=='resource')
			{
				$resource = Resource::find($queue->object_id);
				if($resource==null || $resource->scope_entry==null || $resource->scope_entry->scope==null)
				{
					$queue->delete();
				}
				
			}
			if($queue->object_type=='service')
			{
				$service = Service::find($queue->object_id);
				if($service->resource==null || $service->resource->scope_entry==null || $service->resource->scope_entry->scope==null)
				{
					$queue->delete();
				}	
			}
			
			if($queue->object_type=='scope_entry')
			{
				$scope_entry = ScopeEntry::find($queue->object_id);
				if( $scope_entry==null || $scope_entry->scope==null)
				{
					$queue->delete();
				}	
			}
			
			
		}
		
		//QUEUD but not running

		
		
		
		
		

    }
}
