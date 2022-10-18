<?php

namespace App\Listeners;

use App\Events\NewService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Service;
use App\Models\Resource;
use App\Models\ScopeEntry;
use App\Models\Scope;
use App\Models\Queue;
use Illuminate\Support\Facades\Bus;
class ServiceNotification
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
     * @param  \App\Events\NewScreen  $event
     * @return void
     */
    public function handle(NewService $event)
    {
			$service=Service::find($event->service->id);
			if($service!==null && $service->resource!=null &&  $service->resource->scope_entry!=null && $service->resource->scope_entry->scope!=null)
			{
				$tmp=new Queue;
				$tmp->object_type="service";
				$tmp->object_id=$service->id;
				$tmp->user_id=$service->resource->scope_entry->scope->user_id;
				$tmp->type="analyze";
				$tmp->scope_id=$service->resource->scope_entry->scope->id;
				$tmp->save();
			}
    }
}
