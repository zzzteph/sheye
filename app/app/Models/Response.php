<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Response extends Model
{
    
    protected $touches = ['service'];
    use HasFactory;
	use SoftDeletes;

		    public function service()
    {
        return $this->belongsTo(Service::class);
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


	public function getScreenUrlAttribute()
	{
		return str_replace(storage_path()."/app/public","/storage",$this->path);

	}
	

	public function getPreviewUrlAttribute()
	{
		return str_replace(storage_path()."/app/public","/storage",$this->preview);

	}
	
		public function getTitleShortAttribute()
	{
		if (strlen($this->title) > 30)
			return substr($this->title, 0, 30) . '..';
		return $this->title;
	}
	
	
}
