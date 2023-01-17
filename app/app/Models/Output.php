<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Output extends Model
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
		
		if($this->type=='nuclei')
		{
			$lines=explode(PHP_EOL,$this->report);
			
			foreach($lines as $line)
			{
				$data=explode("\t",$line,2);
				if(count($data)!=2)continue;
				$result.="<strong><a href='https://github.com/projectdiscovery/nuclei-templates/search?q=".$data[0]."' target='_blank'>".$data[0]."</a></strong> ".htmlentities($data[1])."<br/>";
			}
			return $result;
		}
		if($this->type=='dirsearch')
		{
			
			$lines=explode(PHP_EOL,$this->report);
			for($i=2;$i<count($lines);$i++)
			{
				if($i>10)break;
				$result.=$lines[$i].PHP_EOL;
			}
			return "<h1>Count:".count($lines)."</h1><pre>".$result."</pre>";
		}
		
    }
}
