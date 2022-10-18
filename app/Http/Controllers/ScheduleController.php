<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Schedule;
use App\Models\Scope;
use App\Models\ScopeEntry;
class ScheduleController extends Controller
{
     


 	public function list()
	{
		return view('schedule.list',['schedulers'=>Schedule::where('user_id', Auth::id())->get(),'scopes'=>Scope::where('user_id', Auth::id())->get()]);
	}
	


	public function insert(Request $request)
	{
		
		$validated = $request->validate([
			'frequency' => 'required',
			'scope' => 'required'
			
		]);
		$scope=Scope::where('user_id', Auth::id())->where('id',$request->input('scope'))->firstOrFail();
		$scheduler=new Schedule;
		
		switch($request->input('frequency'))
		{
			case "daily":$scheduler->frequency="daily";break;
			case "weekly":$scheduler->frequency="weekly";break;
			case "monthly":$scheduler->frequency="monthly";break;
			case "quarterly":$scheduler->frequency="quarterly";break;
			default:$scheduler->frequency="daily";
		}	
		$scheduler->scope_id=$scope->id;
		$scheduler->user_id=Auth::id();
		$scheduler->type="discovery";
		$scheduler->save();		
		return redirect()->back();
	}
	public function delete($schedule_id)
	{
		$scheduler=Schedule::where('user_id', Auth::id())->where('id',$schedule_id)->firstOrFail();
		$scheduler->delete();
		return redirect()->back();

	}


}
