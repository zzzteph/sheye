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
class AmassCommand implements ShouldQueue, ShouldBeUnique
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
	public $time_limit;
	public $domain;
    public function __construct(CommandQueue $entry)
    {
		$this->entry=$entry;
		$this->onQueue('discovery');
		$this->domain=$entry->argument;
		$this->scanner_path=base_path()."/scanners/amass_linux_amd64/";
		$this->scanner=$this->scanner_path."amass";
		$this->time_limit=2400;
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
		$folder_name="amass".Str::random(20);
		$folder=storage_path('app/public')."/".$folder_name;
		Storage::makeDirectory($folder_name);

		
		$process = new Process([
			$this->scanner,
			'enum',
			'-nolocaldb',
			'-d',
			$this->domain,
			'-dir',
			$folder,
			'2>&1'
		
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
		
		
		Storage::deleteDirectory($folder_name);
		$this->entry->status='done';
		$this->entry->save();
		
    }
}
