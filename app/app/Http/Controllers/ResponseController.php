<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Scope;
use App\Models\ScopeEntry;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class ResponseController extends Controller
{

 	public function scope_screenshots($scope_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		return view('screenshots.list',['screenshots' =>  $scope->screenshots()->orderBy('updated_at', 'desc')->paginate(50),'scope'=>$scope]);
	}
	
	 	public function scope_entry_screenshots($scope_id,$scope_entry_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('scope_id',$scope->id)->where('id',$scope_entry_id)->firstOrFail();
		return view('screenshots.list',['screenshots' =>  $scope_entry->screenshots()->orderBy('updated_at', 'desc')->paginate(50),'scope'=>$scope,'scope_entry'=>$scope_entry]);
	}
	
	
	


}