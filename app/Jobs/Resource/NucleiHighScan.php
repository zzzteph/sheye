<?php

namespace App\Jobs\Resource;

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
use App\Models\Service;
use App\Models\Finding;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
class NucleiHighScan implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 2400;
	public $uniqueFor = 2400;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 public $entry;
	public $resource;
	private $time_limit;
	public $scanner_path;
    public $scanner;
	public $template_path;
	public $severity;
    public function __construct(Queue $entry)
    {
		$this->onQueue('resource');
		$this->entry=$entry;
		$resource= Resource::find($entry->object_id);
		if($resource===null)
		{
			$entry->status='done';
			$entry->save();
			return;
		}
		
		
		
        $this->resource=$resource;
		$this->time_limit=1200;
		$this->severity='high';
		$this->scanner_path=base_path()."/scanners/";
		$this->template_path=$this->scanner_path."/nuclei-templates";
		$this->scanner=$this->scanner_path."nuclei";		

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
    public function handle()
    {
		$this->entry->status='running';
		$this->entry->save();
		
		$resource= Resource::find($this->resource->id);
		$scope_entry= ScopeEntry::find($resource->scope_entry_id);
		$scope= Scope::find($scope_entry->scope_id);
		
		if($resource===null || $scope_entry===null || $scope===null)
		{
			$this->entry->status='done';
			$this->entry->save();
			return;
		}
		
		$list_name=Str::random(40);
		$list = storage_path('app/public')."/".$list_name;
		$fp = fopen($list, 'a');
		$exist=FALSE;
		foreach($this->resource->services as $service)
		{
			if(	strpos($service->service,"http")!==FALSE)
			{
				if(strpos($service->service,"http")!==FALSE && strpos($service->service,"https")===FALSE && strpos($service->service,"ssl")===FALSE)
				{
					$exist=TRUE;
					$url="http://".$this->resource->name.":".$service->port."/";
					fwrite($fp, $url.PHP_EOL);
					
				}
				else if(strpos($service->service,"http")!==FALSE && (strpos($service->service,"https")!==FALSE || strpos($service->service,"ssl")!==FALSE))
				{
					$exist=TRUE;
					$url="https://".$this->resource->name.":".$service->port."/";
					fwrite($fp, $url.PHP_EOL);
				}
			}
		}

		if($exist)
		fwrite($fp, $this->resource->name.PHP_EOL);
		fclose($fp);		









		$report_name="nuclei".Str::random(40).".txt";
		$report = storage_path('app/public')."/".$report_name;

	
		
				$process = new Process([
			$this->scanner,
			'-nc',
			'-ud',
			$this->template_path,
			'-s',
			$this->severity,
			'-eid','expired-ssl,laravel-debug-enabled,CVE-2017-5487,CVE-2016-10940,CVE-2017-10271,CVE-2020-35489,CVE-2018-15473,CVE-2016-6210,unauthenticated-varnish-cache-purge,graphql-alias-batching,CVE-2021-41349',
			'-json',
			'-o',
			$report,
			'-l',
			$list
		
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
		



			$finding=Finding::where('resource_id',$this->resource->id)->where('type','nuclei')->where('severity',$this->severity)->first();		
		if(file_exists($report))
		{ 
			if(strlen(file_get_contents($report))>50)
			{
					
				
					
					$vulnReport="";
					$data=explode(PHP_EOL,file_get_contents($report));
					foreach($data as $entry)
					{
						$vuln=json_decode($entry);

						if(isset($vuln->{'template-id'}) && isset($vuln->{'matched-at'}))
						{
							
							$resVuln=$vuln->{'template-id'}."\t".$vuln->{'matched-at'};

							if(isset($vuln->{'meta'}))
							{
								$resVuln.="\t".str_replace(PHP_EOL,"",json_encode($vuln->{'meta'}, JSON_PRETTY_PRINT)).PHP_EOL;
							}
							else
							{
								$resVuln.=PHP_EOL;
							}
							$vulnReport.=$resVuln;
						}

					}
					
					
					
					if($finding==null)
					{
						$finding=new Finding;		
						
						$finding->resource_id=$resource->id;
						$finding->scope_id=$scope->id;
						$finding->scope_entry_id=$scope_entry->id;
						$finding->type="nuclei";
						$finding->severity=$this->severity;
					}

					$finding->report=$vulnReport;
					$finding->save();


			}
			else
			{
				if($finding!=null)
				$finding->delete();
			}
			//unlink($report);
		}
		else
		{
			if($finding!=null)
				$finding->delete();
		}
		unlink($list);	
			



		


			$this->entry->status='done';
			$this->entry->save();
		
    }
}
