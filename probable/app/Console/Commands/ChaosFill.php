<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Domain;
use App\Models\PartDomain;
use App\Models\Part;
use App\Models\SubDomain;
use App\Models\SubPartDomain;
use App\Models\SubPart;
use App\Models\Sub;
use App\Models\SubStat;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
class ChaosFill extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'chaos:analyze';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove jobs that stuck in queue';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }




    public function handle()
    {
		
		foreach(Domain::all() as $entry)
		{
			$entry->delete();
		}
		foreach(Part::all() as $entry)
		{
			$entry->delete();
		}
		foreach(SubDomain::all() as $entry)
		{
			$entry->delete();
		}
		foreach(SubPart::all() as $entry)
		{
			$entry->delete();
		}
		foreach(Sub::all() as $entry)
		{
			$entry->delete();
		}

		
		$directories = Storage::directories("chaos");
		

		foreach($directories as $entry)
		{
			if(Storage::exists($entry."/data.zip") && Storage::exists($entry."/output"))
			{
				$path = Storage::path($entry."/data.zip");
				$output = Storage::path($entry."/output");
				shell_exec("unzip -o $path -d $output");
				foreach(Storage::files($entry."/output") as $file)
				{
					echo basename($file,".txt")."...".Storage::size($file).PHP_EOL;
					
					$content=Storage::get($file);
					$lines=explode(PHP_EOL,$content);
					
					$domain_name=str_replace("*.","",basename($file,".txt"));
					$domain_name=str_replace("*","",$domain_name);
					$domain=Domain::where('name',$domain_name)->first();
					if($domain==null)
					{
						$domain=new Domain;
						$domain->name=$domain_name;
						$domain->save();
					}
					foreach($lines as $line)
					{
						$subdomain=SubDomain::where('name',trim($line))->where('domain_id',$domain->id)->first();
						if($subdomain==null)
						{
							$subdomain=new SubDomain;
							$subdomain->name=trim($line);
							$subdomain->domain_id=$domain->id;
							$subdomain->save();
						}
						
						$parts=str_replace(".".$domain_name,"",$line);
						
						
						
							$subs=Sub::where('name',$parts)->first();
							if($subs==null)
							{
								$subs=new Sub;
								$subs->name=$parts;
								$subs->save();
							}
							$sub_stat=SubStat::where('sub_id',$subs->id)->where('domain_id',$domain->id)->first();
							if($sub_stat==null)
							{
								$sub_stat=new SubStat;
								$sub_stat->domain_id=$domain->id;
								$sub_stat->sub_id=$subs->id;
								$sub_stat->save();
							}
						
						
						
						
						
						
						
						
						
						
						$parts=explode(".",$parts);
						for($i=0;$i<count($parts);$i++)
						{
							$part=Part::where('name',$parts[$i])->where('level',count($parts)-$i)->first();
							if($part==null)
							{
								$part=new Part;
								$part->name=$parts[$i];
								$part->level=count($parts)-$i;
								$part->save();
							}
							$part_domain=PartDomain::where('part_id',$part->id)->where('domain_id',$domain->id)->first();
							if($part_domain==null)
							{
								$part_domain=new PartDomain;
								$part_domain->domain_id=$domain->id;
								$part_domain->part_id=$part->id;
								$part_domain->save();
							}
							$subpart=SubPart::where('name',$parts[$i])->first();
							if($subpart==null)
							{
								$subpart=new SubPart;
								$subpart->name=$parts[$i];
								$subpart->save();
							}
							$subpart_domain=SubPartDomain::where('sub_part_id',$subpart->id)->where('domain_id',$domain->id)->first();
							if($subpart_domain==null)
							{
								$subpart_domain=new SubPartDomain;
								$subpart_domain->domain_id=$domain->id;
								$subpart_domain->sub_part_id=$subpart->id;
								$subpart_domain->save();
							}
						}
					}
				}
			}
			
		}
		
		
		
		

    }
}