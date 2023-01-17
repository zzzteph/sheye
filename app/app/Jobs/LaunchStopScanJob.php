<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Queue;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class LaunchStopScanJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
	public $timeout = 1800;
	public $uniqueFor = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */

	public $scope;
    public function __construct(Scope $scope)
    {
		$this->scope=$scope;
		$this->onQueue('listeners');
    }
	
	public function uniqueId()
    {
        return $this->scope->id;
    }
	 
    public function handle()
    {
		
			
		foreach($this->scope->queues()->where('status','!=', 'done')->get() as $queue)
		{
				$queue->delete();
		}
	
		
    }
}
