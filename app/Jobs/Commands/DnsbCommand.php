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
class DnsbCommand implements ShouldQueue, ShouldBeUnique
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
	public $time_limit;
	public $domain;
	public $dnsx;
	public $dnsx_worlist;

    public function __construct(CommandQueue $entry)
    {
		$this->entry=$entry;
		$this->onQueue('discovery');
		$this->domain=$entry->argument;
		$this->scanner_path=base_path()."/scanners/";
		$this->dnsx=$this->scanner_path."dnsx";
		$this->dnsx_worlist=$this->scanner_path."final.txt";
		$this->time_limit=3400;


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
		
		
		
		
		$this->entry->report="Nothing was found";
		if ($process->isSuccessful()) {
			$this->entry->report=base64_encode(gzcompress($process->getOutput(),9)); 
		}

		$this->entry->status='done';
		$this->entry->save();
		
		
    }
}
