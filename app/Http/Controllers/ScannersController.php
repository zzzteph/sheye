<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Scanner;
use App\Models\ScannerEntry;
class ScannersController extends Controller
{

	public function types()
	{
		return view('scanners.types',['scanners'=>Scanner::all()]);
	}
	
	public function view($id)
	{

		return view('scanners.view',['scan_entry'=>ScannerEntry::where('id',$id)->firstOrFail(),'scanners'=>Scanner::all()]);
	}
		public function list()
	{
		return view('scanners.list',['scanners'=>ScannerEntry::all()]);
	}
	
			public function new()
	{
		return view('scanners.new',['scanners'=>Scanner::all()]);
	}
	
		public function update(Request $request,$id)
		{
			$scanner_entry=ScannerEntry::where('id',$id)->firstOrFail();
					$validated = $request->validate([
			'name' => 'required|max:64|min:4',
			'template_type' => 'required'
		]);
		
		$scanner_entry->name=$request->input('name');
		$scanner_entry->scanner_id=$request->input('template_type');
		$scanner_entry->save();
		if($scanner_entry->scanner->has_arguments==TRUE)
		{
			if($request->filled('arguments'))
			{
				$scanner_entry->arguments=$request->input('arguments');
					$scanner_entry->save();
			}
		}
		return back();
			
			
		}
		
	
	
	
	
 	public function delete($id)
	{

		
		ScannerEntry::findOrFail($id)->delete();
		return back();
	}


	
	public function create(Request $request)
	{
		
		$validated = $request->validate([
			'name' => 'required|max:64|min:4',
			'template_type' => 'required'
		]);
		
		if(($scanner=Scanner::find($request->input('template_type')))==null)
		{
			return back()->withErrors(['errors' => "Unknown template type"]);
		}
		
		
		$scan_entry=new ScannerEntry;
		$scan_entry->name=$request->input('name');
		$scan_entry->scanner_id=$request->input('template_type');
		$scan_entry->save();
		if($scanner->has_arguments==TRUE)
		{
			if($request->filled('arguments'))
			{
				$scan_entry->arguments=$request->input('arguments');
					$scan_entry->save();
			}
		}
		return redirect()->route('scanners-list');
		
		
	}
	
	
}