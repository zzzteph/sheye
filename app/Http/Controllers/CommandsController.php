<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\CommandQueue;
class CommandsController extends Controller
{
		function validate_domain_list($domain)
	{
		
		$error=FALSE;
		$domain=trim($domain);
		if(str_starts_with($domain, "*.")!==FALSE)
		{
			$domain=substr($domain, 2);
		}

		 if(!filter_var($domain,FILTER_VALIDATE_DOMAIN,FILTER_FLAG_HOSTNAME) )
		 {
			return "Domain validation failed";
		 }
			$parts=explode(".",$domain);
		

		if(count($parts)>9 || count($parts)<2)
		{
			return "Unable to validate";
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
				return "Unable to validate";
		}
	
	
		return FALSE;
	}
	
	public function list($type)
	{
			switch($type)
			{
				case "amass":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','amass')->orderBy('id', 'desc')->paginate(20)]);
				case "subfinder":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','subfinder')->orderBy('id', 'desc')->paginate(20)]);
				case "assetfinder":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','assetfinder')->orderBy('id', 'desc')->paginate(20)]);
				case "gau":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','gau')->orderBy('id', 'desc')->paginate(20)]);

				case "dnsb":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','dnsb')->orderBy('id', 'desc')->paginate(20)]);
				case "nuclei":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','nuclei')->orderBy('id', 'desc')->paginate(20)]);
								case "dirsearch":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','dirsearch')->orderBy('id', 'desc')->paginate(20)]);
													case "nmap":return view('commands.list',['type'=>$type,'queues'=>Auth::user()->command_queues()->where('type','nmap')->orderBy('id', 'desc')->paginate(20)]);

			}
			return back();	
	}
	
	public function insert(Request $request)
	{
		$validated = $request->validate([
			'argument' => 'required|max:64|min:4',
			'command' => 'required',
			
		]);
		$type=$request->input('command');
		if($type!=='nuclei' && $type!=='dirsearch')
		{
			$error=$this->validate_domain_list($request->input('argument'));
			if($error!==false)
			{
				return back()->withErrors(['errors' =>$error]);
			}
		}
		
		if($type!=='amass' && $type!=='subfinder' && $type!=='assetfinder' && $type!=='gau'  && $type!=='dnsb' && $type!=='nuclei' && $type!=='dirsearch'&& $type!=='nmap')
		{
			return back()->withErrors(['errors' =>"Unknown command"]);
		}
		
		$command=new CommandQueue;
		$command->user_id=Auth::id();
		$command->type=$type;
		$command->argument=strtolower($request->input('argument'));
		$command->status="todo";
		$command->report="";
		$command->save();
		
		
		
		return back();
	}
	public function delete($id)
	{
		$command=	CommandQueue::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		CommandQueue::findOrFail($id)->delete();
		return back();
	}

	public function view($id)
	{
		$command=	CommandQueue::where('user_id', Auth::id())->where('id',$id)->firstOrFail();
		if($command->status=='done')
		return response(gzuncompress(base64_decode($command->report)), 200)
                  ->header('Content-Type', 'text/plain');
		return back();		  
	}
}