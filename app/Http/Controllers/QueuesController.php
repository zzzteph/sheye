<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Response;
use App\Models\Scope;
use App\Models\ScopeEntry;
use App\Models\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Artisan;

class QueuesController extends Controller
{

 	public function list()
	{
		$user = Auth::user();
		
		return view('queues.list',['queues' => $user->queues()->orderBy('status', 'asc')->orderBy('updated_at', 'desc')->paginate(50)]);
	}
	
	 	public function scope_queues_list($scope_id,$type=false)
	{
		$user = Auth::user();
		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		switch($type)
		{
			case "running":$queues=$scope->queues()->where('status','running')->orWhere('status','queued')->orderBy('updated_at', 'asc')->paginate(50);break;
			case "done":$queues=$scope->queues()->where('status','done')->orderBy('updated_at', 'asc')->paginate(50);break;
			case "todo":$queues=$scope->queues()->where('status','todo')->orderBy('updated_at', 'asc')->paginate(50);break;
			default:$queues=$scope->queues()->orderBy('status', 'asc')->orderBy('updated_at', 'asc')->paginate(50);
		}
		
		return view('queues.list',['queues' =>$queues,'scope'=>$scope]);
	}
	
	 	public function delete($queue_id)
	{

		Queue::where('user_id', Auth::id())->where('id',$queue_id)->delete();
		
		return back();
	}

}