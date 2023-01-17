<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Resource;
use App\Models\Scope;
use App\Models\ScopeEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;
use App\Jobs\WipeQueueJob;
class ResourceController extends Controller
{

 	public function scope_resources_list($id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		return view('resources.list',['breadcrumb'=>'resources','resources' =>  $scope->resources()->orderBy('updated_at', 'desc')->paginate(50),'scope'=>$scope]);
	}
	
	 	public function scope_entries_resources_list($scope_id,$id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entries=ScopeEntry::where('scope_id',$scope->id)->where('id',$id)->firstOrFail();
		return view('resources.list',['resources' =>  $scope_entries->resources()->orderBy('updated_at', 'desc')->paginate(50),'scope'=>$scope,'scope_entry'=>$scope_entries]);
	}
	


	
	
	
	public function view($scope_id,$scope_entry_id,$resource_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('scope_id',$scope->id)->where('id',$scope_entry_id)->firstOrFail();
		$resource=Resource::where('scope_id',$scope->id)->where('scope_entry_id',$scope_entry->id)->where('id',$resource_id)->firstOrFail();
		return view('resources.get',['resource' =>  $resource,'scope'=>$scope,'scope_entry'=>$scope_entry]);
	}
	public function delete($scope_id,$scope_entry_id,$resource_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('scope_id',$scope->id)->where('id',$scope_entry_id)->firstOrFail();
		$resource=Resource::where('scope_id',$scope->id)->where('scope_entry_id',$scope_entry->id)->where('id',$resource_id)->firstOrFail();
		WipeQueueJob::dispatch("resource",$resource->id);
		$resource->delete();
		return redirect()->back();

	}

}