<?php

namespace App\Listeners;

use App\Events\NewResource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Resource;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Queue;
use Illuminate\Support\Facades\Bus;
class ResourceNotification implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
	public $queue = 'listeners';
    /**
     * Handle the event.
     *
     * @param  \App\Events\NewResource  $event
     * @return void
     */
    public function handle(NewResource $event)
    {
			$resource=Resource::where("id",$event->resource->id)->first();
			if($resource!==null && $resource->scope_entry!=null && $resource->scope_entry->scope!=null)
			{
				$tmp=new Queue;
				$tmp->object_type="resource";
				$tmp->object_id=$resource->id;
				$tmp->user_id=$resource->scope_entry->scope->user_id;
				$tmp->type="nmap";
				if(env("AUTO_NUCLEI", "false")==true)
				$tmp->type="nmap_nuclei";
				
				
				$tmp->scope_id=$resource->scope_entry->scope->id;
				$tmp->save();
			}

    }
}
