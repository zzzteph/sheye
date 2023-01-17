<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScopeTemplate extends Model
{
    use HasFactory;
	
		public function template()
    {
         return $this->belongsTo(Template::class);
    }
	
		public function scope()
    {
         return $this->belongsTo(Scope::class);
    }
}
