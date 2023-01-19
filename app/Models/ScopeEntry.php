<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Events\NewScopeEntry;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
class ScopeEntry extends Model
{
	protected $touches = ['scope'];
    use HasFactory;
	use SoftDeletes;
	

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
        return $this->hasMany(Resource::class);
    }

	public function responses()
    {
        return $this->hasMany(Response::class);
    }

	public function scope()
    {
        return $this->belongsTo(Scope::class);
    }
	
	
	public function queues()
    {
        return Queue::where('object_type','scope_entry')->where('object_id',$this->id)->get();
    }
	
	public function service_export()
    {
        return DB::table('services')
            ->join('resources', 'services.resource_id', '=', 'resources.id')
			->where('services.scope_entry_id',$this->id)
            ->select('services.*', 'resources.name')
            ->get();
    }
	
    protected static function booted()
    {
        static::created(function ($tmp) {
           NewScopeEntry::dispatch($tmp);
        });
    }
		public function screenshots()
    {
        return $this->hasMany(Response::class)->where('size','!=',0);
    }

	public function getNewResourceCountAttribute()
    {
		$count=0;
		foreach($this->resources as $resource)
		{
			if($resource->created_at->diffInHours(Carbon::now())<24)
			{
				$count++;
			}
		}
		return $count;
    }
	
			public function getResourcesCountAttribute()
    {
		if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_entry_'.$this->id."_resources", $cache_timeout, function () {
				return $this->resources()->count();
			});
			return $count;
		}
		else
		{
			return $this->resources()->count();
		}
		
    }
	
		
	public function getScreenshotsCountAttribute()
    {
			if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_entry_'.$this->id."_screenshots", $cache_timeout, function () {
				return $this->responses()->where('size','>',10592)->count();
			});
			return $count;
		}
		else
		{
			return $this->responses()->where('size','>',10592)->count();
		}
		
		
		
		
    }
	
	
	
		public function getCriticalFindingsCountAttribute()
    {
        if(env('ENABLE_DATA_CACHE')===true)
		{
			$cache_timeout=env('CACHE_TIMEOUT',120);
			$count = Cache::remember('scope_entry_'.$this->id."_critical_findings", $cache_timeout, function () {
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
			$count = Cache::remember('scope_entry_'.$this->id."_high_findings", $cache_timeout, function () {
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
			$count = Cache::remember('scope_entry_'.$this->id."_medium_findings", $cache_timeout, function () {
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
			$count = Cache::remember('scope_entry_'.$this->id."_low_findings", $cache_timeout, function () {
				return $this->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
			});
			return $count;
		}
		else
		{
			return $this->outputs()->where('severity','!=','critical')->where('severity','!=','high')->where('severity','!=','medium')->count();
		}
		
    }
	
	
	
	
	
	
	
}
