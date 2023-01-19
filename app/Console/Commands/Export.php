<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class Export extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scope:export {scope_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export scope data to files';

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
		$scope_id = $this->argument('scope_id');
		$scope_entries=ScopeEntry::where('scope_id',$scope_id)->get();
		
		foreach($scope_entries as $entry)
		{
			foreach($entry->resources as $resource)
			{
				
				if(strpos($resource->name, ".".$entry->source)===FALSE)continue;
				Storage::append("export/".$entry->source.".txt", $resource->name);
			}
			
		}


		
		
		
		

    }
}