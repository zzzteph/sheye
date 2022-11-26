<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scanner;
use App\Models\ScannerEntry;
class ScanEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->amass_discovery();
		$this->asset_discovery();
		$this->subfinder_discovery();
		$this->dnsb_discovery();
		$this->wayback_discovery();
		$this->nuclei_resource();
		$this->nmap_resource();
		
		$this->screen_service();
		$this->dirsearch_service();
		
    }
	
	
	
	public function amass_discovery()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Discovery\\AmassJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Amass";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Amass Scanner".PHP_EOL;
		}
	
	}
	
		public function asset_discovery()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Discovery\\AssetJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Assetfinder";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Assetfinder Scanner".PHP_EOL;
		}
	
	}
	public function subfinder_discovery()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Discovery\\SubfinderJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Subfinder";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Subfinder Scanner".PHP_EOL;
		}
	
	}
	
	public function dnsb_discovery()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Discovery\\DnsbJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="DNSX bruteforce";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added DNSX bruteforce Scanner".PHP_EOL;
		}
	
	}	
	
	
		public function wayback_discovery()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Discovery\\WayBack')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="WayBack";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added WayBack Scanner".PHP_EOL;
		}
	
	}	
	
	
	
	public function nuclei_resource()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Resource\\NucleiScan')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nuclei critical";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->arguments="critical";
			$scan_entry->save();
			echo "Added Nuclei critical Scanner".PHP_EOL;
		}
	
			if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nuclei high";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->arguments="high";
			$scan_entry->save();
			echo "Added Nuclei high Scanner".PHP_EOL;
		}
		
				if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nuclei medium";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->arguments="medium";
			$scan_entry->save();
			echo "Added Nuclei medium Scanner".PHP_EOL;
		}
	
	
	
	}	
			public function nmap_resource()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Resource\\NmapJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nmap All";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->arguments="1-65535";
			$scan_entry->save();
			echo "Added Nmap all Scanner".PHP_EOL;
		}
		
		
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nmap HTTP";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->arguments="80,443,8080,8443";
			$scan_entry->save();
			echo "Added Nmap HTTP Scanner".PHP_EOL;
		}

		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Nmap default";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Nmap HTTP Scanner".PHP_EOL;
		}



	}	
	
		public function screen_service()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Resource\\AnalyzeService')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Screenshots";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Screenshots Scanner".PHP_EOL;
		}

	}	
	
	
	public function dirsearch_service()
	{
		$scanner=Scanner::where('class','\\App\\Jobs\\Resource\\DirsearchJob')->first();
		if($scanner!=null)
		{
			$scan_entry=new ScannerEntry;
			$scan_entry->name="Dirsearch";
			$scan_entry->scanner_id=$scanner->id;
			$scan_entry->save();
			echo "Added Dirsearch Scanner".PHP_EOL;
		}

	}	
	
}
