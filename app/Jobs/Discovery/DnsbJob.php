<?php

namespace App\Jobs\Discovery;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Queue;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
class DnsbJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 3600;
	public $uniqueFor = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 
	 
	public $entry;
	public $scope_entry;
	public $scanner_path;
    public $scanner;
	public $time_limit;
	public $domain;
	public $dnsx;
	public $dnsx_worlist;
    public function __construct(Queue $entry)
    {
		$this->entry=$entry;
		$this->onQueue('discovery');
		$scope_entry= ScopeEntry::find($entry->object_id);
		if($scope_entry===null)
		{
			$entry->status='done';
			$entry->save();
			return;
		}
		

		
		$this->scope_entry=$scope_entry;
		$this->domain=$this->scope_entry->source;
		$this->scanner_path=base_path()."/scanners/";
		$this->dnsx=$this->scanner_path."dnsx";
		$this->dnsx_worlist=$this->scanner_path."final.txt";
		$this->time_limit=3000;
    }
	
	public function uniqueId()
    {
        return $this->entry->id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
	 
	 	function is_valid_domain_name($domain_name) {
		  return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
				  && preg_match("/^.{1,253}$/", $domain_name) //overall length check
				  && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	  }
	  

	 
    public function handle()
    {
		$this->entry->status='running';
		$this->entry->save();
		$this->domain=$this->domain;
		if($this->scope_entry->type=='domain_list')
		{
			$this->domain=trim(str_replace("*.","",$this->domain));
		}
		

		
				$process = new Process([
			$this->dnsx,
			'--silent',
			'-w',
			$this->dnsx_worlist,
			'-d',
			$this->domain,
			'-wd',
			$this->domain
		
		]);

		$process->setTimeout($this->time_limit);
		$process->start();
		while ($process->isRunning()) {

			try{
				$process->checkTimeout();
			}
			catch(ProcessTimedOutException $e)
			{
				break;
			}
			usleep(2000000);
		}
		
		$output="";
				if ($process->isSuccessful()) {
				
				$output=$process->getOutput(); 
		}
		
		
		
		$uniq_lines=array();
		if(strlen($output)<3)
		{
			
			$lines=explode(PHP_EOL,$output);
			foreach($lines as $line)
			{
				$line=trim($line);
				if(strpos($line,$this->domain)!==FALSE &&  str_ends_with($line,$this->domain) && $this->is_valid_domain_name($line))
					{
						if(Resource::where('scope_entry_id',$this->scope_entry->id)->where('name',$line)->first()==null)
						{
							if(!in_array($line,$uniq_lines))
								array_push($uniq_lines,$line);
						}
					}
			}
		}

		
		

		foreach($uniq_lines as $line)
		{

			$line=trim($line);

			if(strpos($line,$this->domain)!==FALSE &&  str_ends_with($line,$this->domain) && $this->is_valid_domain_name($line))
			{
				
				
				$resource=Resource::where('scope_entry_id',$this->scope_entry->id)->where('name',$line)->first();
				if($resource==null)
				{
							$resource=new Resource;
							$resource->name=$line;
							$resource->type="domain";
							$resource->scope_entry_id=$this->scope_entry->id;
							$resource->scope_id=$this->scope_entry->scope_id;
							$resource->save();				
				}
			}
		}

		$this->entry->status='done';
		$this->entry->save();
		
		
    }
}
