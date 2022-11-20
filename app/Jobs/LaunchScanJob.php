<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Queue;
use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class LaunchScanJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 1800;
	public $uniqueFor = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */

	public $scope;
	public $template_id;
    public function __construct(Scope $scope,$template_id=false)
    {
		$this->scope=$scope;
		$this->onQueue('listeners');
		$this->template_id=$template_id;
    }
	
	public function uniqueId()
    {
        return $this->scope->id;
    }
	 
    public function handle()
    {
		
			if($this->template_id===FALSE)
				$template=$this->scope->scope_template->template;
			else
				$template=Template::where('id',$this->template_id)->first();
			if($template==null)return 1;
			//scope_entries
			foreach($this->scope->scope_entries as $scope_entry)
			{
				foreach($template->template_entries as $entry)
				{
					if($entry->scanner_entry->scanner->type!='discovery')continue;
					$tmp=new Queue;
					$tmp->object_type="scope_entry";
					$tmp->object_id=$scope_entry->id;
					$tmp->user_id=$this->scope->user_id;
					$tmp->scanner_entry_id=$entry->scanner_entry->id;
					$tmp->scope_id=$this->scope->id;
					$tmp->type="chain";
					$tmp->save();	
				}
	
			}
			//resources
			foreach($this->scope->scope_entries as $scope_entry)
			{
				foreach($scope_entry->resources as $resource)
				{
					foreach($template->template_entries as $entry)
					{
						if($entry->scanner_entry->scanner->type!='resource')continue;
						$tmp=new Queue;
						$tmp->object_type="resource";
						$tmp->object_id=$resource->id;
						$tmp->user_id=$this->scope->user_id;
						$tmp->scanner_entry_id=$entry->scanner_entry->id;
						$tmp->scope_id=$this->scope->id;
						$tmp->type="chain";
						$tmp->save();	
					}
				}
	
			}
			//services
			foreach($this->scope->scope_entries as $scope_entry)
			{
				foreach($scope_entry->resources as $resource)
				{
					foreach($resource->services as $service)
					{
						foreach($template->template_entries as $entry)
						{
							if($entry->scanner_entry->scanner->type!='service')continue;
							$tmp=new Queue;
							$tmp->object_type="service";
							$tmp->object_id=$service->id;
							$tmp->user_id=$this->scope->user_id;
							$tmp->scanner_entry_id=$entry->scanner_entry->id;
							$tmp->scope_id=$this->scope->id;
							$tmp->type="chain";
							$tmp->save();	
						}
					}
				}
	
			}
			
			
			
			
		
    }
}
