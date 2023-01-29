<?php

namespace App\Jobs\Resource;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Response;
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
use Illuminate\Support\Facades\Log;
class AnalyzeService implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 120;
	public $uniqueFor = 120;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	public $service;
	public $entry;
	private $time_limit;
	public $scanner_path;
    public $scanner;
	public $httpx;
    public function __construct(Queue $entry)
    {
		$this->onQueue('resource');
		$this->entry=$entry;
		$this->scanner_path=base_path()."/scanners/screenshot/target/";
		$this->scanner=$this->scanner_path."screenshot-1.0.jar";
		$this->httpx=base_path()."/scanners/httpx";
		$this->time_limit=60;
		Log::channel('AnalyzeService')->debug('==============new:'.$entry->id.'==============');
		Log::channel('AnalyzeService')->debug('queue_id '.$entry->id);
		Log::channel('AnalyzeService')->debug('object_id '.$entry->object_id);
		Log::channel('AnalyzeService')->debug('object_type '.$entry->object_type);
		Log::channel('AnalyzeService')->debug('==============new==============');
    }

	public function uniqueId()
    {
        return $this->entry->id;
    }



    public function handle()
    {
		Log::channel('AnalyzeService')->debug('==============running:'.$entry->id.'==============');
		
		$service= Service::find($this->entry->object_id);
		if($service===null)
		{
			Log::channel('AnalyzeService')->debug($this->entry->id." unable to find service ".$this->entry->object_id);
			$this->entry->status='done';
			$this->entry->save();
			Log::channel('AnalyzeService')->debug($this->entry->id." done");
			return;
		}
		$this->service=$service;
	
	
		$this->entry->status='running';
		Log::channel('AnalyzeService')->debug($this->entry->id." change status to running");
		
		
		
		$this->entry->save();
		
		$service= Service::find($this->service->id);
		if($service===null)
		{
			Log::channel('AnalyzeService')->debug($this->entry->id." unable to find service ".$this->entry->object_id);
			$this->entry->status='done';
			$this->entry->save();
			Log::channel('AnalyzeService')->debug($this->entry->id." done");
			return;
		}
		
		
		
		$scope_entry= ScopeEntry::find($service->scope_entry_id);
		$scope= Scope::find($service->scope_id);
		$resource= Resource::find($service->resource_id);
		if($resource===null || $scope_entry===null || $scope===null)
		{
			Log::channel('AnalyzeService')->debug($this->entry->id." unable to find one of the resource for service ".$this->entry->object_id);
			$this->entry->status='done';
			$this->entry->save();
			Log::channel('AnalyzeService')->debug($this->entry->id." done");
			return;
		}
		
		
		
			if(	strpos($service->service,"http")!==FALSE || strpos($service->service,"ssl")!==FALSE)
			{
				$url="";
				if(strpos($service->service,"http")!==FALSE && strpos($service->service,"https")===FALSE && strpos($service->service,"ssl")===FALSE)
				{
					
					$url="http://".$resource->name.":".$service->port."/";	
				}
				else if(strpos($service->service,"http")!==FALSE && (strpos($service->service,"https")!==FALSE || strpos($service->service,"ssl")!==FALSE))
				{
					$url="https://".$resource->name.":".$service->port."/";
					
				}
				else if(strpos($service->service,"ssl")!==FALSE || strpos($service->service,"http")==FALSE)
				{
					$url="https://".$resource->name.":".$service->port."/";
					
				}
				if($url==="")
				{
					Log::channel('AnalyzeService')->debug($this->entry->id." no url ");
					$this->entry->status='done';
					$this->entry->save();
					Log::channel('AnalyzeService')->debug($this->entry->id." done");
					return;
				}
				Log::channel('AnalyzeService')->debug($this->entry->id." url ".$url);
				
				$rnd=Str::random(40);
				
				
				Storage::disk('public')->makeDirectory($scope_entry->id);
				$httpx_file_name="httpx".Str::random(40);
				$path=storage_path('app/public')."/".$scope_entry->id."/".$rnd.".jpg";
				$source=storage_path('app/public')."/".$scope_entry->id."/".$rnd.".txt";
				$preview=storage_path('app/public')."/".$scope_entry->id."/preview_".$rnd.".jpg";
				
				
				$httpx_file=storage_path('app/public')."/".$httpx_file_name;
				Storage::disk('public')->put($httpx_file_name,$url);
				
				

				Log::channel('AnalyzeService')->debug($this->entry->id." path ".$path);
				Log::channel('AnalyzeService')->debug($this->entry->id." preview ".$preview);
				Log::channel('AnalyzeService')->debug($this->entry->id." httpx_file ".$httpx_file);
				
				
				
				
				//take screenshot
				$process = new Process([
				'java',
					'-jar',
					$this->scanner,
					'-u',
					$url,
					'-s',
					$path,
					'-c',
					$source
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
				
				Log::channel('AnalyzeService')->debug($this->entry->id." screenshot taken");
				//mogrify screenshot
				
				$process = new Process([
				'mogrify',
					'-resize',
					'10%',
					'-write',
					$preview,
					$path
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
				
				Log::channel('AnalyzeService')->debug($this->entry->id." mogrify done");
				//get information with HTTPX
				
				$process = new Process([
				$this->httpx,
					'-sc',
					'-asn',
					'-ip',
					'-cname',
					'-title',
					'-server',
					'-silent',
					'-json',
					'-list',
					$httpx_file
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
				
				Log::channel('AnalyzeService')->debug($this->entry->id." httpx done");
				if ($process->isSuccessful()) {
					$httpx = json_decode($process->getOutput());
				}
				
						

				//delete HTTPX_FILE
				Storage::disk('public')->delete($httpx_file_name);

				$text="";
				if(file_exists($source))
					$text=file_get_contents($source);
				//extract title
				
				Log::channel('AnalyzeService')->debug($this->entry->id." source text size ".strlen($text));		

				
				$title="";
				$pos = strpos($text, "<title>");
				if($pos!==FALSE)
				{
						$pos_end = strpos($text, "</title>",$pos);
						if($pos_end!==FALSE)
						{
							if($pos+strlen("<title>")<$pos_end)
							{
								echo ($pos+strlen("<title>")).PHP_EOL;
								echo ($pos_end-($pos+strlen("<title>"))).PHP_EOL;
								$title = substr($text, $pos+strlen("<title>"), $pos_end-($pos+strlen("<title>"))); 
							}
						}
				}
				$ip="";
				$code="";
				$server="";
				$asn="";
				
				if($httpx!==NULL)
				{
					if(isset($httpx->{'host'}))
					{
						$ip=$httpx->{'host'};
					}
					if(isset($httpx->{'status-code'}))
					{
						$code=$httpx->{'status-code'};
					}
					if(isset($httpx->{'webserver'}))
					{
						$server=$httpx->{'webserver'};
					}
					if(isset($httpx->{'title'}))
					{
						if($title=="")
						{
							$title=$httpx->{'title'};
						}
					}
					
					if(isset($httpx->{'asn'}))
					{

						if(isset($httpx->{'asn'}->{'as-number'}))$asn.=" ".$httpx->{'asn'}->{'as-number'};
						if(isset($httpx->{'asn'}->{'as-name'}))$asn.=" ".$httpx->{'asn'}->{'as-name'};
						if(isset($httpx->{'asn'}->{'as-country'}))$asn.=" ".$httpx->{'asn'}->{'as-country'};
						if(isset($httpx->{'asn'}->{'as-range'}))$asn.=" ".$httpx->{'asn'}->{'as-range'};
					}	
				}

				Log::channel('AnalyzeService')->debug($this->entry->id." title ".$title);		
				Log::channel('AnalyzeService')->debug($this->entry->id." ip ".$ip);
				Log::channel('AnalyzeService')->debug($this->entry->id." code ".$code);
				Log::channel('AnalyzeService')->debug($this->entry->id." server ".$server);
				Log::channel('AnalyzeService')->debug($this->entry->id." asn ".$asn);

				
						
				
				
				$resp=Response::where('service_id',$service->id)->first();
				if($resp==null)
				{
					$resp=new Response;
				}
				
				if(file_exists($path))
				{
					$resp->path=$path;
					$resp->size=filesize($path);
					Log::channel('AnalyzeService')->debug($this->entry->id." ".$path." size = ".filesize($path));
				}
				else
				{
					Log::channel('AnalyzeService')->debug($this->entry->id." ".$path." not exists!");
				}
				if(file_exists($preview))
					$resp->preview=$preview;
				$resp->link=$url;
				
				
				
				if (strlen($title) > 250)
					$title = substr($title, 0, 249) . '...';
				
				
				$resp->source=$text;
				$resp->title=$title;
				
				$resp->server=$server;
				$resp->code=intval($code);
				$resp->ip=$ip;
				$resp->asn=$asn;
				
				$resp->service_id=$service->id;
				$resp->resource_id=$resource->id;
				$resp->scope_id=$scope->id;
				$resp->scope_entry_id=$scope_entry->id;
				$resp->save();
	
					

				Storage::disk('public')->delete($scope_entry->id."/".$rnd.".txt");
				
			}

			Log::channel('AnalyzeService')->debug($this->entry->id." done");
			$this->entry->status='done';
			$this->entry->save();
		
    }
}
