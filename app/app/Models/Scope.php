<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
class Scope extends Model
{
    use HasFactory;
	use SoftDeletes;
	
	
	public function scope_template()
    {
        return $this->hasOne(ScopeTemplate::class);
    }
	
	public function services()
    {
        return $this->hasMany(Service::class);
    }
			public function outputs()
    {
        return $this->hasMany(Output::class);
    }
	public function resources()
    {
        return $this->hasMany( Resource::class);
    }
	
		public function responses()
    {
		
		return $this->hasMany( Response::class);
    }
		public function screenshots()
    {
        return $this->responses()->where('size','>',10592);
    }
	
		public function scope_entries()
    {
        return $this->hasMany(ScopeEntry::class);
    }
	

	

	public function queues()
    {
        return $this->hasMany(Queue::class);
    }
	
	public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
	
	public function getScreenshotsCountAttribute()
    {
		return $this->responses()->where('size','>',10592)->count();
    }
	

		public function getProgressAttribute()
    {

		return $this->queues()->where('status','!=', 'done')->count();

		
    }
}
