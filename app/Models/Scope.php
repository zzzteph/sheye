<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
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
	
	public function getCriticalFindingsCountAttribute()
    {
        if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_critical_findings", $cache_timeout, function () {
				return $this->outputs()->where('severity','critical')->count();
			});
			return $count;
		}
		else
		{
			return $this->outputs()->where('severity','critical')->count();
		}
		
    }
	
		public function getHighFindingsCountAttribute()
    {
        if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_high_findings", $cache_timeout, function () {
				return $this->outputs()->where('severity','high')->count();
			});
			return $count;
		}
		else
		{
			return $this->outputs()->where('severity','high')->count();
		}
		
    }
	
	
			public function getMediumFindingsCountAttribute()
    {
        if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_medium_findings", $cache_timeout, function () {
				return $this->outputs()->where('severity','medium')->count();
			});
			return $count;
		}
		else
		{
			return $this->outputs()->where('severity','medium')->count();
		}
		
    }
	
				public function getLowFindingsCountAttribute()
    {
        if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_low_findings", $cache_timeout, function () {
				return $this->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
			});
			return $count;
		}
		else
		{
			return $this->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
		}
		
    }
	
	
	
	
	
	public function getScreenshotsCountAttribute()
    {
			if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_screenshots", $cache_timeout, function () {
				return $this->responses()->where('size','>',10592)->count();
			});
			return $count;
		}
		else
		{
			return $this->responses()->where('size','>',10592)->count();
		}
		
		
		
		
    }
	

	public function getProgressAttribute()
    {

		if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=60;
			$count = Cache::remember('scope_'.$this->id."_progress", $cache_timeout, function () {
				return $this->queues()->where('status','!=', 'done')->count();
			});
			return $count;
		}
		else
		{
			return $this->queues()->where('status','!=', 'done')->count();
		}


		

		
    }
	
	
	public function getScopeEntriesCountAttribute()
    {
		

		if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_scope_entries", $cache_timeout, function () {
				return $this->scope_entries()->count();
			});
			return $count;
		}
		else
		{
			return $this->scope_entries()->count();
		}
		
    }
	
		public function getResourcesCountAttribute()
    {
		if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_'.$this->id."_resources", $cache_timeout, function () {
				return $this->resources()->count();
			});
			return $count;
		}
		else
		{
			return $this->resources()->count();
		}
		
    }
	
	
	
	
	
	
}
