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
use Illuminate\Support\Facades\Bus;
class ScopesController extends Controller
{
     
   public function index()
 { 
    return view('dashboard',['templates'=>Template::all(),'scopes' =>  Scope::where('user_id', Auth::id())->orderBy('id', 'desc')->paginate(12)]);
 }
 
 	public function new()
	{
		return view('scopes.new',['templates' => Template::all()]);
	}

 	public function edit($id)
	{
		$scope=Scope::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		$scope_entries=ScopeEntry::where('scope_id',$scope->id)->orderBy('source', 'asc')->paginate(10);
		return view('scopes.edit',[
		'scope' =>  $scope,
		'scope_entries' =>$scope_entries,
		'templates' => Template::all()		]);
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
			'name' => 'required|max:64|min:4',
			'template' => 'required'
		]);
		$scope->name=$request->input('name');
		$scope->save();	
		
		Template::findOrFail($request->input('template'));

		if($scope->scope_template==null)
		{
			$scope_template=new ScopeTemplate;
			$scope_template->template_id=$request->input('template');
			$scope_template->scope_id=$scope->id;
			$scope_template->save();
		}
		else
		{
			$scope->scope_template->template_id=$request->input('template');
			$scope->scope_template->save();
		}
		
		
		
		
			return redirect()->route('scope-entry-list',['scope_id' =>$scope->id]);
	}


	public function bulk_update(Request $request,$scope_id)
	{

		$scope=Scope::where('user_id', Auth::id())->where('id',$scope_id)->firstOrFail();
		$validated = $request->validate([
			'domains' => 'required|max:64000'
		]);
		
		$domains=explode(PHP_EOL,$request->input('domains'));
		foreach($domains as $domain)
		{
			if($this->is_error_in_domain($domain)!==FALSE && $this->check_if_domain_is_ip_mask($domain)===FALSE)
			{
					return back()->withErrors(['errors' => "Input domains or ip lists have incorrect format"]);
			}
		}

		
		
		

		foreach($domains as $domain)
		{
			$domain=trim($domain);
			if($this->check_if_domain_is_ip_mask($domain)==true)continue;
			if($this->is_error_in_domain($domain)!=FALSE)continue;
			
			
			if(ScopeEntry::where("scope_id",$scope_id)->where("source",trim($domain))->first()!=null)continue;
			$tmp=new ScopeEntry;
			$tmp->type="domain";
			if(str_starts_with($domain, "*.")!==FALSE)$tmp->type="domain_list";
			
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();
			 		
			 
			 
		}
		
		
		foreach($domains as $domain)
		{
			$domain=trim($domain);
			if($this->check_if_domain_is_ip_mask($domain)===false)continue;
			if(ScopeEntry::where("scope_id",$scope_id)->where("source",trim($domain))->first()!=null)continue;
			$tmp=new ScopeEntry;
			$tmp->type="ip_list";
			
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();
 
		}
		
		
		
	return redirect()->route('scope-entry-list',['scope_id' =>$scope->id]);
	}



 	function is_error_in_domain($domain)
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



	function check_if_domain_is_ip_mask($domain)
	{
		$domain=trim($domain);
		    $parts = explode('/', $domain);
			if(count($parts) != 2) {
				return false;
			}

			$ip = $parts[0];
			$netmask = intval($parts[1]);

			if($netmask < 0) {
				return false;
			}

			if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
				return $netmask <= 32;
			}
			return false;
	}

	function validate_domain_list($domains)
	{
		
		$error=FALSE;
		foreach($domains as $domain)
		{
			if($this->check_if_domain_is_ip_mask($domain))continue;
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
			'domains' => 'required|max:64000',
			'template' => 'required'
		]);
		
		$domains=explode(PHP_EOL,$request->input('domains'));
		$error=$this->validate_domain_list($domains);
		$domainsCount=0;
		foreach($domains as $domain)
		{
			if($this->is_error_in_domain($domain)===FALSE || $this->check_if_domain_is_ip_mask($domain)!==FALSE)
			{
				$domainsCount++;
			}
		}
		
		if($domainsCount==0)
		{
			$msg="Input domains or ip lists have incorrect format";
			return back()->withErrors(['errors' => $msg]);
		}

		Template::findOrFail($request->input('template'));
		
		$scope=new Scope;
		$scope->name=$request->input('name');
		$scope->user_id=Auth::id();
		$scope->save();
		
		$scope_template=new ScopeTemplate;
		$scope_template->template_id=$request->input('template');
		$scope_template->scope_id=$scope->id;
		$scope_template->save();
		
		
		foreach($domains as $domain)
		{
			if($this->check_if_domain_is_ip_mask($domain)==true)continue;
			if($this->is_error_in_domain($domain)!=FALSE)continue;
			$domain=trim($domain);
			$tmp=new ScopeEntry;
			$tmp->type="domain";
			if(str_starts_with($domain, "*.")!==FALSE)$tmp->type="domain_list";
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();	 
		}
		
		foreach($domains as $domain)
		{
			if($this->check_if_domain_is_ip_mask($domain)==false)continue;
			$domain=trim($domain);
			$tmp=new ScopeEntry;
			$tmp->type="ip_list";
			$tmp->source=trim(strtolower($domain));
			$tmp->scope_id=$scope->id;
			$tmp->save();	 
		}
		
		
		
		if($error==FALSE)
			return redirect()->route('scope-entry-list',['scope_id' =>$scope->id]);
		else
			return redirect()->route('scope-entry-list',['scope_id' =>$scope->id])->withErrors(['errors' =>"Input domains have incorrect format:".$error]);
	}



}