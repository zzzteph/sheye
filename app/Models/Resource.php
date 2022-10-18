<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\NewResource;
class Resource extends Model
{
	protected $touches = ['scope_entry'];
    use HasFactory;
	use SoftDeletes;
	

	
	public function services()
    {
        return $this->hasMany(Service::class);
    }
	
	public function print_services()
	{
		$result="";
		foreach($this->services() as $service)
		{
			$result.=$service->port." ";
		}
		return $result;
	}
	
		public function screenshots()
    {
        return $this->hasMany(Response::class)->where('size','!=',0);
    }
	
		public function findings()
    {
        return $this->hasMany(Finding::class);
    }
	
	public function responses()
    {
        return $this->hasMany(Response::class);
    }

	public function scope()
    {
        return $this->belongsTo(Scope::class);
    }
	
	
	
	
	public function scope_entry()
    {
        return $this->belongsTo(ScopeEntry::class);
    }
	
	
	    protected static function booted()
    {
        static::created(function ($tmp) {
           NewResource::dispatch($tmp);
        });
    }
	
}
