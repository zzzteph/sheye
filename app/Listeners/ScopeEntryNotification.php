<?php

namespace App\Listeners;

use App\Events\NewScopeEntry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ScopeEntry;
use App\Models\Queue;
use Illuminate\Support\Facades\Bus;
class ScopeEntryNotification implements ShouldQueue
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
     * @param  \App\Events\NewScope  $event
     * @return void
     */
    public function handle(NewScopeEntry $event)
    {		
		if($event->entry->type=="domain_list" || $event->entry->type=="domain")
		{
			$tmp=new Queue;
			$tmp->object_type="scope_entry";
			$tmp->object_id=$event->entry->id;
			$tmp->user_id=$event->entry->scope->user_id;
			$tmp->type="asset";
			$tmp->scope_id=$event->entry->scope_id;
			$tmp->save();
			
			$tmp=new Queue;
			$tmp->object_type="scope_entry";
			$tmp->object_id=$event->entry->id;
			$tmp->user_id=$event->entry->scope->user_id;
			$tmp->type="subfinder";
			$tmp->scope_id=$event->entry->scope_id;
			$tmp->save();
			
			$tmp=new Queue;
			$tmp->object_type="scope_entry";
			$tmp->object_id=$event->entry->id;
			$tmp->user_id=$event->entry->scope->user_id;
			$tmp->type="wayback";
			$tmp->scope_id=$event->entry->scope_id;
			$tmp->save();
			
						$tmp=new Queue;
			$tmp->object_type="scope_entry";
			$tmp->object_id=$event->entry->id;
			$tmp->user_id=$event->entry->scope->user_id;
			$tmp->type="dnsb";
			$tmp->scope_id=$event->entry->scope_id;
			$tmp->save();
			
			$tmp=new Queue;
			$tmp->object_type="scope_entry";
			$tmp->object_id=$event->entry->id;
			$tmp->user_id=$event->entry->scope->user_id;
			$tmp->type="amass";
			$tmp->scope_id=$event->entry->scope_id;
			$tmp->save();
 
		}		
    }
}
