<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Resource;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Queue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class WipeQueueJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 3000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 
	 
	public $type;
	public $id;

    public function __construct($type,$id)
    {
		$this->type=$type;
		$this->id=$id;
		$this->onQueue('listeners');
    }

    public function handle()
    {
		if($this->type=='resource')
		{
			$resource=Resource::withTrashed()->where('id',$this->id)->firstOrFail();
			$queues=Queue::where('object_id',$resource->id)->where('object_type','resource')->get();
			foreach($queues as $queue)$queue->delete();
			foreach($resource->services as $service)
			{
					$queues=Queue::where('object_id',$service->id)->where('object_type','service')->get();
					foreach($queues as $queue)$queue->delete();
			}
		}
		
		if($this->type=='scope_entry')
		{
			$scope_entry=ScopeEntry::withTrashed()->where('id',$this->id)->firstOrFail();
			$queues=Queue::where('object_id',$scope_entry->id)->where('object_type','scope_entry')->get();
			foreach($queues as $queue)$queue->delete();
			
			$resources=Resource::withTrashed()->where('scope_entry_id',$scope_entry->id)->get();
			foreach($resources as $entry)
			{
				$queues=Queue::where('object_id',$entry->id)->where('object_type','resource')->get();
				foreach($queues as $queue)$queue->delete();
				foreach($entry->services as $service)
				{
						$queues=Queue::where('object_id',$service->id)->where('object_type','service')->get();
						foreach($queues as $queue)$queue->delete();
				}
				
			}

		
		}
		
		

		
    }
}
