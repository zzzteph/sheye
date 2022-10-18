<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Tool;
use App\Models\Scan;
use App\Models\ScanEntry;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Screenshot;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Jobs\Resource\Dirsearch;
use App\Jobs\Nmap1000Scan;
use App\Jobs\Nmap65k;
use App\Jobs\Nuclei;
use App\Jobs\NucleiMedium;
use App\Jobs\NucleiLow;
use App\Jobs\Discovery\AmassBig;
use App\Jobs\Discovery\AmassJob;
use App\Jobs\Discovery\AmassHuge;
use App\Jobs\NucleiScope;
use Illuminate\Support\Facades\Bus;
use App\Jobs\Resource\NmapResourceScan;
use App\Jobs\Resource\ScreenShotJob;
class JobsController extends Controller
{
	
	
	public function monitor($type)
	{
		if($type=="amass")
		$result=shell_exec('ps aux| grep "/var/www/scanner/scanners/amass_linux_amd64/amass"| grep -v grep|grep -v timeout|awk \'{ for(i=1;i<=NF;i++) {if ( i >= 11 ) printf $i" "}; printf "\n" }\'');
		if($type=="nmap")
		$result=shell_exec('ps aux| grep "nmap --exclude"| grep -v grep|grep -v timeout| grep -v grep|grep -v timeout|awk \'{ for(i=1;i<=NF;i++) {if ( i >= 11 ) printf $i" "}; printf "\n" }\'');
		if($type=="nuclei")
		$result=shell_exec('ps aux| grep "nuclei -nc -ud"| grep -v grep|grep -v timeout| grep -v grep|grep -v timeout|awk \'{ for(i=1;i<=NF;i++) {if ( i >= 11 ) printf $i" "}; printf "\n" }\'');
		$result=explode(PHP_EOL,$result);
		return view('scans.monitor',['jobs' =>  $result]);
	}
	





}