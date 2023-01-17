<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Events\NewService;

class Service extends Model
{
	protected $touches = ['resource'];
    use HasFactory;
	use SoftDeletes;

	public function response()
    {
        return $this->hasOne(Response::class);
    }

	public function screenshot()
    {
        return $this->hasOne(Response::class)->where('size','!=',0);
    }
	
	
	public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
	

		public function scope_entry()
    {
        return $this->belongsTo(ScopeEntry::class);
    }
	public function scope()
    {
        return $this->belongsTo(Scope::class);
    }

	    protected static function booted()
    {
        static::created(function ($tmp) {
           NewService::dispatch($tmp);
        });
    }

	
}
