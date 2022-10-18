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
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
class NmapResourceScan implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 600;
	public $uniqueFor = 600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 public $entry;
	public $resource;
	private $time_limit;
	private $ports;
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
		$this->time_limit=300;
		$this->ports="22,80,443,8080,8443,445,3389,12345,5432,1433,8880,8088,6379,2375,8983,8383,4990,8500,6066,1090,1098,1099,4444,11099,47001,47002,10999,7000-7004,8000-8003,9000-9003,9503,7070,7071,45000,45001,8686,9012,50500,4848,11111,11211,4445,4786,5555,5556,5900,21,3306,1494,1521,1720,1723,1755,1761,1801,1900,1935,1998,2000-2003,2005,2049,2103,2105,2107,2121,2161,2301,2383,2401,2601,2717,2869,2967,3000-3001,3128,3268,3306,3689-3690,3703,3986,4000-4001,4045,4899,5000-5001,5003,5009,5050-5051,5060,5101,5120,5190,5357,5631,5666,5800,5901,6000-6002,6004,6112,6646,6666,7937-7938,8008-8010,8031,8081,8888,9090,9100,9102,9999-10001,10010,32768,32771,49152-49157,50000";
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

		$randomName="nmap".Str::random(40).".xml";
		$report = storage_path('app')."/".$randomName;
		
		
		$process = new Process([
			'nmap',
			'--exclude',
			'127.0.0.0/16,10.0.0.0/8,172.16.0.0/12,192.168.0.0/16,100.64.0.0/10',
			'-sV',
			'--open',
			'-n',
			'-Pn',
			'-sT',
			'-p',
			$this->ports,
			'-oX',
			$report,
			$this->resource->name,
		
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
		


		
		$result=array();


		if (file_exists($report)) {
			try{
				
			    $xml = simplexml_load_file($report);
						if(isset($xml->{'host'}->{'ports'}->{'port'}))
							{
								foreach($xml->{'host'}->{'ports'}->{'port'} as $entry)
								{

									
									$port=(string)$entry['portid'][0];
									$service="";
									if(isset($entry->{'service'}['name'][0]))
										$service.=(string)$entry->{'service'}['name'][0]." ";	
									if(isset($entry->{'service'}['tunnel'][0]))
										$service.=(string)$entry->{'service'}['tunnel'][0]." ";	
									if(isset($entry->{'service'}['product'][0]))
										$service.=(string)$entry->{'service'}['product'][0]." ";	
									if(isset($entry->{'service'}['version'][0]))
										$service.=(string)$entry->{'service'}['version'][0]." " ;	
									array_push($result,array("port"=>$port,"service"=>$service));
								}
							}
			}
			catch(\Exception $e)
			{

				
			}
		}


		foreach($result as $entry)
		{
			$service=Service::where('resource_id',$this->resource->id)->where('port',$entry['port'])->first();
			if($service==null)
			{
				$service=new Service;
				$service->port=$entry['port'];
				$service->service=$entry['service'];
				$service->resource_id=$this->resource->id;
				$service->scope_id=$scope->id;
				$service->scope_entry_id=$scope_entry->id;
				$service->closed=FALSE;
				$service->save();				
			}		
			
		}



			Storage::delete($randomName);
			if(file_exists($report))unlink($report);
			$this->entry->status='done';
			$this->entry->save();
		
    }
}
