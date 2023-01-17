<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
class APITokenValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
		$key=null;
		if($request->header('API-KEY')!=null)
			$key = $request->header('API-KEY');
		if($request->input('api_key')!=null)
			$key = $request->input('api_key');
		 if ($key==null) {
			return response()->json(['error' => 'No api_key set']);
        }
	
		$user=User::where('api_key',$key)->first();
		if($user==null)
		{
			return response()->json(['error' => 'No valid api_key']);
		}
		
		
		
        return $next($request);
    }
}
