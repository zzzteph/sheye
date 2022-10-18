<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
	
	public function queues()
    {
        return $this->hasMany(Queue::class);
    }

	public function command_queues()
    {
        return $this->hasMany(CommandQueue::class);
    }
		public function scopes()
    {
        return $this->hasMany(Scope::class);
    }
	
		public function getCountActiveTasksAttribute()
    {
		$result=0;
        $result+=$this->hasMany(Queue::class)->where('status','running')->count();
		$result+=$this->hasMany(Queue::class)->where('status','queued')->count();
		$result+=$this->hasMany(CommandQueue::class)->where('status','running')->count();
		$result+=$this->hasMany(CommandQueue::class)->where('status','queued')->count();
		return $result;
    }
	
	

	
	
	public function active_queues()
    {
        return $this->hasMany(Queue::class)->where('status','!=','done');
    }

		public function active_commands()
    {
        return $this->hasMany(CommandQueue::class)->where('status','!=','done');
    }
	
	

	
	
}
