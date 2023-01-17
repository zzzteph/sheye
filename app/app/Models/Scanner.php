<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scanner extends Model
{
    use HasFactory;
	
	public function scanner_entries()
    {
        return $this->hasMany(ScannerEntry::class);
    }
	
	
	
}
