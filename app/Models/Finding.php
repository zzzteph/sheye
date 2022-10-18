<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
	protected $touches = ['resource'];
    use HasFactory;
	
		public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
	
	    public function getFineReportAttribute()
    {
		$result="";
        $lines=explode(PHP_EOL,$this->report);
		foreach($lines as $line)
		{
			$data=explode("\t",$line,2);
			if(count($data)!=2)continue;
			$result.="<strong><a href='https://github.com/projectdiscovery/nuclei-templates/search?q=".$data[0]."' target='_blank'>".$data[0]."</a></strong> ".htmlentities($data[1])."<br/>";
		}
		return $result;
    }
}
