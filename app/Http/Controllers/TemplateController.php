<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Scanner;
use App\Models\ScannerEntry;
use App\Models\Template;
use App\Models\TemplateEntry;
use App\Models\Scope;
use App\Jobs\LaunchScanJob;
class TemplateController extends Controller
{

	public function view($id)
	{

		return view('templates.view',['template'=>Template::where('id',$id)->firstOrFail(),'scanners'=>ScannerEntry::all()]);
	}
		public function list()
	{
		return view('templates.list',['templates'=>Template::all()]);
	}
	
			public function new()
	{
		return view('templates.new',['scanners'=>ScannerEntry::all()]);
	}
 	public function delete($id)
	{
		Template::findOrFail($id)->delete();
		return back();
	}
	

	public function launch(Request $request)
	{
		$validated = $request->validate([
			'scope' => 'required',
			'template' => 'required'
			
		]);
		$scope=Scope::where('user_id', Auth::id())->where('id',$request->input('scope'))->firstOrFail();
		$template=Template::where('id',$request->input('template'))->firstOrFail();
		LaunchScanJob::dispatch($scope,$template->id);
		return redirect()->route('dashboard');
	}


	public function update(Request $request,$id)
	{
		
		$validated = $request->validate([
			'name' => 'required|max:64|min:4',
		]);
		$template=Template::where('id',$id)->firstOrFail();
		$template->name=$request->input('name');
		$template->save();
		//wiping templates
		foreach($template->template_entries as $entry)
		{
			 $entry->delete();
		}
		if($request->has('discovery_templates'))
		foreach($request->input('discovery_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		if($request->has('resource_templates'))
				foreach($request->input('resource_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		if($request->has('service_templates'))
						foreach($request->input('service_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		
		return redirect()->route('templates-view',['id'=>$template->id]);
		
		
	}
	





	
	public function create(Request $request)
	{
		
		$validated = $request->validate([
			'name' => 'required|max:64|min:4',
		]);
		

		
		$template=new Template;
		$template->name=$request->input('name');
		$template->save();
		
		
		if($request->has('discovery_templates'))
		foreach($request->input('discovery_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		if($request->has('resource_templates'))
				foreach($request->input('resource_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		if($request->has('service_templates'))
						foreach($request->input('service_templates') as $entry)
		{
			$scanner_entry=ScannerEntry::where('id',$entry)->first();
			if($scanner_entry==null)continue;
			$template_entry=new TemplateEntry;
			$template_entry->scanner_entry_id=$scanner_entry->id;
			$template_entry->template_id=$template->id;
			$template_entry->save();
			
		}
		
		return redirect()->route('templates-view',['id'=>$template->id]);
		
		
	}
	
	
}