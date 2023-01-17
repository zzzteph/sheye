<?php

namespace App\Jobs\Commands;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\CommandQueue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
class NucleiCommand implements ShouldQueue, ShouldBeUnique
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
	public $scanner_path;
    public $scanner;
	public $template_path;
	public $time_limit;
	
	public $domain;
    public function __construct(CommandQueue $entry)
    {
		$this->entry=$entry;
		$this->onQueue('resource');
		$this->domain=$entry->argument;
		$this->scanner_path=base_path()."/scanners/";
		$this->template_path=$this->scanner_path."/nuclei-templates";
		$this->scanner=$this->scanner_path."nuclei";
		$this->time_limit=1200;
    }
	
	public function uniqueId()
    {
        return $this->entry->id;
    }


	  

	 
    public function handle()
    {
		
		$this->entry->status='running';
		$this->entry->save();
		$this->domain=trim(str_replace("*.","",$this->domain));
		$report_name="nuclei".Str::random(40).".txt";
		$report = storage_path('app/public')."/".$report_name;
		
		$process = new Process([
			$this->scanner,
			'-nc',
			'-ud',
			$this->template_path,
			'-eid','expired-ssl,laravel-debug-enabled,CVE-2017-5487,CVE-2016-10940,CVE-2017-10271,CVE-2020-35489,CVE-2018-15473,CVE-2016-6210,unauthenticated-varnish-cache-purge,graphql-alias-batching,CVE-2021-41349',
			'-o',
			$report,
			'-u',
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
		$handle = fopen($report, "r");
		if ($handle) {
			while (($line = fgets($handle)) !== false) {
				$output.=trim($line).PHP_EOL; 
				if(strlen($output)>1000000)break;
			}

			fclose($handle);
		}
				
		Storage::delete($report_name);		
		$this->entry->report=base64_encode(gzcompress($output,9)); 



		$this->entry->status='done';
		$this->entry->save();
		
    }
}
