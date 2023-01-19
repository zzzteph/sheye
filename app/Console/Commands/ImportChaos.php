<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class ImportChaos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chaos:import {scope_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Chaos data to shrewdeye';

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
				try{
				$file=Http::retry(3, 1000)->get($entry['URL']);
				if($file->ok())
				{
					Storage::disk('local')->put($folder."/data.zip",$file->body());
				}
				else
					echo "There is an error in downloading ".$entry['name'].PHP_EOL;
				}
				catch(\Illuminate\Http\Client\RequestException $e)
				{
					echo "There is an error in downloading ".$entry['name'].PHP_EOL;
				}

		}
		
		
		
		$directories = Storage::directories("chaos");
		$scope=Scope::where('id', $this->argument('scope_id'))->firstOrFail();
		foreach($directories as $entry)
		{
			if(Storage::exists($entry."/data.zip") && Storage::exists($entry."/output"))
			{
				$path = Storage::path($entry."/data.zip");
				$output = Storage::path($entry."/output");
				shell_exec("unzip -o $path -d $output");
				foreach(Storage::files($entry."/output") as $file)
				{
					echo basename($file,".txt")."...".Storage::size($file).PHP_EOL;
					
					$content=Storage::get($file);
					$lines=explode(PHP_EOL,$content);
					
					$domain_name=str_replace("*.","",basename($file,".txt"));
					$domain_name=str_replace("*","",$domain_name);
					//domain name can contain * or 
					$scope_entry=ScopeEntry::where('scope_id',$scope->id)->where('source',$domain_name)->first();
					if($scope_entry==null)
					{
						$scope_entry=ScopeEntry::where('scope_id',$scope->id)->where('source','*.'.$domain_name)->first();
					}
					
					if($scope_entry==null)
					{
						$scope_entry=new ScopeEntry;
						$scope_entry->source=$domain_name;
						$scope_entry->scope_id=$scope->id;
						$scope_entry->type="domain_list";
						try
						{
							$scope_entry->save();
						}
						catch( \Illuminate\Database\QueryException $e)
						{
							continue;
						}
					}
					
					$list_of_sources=array();
					foreach(Resource::where('scope_entry_id',$scope_entry->id)->get() as $rentry)
					{
						$list_of_sources[trim($rentry->name)]=1;
					}
					
					
					foreach($lines as $line)
					{
						try
						{
													
							if(!isset($list_of_sources[trim($line)]))
							{
								$resource=Resource::where('scope_entry_id',$scope_entry->id)->where('name',trim($line))->first();
								if($resource==null)
								{
										$resource=new Resource;
										$resource->scope_id=$scope_entry->scope_id;
										$resource->scope_entry_id=$scope_entry->id;
										$resource->type='domain';
										$resource->name=trim($line);
										$resource->save();
										$list_of_sources[trim($line)]=1;
								}
							}
							else
							{
								unset($list_of_sources[trim($line)]);
							}
						}
						catch( \Illuminate\Database\QueryException $e)
						{
							continue;
						}
					}
					unset($list_of_sources);
				}

			}
			
		}
		Storage::deleteDirectory("chaos");
		
		
		
		

    }
}