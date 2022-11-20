<?php
namespace App\Jobs\Resource;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Response;
use App\Models\Queue;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use App\Models\Service;
use App\Models\Output;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Exception\ProcessTimedOutException;
use Symfony\Component\Process\Process;
class DirsearchJob implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 2000;
    public $uniqueFor = 2000;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $service;
    public $entry;
    private $time_limit;
    public $scanner_path;
    public $scanner;
    public function __construct(Queue $entry)
    {
        $this->onQueue('resource');
        $this->entry = $entry;
        $this->scanner_path = base_path() . "/scanners/dirsearch/";
        $this->scanner = $this->scanner_path . "dirsearch.py";
        $this->time_limit = 1800;
    }

    public function uniqueId()
    {
        return $this
            ->entry->id;
    }

    public function handle()
    {
        $service = Service::find($this
            ->entry
            ->object_id);
        if ($service === null)
        {
            $entry->status = 'done';
            $entry->save();
            return;
        }
        $this->service = $service;
        $this
            ->entry->status = 'running';
        $this
            ->entry
            ->save();

        $service = Service::find($this
            ->service
            ->id);
        if ($service === null)
        {
            $this
                ->entry->status = 'done';
            $this
                ->entry
                ->save();
            return;
        }
        $scope_entry = ScopeEntry::find($service->scope_entry_id);
        $scope = Scope::find($service->scope_id);
        $resource = Resource::find($service->resource_id);
        if ($resource === null || $scope_entry === null || $scope === null)
        {
            $this
                ->entry->status = 'done';
            $this
                ->entry
                ->save();
            return;
        }

        if (strpos($service->service, "http") !== false || strpos($service->service, "ssl") !== false)
        {
            $url = "";
            if (strpos($service->service, "http") !== false && strpos($service->service, "https") === false && strpos($service->service, "ssl") === false)
            {

                $url = "http://" . $resource->name . ":" . $service->port . "/";
            }
            else if (strpos($service->service, "http") !== false && (strpos($service->service, "https") !== false || strpos($service->service, "ssl") !== false))
            {
                $url = "https://" . $resource->name . ":" . $service->port . "/";

            }
            else if (strpos($service->service, "ssl") !== false || strpos($service->service, "http") == false)
            {
                $url = "https://" . $resource->name . ":" . $service->port . "/";

            }
            if ($url === "")
            {
                $this
                    ->entry->status = 'done';
                $this
                    ->entry
                    ->save();
            }

            Storage::disk('public')
                ->makeDirectory($scope_entry->id);
            $dirseach_output = "dirsearch" . Str::random(40);
            $report = storage_path('app') . "/" . $dirseach_output;

            $process = new Process([$this->scanner, '-e', 'php,aspx,jsp,html,js', '-i', '200', '--format=plain', '-o', $report, '--url', $url

            ]);

            $process->setTimeout($this->time_limit);
            $process->start();
            while ($process->isRunning())
            {

                try
                {
                    $process->checkTimeout();
                }
                catch(ProcessTimedOutException $e)
                {
                    break;
                }
                usleep(2000000);
            }

            $output = "";
			 if (file_exists($report))
			 {
				$handle = fopen($report, "r");
				if ($handle)
				{
					while (($line = fgets($handle)) !== false)
					{
						$output .= trim($line) . PHP_EOL;
						if (strlen($output) > 1000000) break;
					}

					fclose($handle);
				}
			 }

            $finding = Output::where('resource_id', $resource->id)
                ->where('service_id', $this
                ->service
                ->id)
                ->where('type', 'dirsearch')
                ->where('severity', 'info')
                ->first();
            if (file_exists($report))
            {
                if (strlen(file_get_contents($report)) > 100)
                {

                    if ($finding == null)
                    {
                        $finding = new Output;
                        $finding->service_id = $this
                            ->service->id;
                        $finding->resource_id = $resource->id;
                        $finding->scope_id = $scope->id;
                        $finding->scope_entry_id = $scope_entry->id;
                        $finding->type = "dirsearch";
                        $finding->severity = 'info';
                    }

                    $finding->report = $output;
                    $finding->save();

                }
                else
                {
                    if ($finding != null) $finding->delete();
                }
            }
            else
            {
                if ($finding != null) $finding->delete();
            }
            Storage::delete($dirseach_output);

        }

        $this
            ->entry->status = 'done';
        $this
            ->entry
            ->save();

    }
}
