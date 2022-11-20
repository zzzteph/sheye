<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateEntry extends Model
{
    use HasFactory;
	
	public function template()
    {
         return $this->belongsTo(Template::class);
    }
	
		public function scanner_entry()
    {
         return $this->belongsTo(ScannerEntry::class);
    }
	
}
