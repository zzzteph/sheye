<?php

namespace App\Listeners;

use App\Events\NewScopeEntry;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\ScopeEntry;
use App\Models\Queue;
use App\Models\Template;
use App\Models\TemplateEntry;
use App\Models\Scanner;
use App\Models\ScannerEntry;
use App\Jobs\IPParseJob;
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
			
			$scope=$event->entry->scope;
			$template=$scope->scope_template->template;
			
			foreach($template->template_entries as $entry)
			{
			

				if($entry->scanner_entry->scanner->type!='discovery')continue;
				$tmp=new Queue;
				$tmp->object_type="scope_entry";
				$tmp->object_id=$event->entry->id;
				$tmp->user_id=$event->entry->scope->user_id;
				$tmp->scanner_entry_id=$entry->scanner_entry->id;
				$tmp->scope_id=$event->entry->scope_id;
				$tmp->type="chain";
				$tmp->save();	
			}

 
		}		
		
		if($event->entry->type=="ip_list")
		{
			
			IPParseJob::dispatch($event->entry);

 
		}	
		
		
		
    }
}
