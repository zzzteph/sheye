<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScannerEntry extends Model
{
    use HasFactory;
	
	
	
		public function scanner()
    {
         return $this->belongsTo(Scanner::class);
    }
	
}
