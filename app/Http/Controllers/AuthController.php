<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
	
	public function authentificate(Request $request)
	{

        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);
       	$user = User::where('name', $request->input('name'))->first();
			if(is_null($user)){	
				return back()->withErrors([
						'message' => 'User or password wrong',
					]);
			}	
			if (!Hash::check($request->input('password'), $user->password)) {
    				return back()->withErrors([
						'message' => 'User or password wrong',
					]);
			}
			
           Auth::loginUsingId($user->id);
           return redirect()->route('dashboard'); 

	}
	
	

	


  	public function login()
	{
		if(!Auth::check())
		{
			return view('login');	
		}
		else	
			return redirect()->route('dashboard');
	}


	
  	public function logout(Request $request)
	{
		Auth::logout();
		$request->session()->invalidate();
		$request->session()->regenerateToken();
		return redirect()->route('main');
	}
	

	
	

		
		
		

		
	}