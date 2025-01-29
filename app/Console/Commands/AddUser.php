<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class AddUser extends Command
{
    protected $signature = 'app:add-user 
    {name : Username}
    {email : User email, unique}
    {password : Password}
    {role? : user roles - user, admin or main_admin}';
    protected $description = 'Add new user';
    public function __construct()
    {
        parent::__construct();
    }
    public function handle()
    {
        $validator = Validator::make(['email' => $this->argument('email')],[  'email' => 'email']);
        if(!$validator->passes()){
            $this->error('Email validation failed - '.$this->argument('email'));
            return;
        }
        
		$user=new User;
        $user->email=$this->argument('email');
        
        
        switch($this->argument('role'))
        {
            case "admin": $user->role="admin";break;
            case "main_admin": $user->role="main_admin";break;
            default:$user->role="user";
            
        }

        $user->name=$this->argument('name');

		$user->password=Hash::make($this->argument('password'));
   
        try {
            if ($user->save()) {
                $this->info('User saved successfully.');
            } else {
                $this->error('Failed to save the user.');
            }
        } catch (\Exception $e) {
            $this->error('Error saving user: ' . $e->getMessage());
        }
    }
}