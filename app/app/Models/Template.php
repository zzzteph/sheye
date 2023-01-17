<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;
	
	
		public function template_entries()
    {
        return $this->hasMany(TemplateEntry::class);
    }
	
	
	
}
