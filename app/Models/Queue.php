<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Queue extends Model
{
    	protected $touches = ['scope'];
    use HasFactory;
	use SoftDeletes;
	
	
	public function scope()
    {
        return $this->belongsTo(Scope::class);
    }
	
	
		public function resource()
    {
        return Resource::find($this->object_id);
    }
	
		public function scope_entry()
    {
        return ScopeEntry::find($this->object_id);
    }
	
			public function service()
    {
        return Service::find($this->object_id);
    }
	
}
