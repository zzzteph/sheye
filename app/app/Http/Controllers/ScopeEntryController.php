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
use App\Jobs\Delete\ScopeEntryDelete;
use Illuminate\Support\Facades\Bus;
use App\Jobs\WipeQueueJob;
class ScopeEntryController extends Controller
{

	public function list($scope_id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entries=ScopeEntry::where('scope_id',$scope->id)->orderBy('updated_at','desc')->paginate(25);
	
		return view('scope_entries.list',['scope' =>  $scope,'scope_entries'=>$scope_entries,'templates' => Template::all()]);
		
	}

 	public function delete($scope_id,$id)
	{
		//todo make delete normal
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$scope_entry=ScopeEntry::where('scope_id',$scope_id)->where('id',$id)->firstOrFail();
		WipeQueueJob::dispatch("scope_entry",$scope_entry->id);
		$scope_entry->delete();
		return redirect()->back();
	}


	

}