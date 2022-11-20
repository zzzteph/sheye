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
class FindingsController extends Controller
{
     
	 
	 
	 
	 

 	public function scope_list($scope_id,$severity="critical")
	{
		
		if($severity!='critical' && $severity!='high'  && $severity!='medium')
		{
			$severity='critical';
		}	
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		return view('outputs.list',['scope' => $scope,'findings'=>$scope->outputs()->where('severity',$severity)->paginate(50)]);
		
		
	}
 	
 	public function scope_entry_list($scope_id,$scope_entry_id,$severity="critical")
	{
		
		if($severity!='critical' && $severity!='high'  && $severity!='medium')
		{
			$severity='critical';
		}	 
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
			$scope_entry=ScopeEntry::where('id',$scope_entry_id)->where('scope_id',$scope_id)->firstOrFail();
		return view('outputs.list',['scope' => $scope,'scope_entry' => $scope_entry,'findings'=>$scope->outputs()->where('severity',$severity)->paginate(50)]);
		
		
	}
}