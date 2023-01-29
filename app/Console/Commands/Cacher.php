<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CacheJob;
use Illuminate\Support\Facades\Log;
class Cacher extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create cache for scopes and scope_entries';

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
		Log::channel('data:cache')->debug('===============================DATA:CACHE=======================================================');
		if(env('ENABLE_DATA_CACHE',false)==true)
		{
			
			CacheJob::dispatch(false);
			Log::channel('data:cache')->debug('Cache dispatched');
		}
		Log::channel('data:cache')->debug('=================================================================================================');

    }
}
