<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Queue;
use Carbon\Carbon;
use App\Models\CommandQueue;
use Illuminate\Support\Facades\DB;

class ClearDoneQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wipe:done';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipe done jobs in queue';

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
		$queues=Queue::where('status','done')->get();
		foreach($queues as $queue)
		{
			$queue->delete();
		}


    }
}
