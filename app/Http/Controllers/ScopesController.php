<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Scope;
use App\Models\ScopeEntry;
 
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Bus;
use App\Jobs\WipeQueueJob;
class ScopesController extends Controller
{
     
   public function index()
 { 
    return view('dashboard',['scopes' =>  Scope::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(10)]);
 }
 
 	public function new()
	{
		return view('scopes.new');
	}

 	public function edit($id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		$scope_entries=ScopeEntry::where('scope_id',$scope->id)->orderBy('created_at', 'desc')->paginate(10);
		return view('scopes.edit',[
		'scope' =>  $scope,
		'scope_entries' =>$scope_entries ]);
	}
 	public function delete($id)
	{

		$scope=	Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		Scope::findOrFail($id)->delete();
		return redirect()->route('dashboard');
	}






	
	public function update(Request $request,$id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		$validated = $request->validate([
			'name' => 'required|max:64|min:4'
		]);
		$scope->name=$request->input('name');
		$scope->save();	
		return redirect()->route('scopes-edit',['id' =>$scope->id]);
	}


	public function bulk_update(Request $request,$scope_id)
	{

		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$validated = $request->validate([
			'domains' => 'required|max:64000'
		]);
		
		$domains=explode(PHP_EOL,$request->input('domains'));
		$error=$this->validate_domain_list($domains);
		if($error!==FALSE)
		{
			$msg="Input domains have incorrect format:".$error;
			return back()->withErrors(['errors' => $msg]);
		}
		

		foreach($domains as $domain)
		{
			$domain=trim($domain);
			if(ScopeEntry::where("scope_id",$scope_id)->where("source",trim($domain))->first()!=null)continue;
			$tmp=new ScopeEntry;
			$tmp->type="domain";
			if(str_starts_with($domain, "*.")!==FALSE)$tmp->type="domain_list";
			
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();
			 		
			 
			 
		}
		return redirect()->route('scopes-edit',['id' =>$scope->id]);
	}



 	function validate_domain($domain)
	{
		$domain=trim($domain);
		if(str_starts_with($domain, "*.")!==FALSE)
		{
			$domain=substr($domain, 2);
		}

		if(!filter_var($domain,FILTER_VALIDATE_DOMAIN,FILTER_FLAG_HOSTNAME) )
		{
			return $domain;
		 }
			$parts=explode(".",$domain);
		

			if(count($parts)>9 || count($parts)<2)
			{
				return $domain;	
			}
			$length_error=FALSE;
			foreach($parts as $part)
			{
				if(strlen($part)==0 || strlen($part)>62)
				{
					return $domain;
				}
			}
			if(strlen($parts[count($parts)-1])<2 || strlen($parts[count($parts)-1])>6)
			{
				return $domain;
			}


		return FALSE;
	}


	function validate_domain_list($domains)
	{
		
		$error=FALSE;
		foreach($domains as $domain)
		{
			$domain=trim($domain);
			if(str_starts_with($domain, "*.")!==FALSE)
			{
				$domain=substr($domain, 2);
			}

			 if(!filter_var($domain,FILTER_VALIDATE_DOMAIN,FILTER_FLAG_HOSTNAME) )
			 {
				 $error.=$domain.";".PHP_EOL;
				 continue;
			 }
			$parts=explode(".",$domain);
		

			if(count($parts)>9 || count($parts)<2)
			{
				 $error.=$domain.";".PHP_EOL;
				 continue;
			}
			$length_error=FALSE;
			foreach($parts as $part)
			{
				if(strlen($part)==0 || strlen($part)>62)
				{
					$length_error=TRUE;
					break;
				}
			}
			if(strlen($parts[count($parts)-1])<2 || strlen($parts[count($parts)-1])>6)
			{
				$length_error=TRUE;
			}
			if($length_error===TRUE)
			{
				 $error.=$domain.";".PHP_EOL;
				 continue;
			}
		}
		if($error!==FALSE)
			if(strlen($error)>100)substr($error, 0, 100) . '...';
		return $error;
	}

	public function create(Request $request)
	{
		$validated = $request->validate([
			'name' => 'required|max:64|min:4',
			'domains' => 'required|max:64000'
		]);
		
		$domains=explode(PHP_EOL,$request->input('domains'));
		$error=$this->validate_domain_list($domains);
		

		
		
		
		
		
		
		$domainsCount=0;
		foreach($domains as $domain)
		{
			if($this->validate_domain($domain)===FALSE)
			{
				$domainsCount++;
			}
		}
		
		if($domainsCount==0)
		{
			$msg="Input domains have incorrect format";
			return back()->withErrors(['errors' => $msg]);
		}

		
		
		$scope=new Scope;
		$scope->name=$request->input('name');
		$scope->user_id=Auth::id();
		$scope->save();
		
		
		
		foreach($domains as $domain)
		{
			if($this->validate_domain($domain)!=FALSE)continue;
			$domain=trim($domain);
			$tmp=new ScopeEntry;
			$tmp->type="domain";
			if(str_starts_with($domain, "*.")!==FALSE)$tmp->type="domain_list";
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();
			 		
			 
			 
		}
		if($error==FALSE)
			return redirect()->route('scopes-edit',['id' =>$scope->id]);
		else
			return redirect()->route('scopes-edit',['id' =>$scope->id])->withErrors(['errors' =>"Input domains have incorrect format:".$error]);
	}



}