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
class SubfinderCommand implements ShouldQueue, ShouldBeUnique
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
		$this->scanner_path=base_path()."/scanners/";
		$this->scanner=$this->scanner_path."subfinder";
		$this->time_limit=1700;
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
			$this->scanner,
			'--silent',
			'-d',
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

				if ($process->isSuccessful()) {
			$this->entry->report=base64_encode(gzcompress($process->getOutput(),9)); 
		}
		$this->entry->status='done';
		$this->entry->save();
		
    }
}
