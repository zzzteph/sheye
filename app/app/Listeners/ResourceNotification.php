<?php

namespace App\Listeners;

use App\Events\NewResource;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\Resource;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Queue;
use App\Models\Template;
use App\Models\TemplateEntry;
use App\Models\Scanner;
use App\Models\ScannerEntry;
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
		
			$scope=$resource->scope_entry->scope;
			$template=$scope->scope_template->template;
				
				foreach($template->template_entries as $entry)
				{
				

					if($entry->scanner_entry->scanner->type!='resource')continue;
					$tmp=new Queue;
					$tmp->object_type="resource";
					$tmp->object_id=$resource->id;
					$tmp->user_id=$resource->scope_entry->scope->user_id;
					$tmp->scanner_entry_id=$entry->scanner_entry->id;
					$tmp->scope_id=$resource->scope_entry->scope->id;
									$tmp->type="chain";
					$tmp->save();	
				}

			}

    }
}
