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
class DirsearchCommand implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 1800;
	public $uniqueFor = 1800;
    /**
     * Create a new job instance.
     *
     * @return void
     */
	 
	 
	public $entry;
	public $scanner_path;
    public $scanner;
	public $time_limit;
	public $domain;
    public function __construct(CommandQueue $entry)
    {
		$this->entry=$entry;
		$this->onQueue('discovery');
		$this->domain=$entry->argument;
		$this->scanner_path=base_path()."/scanners/dirsearch/";
		$this->scanner=$this->scanner_path."dirsearch.py";
		$this->time_limit=1800;

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
		$report_name="dirsearch".Str::random(40).".txt";
		$report=storage_path('app')."/".$report_name;

			$process = new Process([
			$this->scanner,
			'-e',
			'php,asp,aspx,jsp,py,txt,conf,config,bak,backup,swp,old,db,sqlasp,aspx,aspx~,asp~,py,py~,rb,rb~,php,php~,bak,bkp,cache,cgi,conf,csv,html,inc,jar,js,json,jsp,jsp~,lock,log,rar,old,sql,sql.gz,sql.zip,sql.tar.gz,sql~,swp,swp~,tar,tar.bz2,tar.gz,txt,wadl,zip',
			'-i',
			'200',
			'--format=plain',
			'-o',
			$report,
			'--url',
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
