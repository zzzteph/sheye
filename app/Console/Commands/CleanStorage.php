<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
class CleanStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wipe:storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove old files from storage';

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
		Log::channel('wipe:storage')->debug('===============================WIPE:STORAGE=======================================================');
		$folders=Storage::directories();
		foreach($folders as $folder)
		{
			if($folder=="public")continue;
			if(str_starts_with($folder,"amass"))
			{
				if(Carbon::now()->diffInDays(Carbon::parse(Storage::lastModified($folder)))>1)
				{
					Log::channel('wipe:storage')->debug("Deleting folder ".$folder);
					Storage::deleteDirectory($folder);
				}
			}
		}
		$files=Storage::files();
		foreach($files as $file)
		{
			if(str_starts_with($file,"."))continue;
			
			try
			{
				if(Carbon::now()->diffInDays(Carbon::parse(Storage::lastModified($file)))>1)
				{
					Log::channel('wipe:storage')->debug("Deleting file ".$file);
					Storage::delete($file);
				}
			}
			catch(\League\Flysystem\UnableToRetrieveMetadata $e)
			{
				Log::channel('wipe:storage')->debug("Unable to delete file ".$file);
				echo "Unable to delete $file".PHP_EOL;
			}
			
			

		}
		
				$files=Storage::disk('public')->files();
		foreach($files as $file)
		{

			if(str_starts_with($file,"."))continue;
			
			try
			{
				if(Carbon::now()->diffInDays(Carbon::parse(Storage::disk('public')->lastModified($file)))>1)
				{
					Log::channel('wipe:storage')->debug("Deleting public file ".$file);
					Storage::disk('public')->delete($file);
				}
			}
			catch(\League\Flysystem\UnableToRetrieveMetadata $e)
			{
					Log::channel('wipe:storage')->debug("Unable to delete public file ".$file);
				echo "Unable to delete $file".PHP_EOL;
			}
			
		}
		Log::channel('wipe:storage')->debug('==================================================================================================');
		
		
    }
}
