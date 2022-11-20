<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Resource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Models\Queue;
use App\Jobs\LaunchScanJob;
use App\Jobs\LaunchStopScanJob;
class ScansController extends Controller
{
     
	 
	 
	 
	 

 	public function scope_scan_launch($scope_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		LaunchScanJob::dispatch($scope);
		return back();	
	}



 	public function scope_scan_stop($scope_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		LaunchStopScanJob::dispatch($scope);
		return back();	
	}













}