<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Events\NewScopeEntry;
use Illuminate\Support\Facades\DB;
class ScopeEntry extends Model
{
	protected $touches = ['scope'];
    use HasFactory;
	use SoftDeletes;
	

	public function services()
    {
        return $this->hasMany(Service::class);
    }
			public function findings()
    {
        return $this->hasMany(Finding::class);
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
	public function getScreenshotsCountAttribute()
    {
		return $this->responses()->where('size','>',10592)->count();
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
}
