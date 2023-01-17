<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Models\PartDomain;
use App\Models\Part;
use App\Models\SubDomain;
use App\Models\SubPartDomain;
use App\Models\SubPart;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class ChaosDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chaos:download';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove jobs that stuck in queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }




    public function handle()
    {
		
		$response = Http::get('https://chaos-data.projectdiscovery.io/index.json');
		

		foreach($response->json() as $entry)
		{
			echo $entry['name'].PHP_EOL;
			$folder="chaos/".md5($entry['name']);

				Storage::makeDirectory($folder."/output");
				$file=Http::get($entry['URL']);
				if($file->ok())
				{
					Storage::disk('local')->put($folder."/data.zip", file_get_contents($entry['URL']));
					echo "Downloaded...".Storage::size($folder."/data.zip").PHP_EOL;
				}
				else
					echo "There is an error in downloading ".$entry['name'].PHP_EOL;

		}
		
		
		
		

    }
}