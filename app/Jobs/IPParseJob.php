<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class IPParseJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 17200;
	public $uniqueFor = 17200;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 
	 
	public $entry;
    public function __construct(ScopeEntry $entry)
    {
		$this->onQueue('listeners');
        $this->entry=$entry;

    }


    public function handle()
    {
		
		if($this->entry->type!=='ip_list')
		{
			return;
		}
		
	  $range = array();
	  $cidr = explode('/', $this->entry->source);
	  $start= (ip2long($cidr[0])) & ((-1 << (32 - (int)$cidr[1])));
	  $end = ($start) + pow(2, (32 - (int)$cidr[1])) - 1;
	  
	  for(;$start<$end;$start++)
	  {
		  
		   
		   $resource=new Resource;
		   $resource->name= long2ip($start);
		   $resource->type="ip";
		   $resource->scope_entry_id=$this->entry->id;
		   $resource->scope_id=$this->entry->scope->id;
		   $resource->save();	
	  }
	 

    }
}
