<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Schedule extends Model
{
        use HasFactory;
	use SoftDeletes;
	
	
	public function scope()
    {
        return $this->belongsTo(Scope::class);
    }
	
		public function template()
    {
        return $this->belongsTo(Template::class);
    }
	
	
	
}
