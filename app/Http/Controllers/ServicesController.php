<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Template;
use App\Models\ScopeTemplate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;

class ServicesController extends Controller
{
     
   public function list($id)
 { 
	$scope= Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
	$services=array();
	foreach($scope->services as $service)
	{
		if(!isset($services[$service->port]))
			$services[$service->port]=0;
		$services[$service->port]++;

	}
	ksort($services);
    return view('services.list',['scope' =>  $scope,'services'=>$services]);
 }
 


}