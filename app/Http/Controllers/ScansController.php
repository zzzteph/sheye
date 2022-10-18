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
class ScansController extends Controller
{
     
	 
	 
	 
	 

 	public function scope_scan($scope_id,$type)
	{
		
		if($type!=='nuclei' &&$type!=='nmap' && $type!=='nmap_nuclei' && $type!=='dir')
		{
			$type='nuclei';
		}			
		
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		
		foreach($scope->resources as $resource)
		{
				if($resource->scope_entry==null)continue;
				$tmp=new Queue;
				$tmp->object_type="resource";
				$tmp->object_id=$resource->id;
				$tmp->user_id=$resource->scope_entry->scope->user_id;
				$tmp->type=$type;
				$tmp->scope_id=$resource->scope_entry->scope->id;
				$tmp->save();
		}
		return back();	
	}
 	public function scope_entry_scan($scope_id,$scope_entry_id,$type)
	{
				if($type!=='nuclei' &&$type!=='nmap' && $type!=='nmap_nuclei' && $type!=='dir')
		{
			$type='nuclei';
		}	
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('id',$scope_entry_id)->where('scope_id',$scope_id)->firstOrFail();
		foreach($scope_entry->resources as $resource)
		{

				$tmp=new Queue;
				$tmp->object_type="resource";
				$tmp->object_id=$resource->id;
				$tmp->user_id=$resource->scope_entry->scope->user_id;
				$tmp->type=$type;
				$tmp->scope_id=$resource->scope_entry->scope->id;
				$tmp->save();
		}
		return back();	
	}
 	public function resource_scan($scope_id,$scope_entry_id,$resource_id,$type)
	{
		if($type!=='nuclei' && $type!=='nmap' && $type!=='nmap_nuclei' && $type!=='dir')
		{
			$type='nuclei';
		}	
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('id',$scope_entry_id)->where('scope_id',$scope_id)->firstOrFail();
		$resource=Resource::where('resource_id',$resource_id)->where('scope_entry_id',$scope_entry_id)->where('scope_id',$scope_id)->firstOrFail();

		$tmp=new Queue;
		$tmp->object_type="resource";
		$tmp->object_id=$resource->id;
		$tmp->user_id=$resource->scope_entry->scope->user_id;
		$tmp->type=$type;
		$tmp->scope_id=$resource->scope_entry->scope->id;
		$tmp->save();
		return back();	
	}













}