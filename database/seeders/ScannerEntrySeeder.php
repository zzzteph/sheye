<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scanner;
use App\Models\ScannerEntry;
class ScannerEntrySeeder extends Seeder
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
		$this->nmap_resource();
		$this->nuclei_resource();
		$this->screen_service();
		$this->dirsearch_service();
		
		
		
		
		
    }
	
	
	function dirsearch_service()
	{
		echo "Searching for \\App\\Jobs\\Resource\\DirsearchJob".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Resource\\DirsearchJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Resource\\DirsearchJob";
			$scanner->has_arguments=true;
			$scanner->type="service";
			$scanner->description="Dirsearch job";
			$scanner->save();
			echo "Added \\App\\Jobs\\Resource\\DirsearchJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Resource\\DirsearchJob".PHP_EOL;
		}

	}
	
	
	
						function screen_service()
	{
		echo "Searching for \\App\\Jobs\\Resource\\AnalyzeService".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Resource\\AnalyzeService')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Resource\\AnalyzeService";
			$scanner->has_arguments=false;
			$scanner->type="service";
			$scanner->description="Get screenshots and page content";
			$scanner->save();
			echo "Added \\App\\Jobs\\Resource\\AnalyzeService".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Resource\\AnalyzeService".PHP_EOL;
		}

	}
	
	
	
	
					function nuclei_resource()
	{
		echo "Searching for \\App\\Jobs\\Resource\\NucleiScan".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Resource\\NucleiScan')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Resource\\NucleiScan";
			$scanner->has_arguments=true;
			$scanner->type="service";
			$scanner->description="Scan hosts with Nuclei";
			$scanner->save();
			echo "Added \\App\\Jobs\\Resource\\NucleiScan".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Resource\\NucleiScan".PHP_EOL;
		}

	}
	
	
				function wayback_discovery()
	{
		echo "Searching for \\App\\Jobs\\Discovery\\WayBack".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Discovery\\WayBack')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Discovery\\WayBack";
			$scanner->has_arguments=false;
			$scanner->type="discovery";
			$scanner->description="getallurls (gau) fetches known URLs from AlienVault's Open Threat Exchange, the Wayback Machine, Common Crawl, and URLScan for any given domain. Inspired by Tomnomnom's waybackurls. https://github.com/lc/gau";
			$scanner->save();
			echo "Added \\App\\Jobs\\Discovery\\WayBack".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Discovery\\WayBack".PHP_EOL;
		}

	}
	
		
	
	
			function dnsb_discovery()
	{
		echo "Searching for \\App\\Jobs\\Discovery\\DnsbJob".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Discovery\\DnsbJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Discovery\\DnsbJob";
			$scanner->has_arguments=true;
			$scanner->type="discovery";
			$scanner->description="dnsx is a fast and multi-purpose DNS toolkit allow to run multiple probes using retryabledns library, that allows you to perform multiple DNS queries of your choice with a list of user supplied resolvers, additionally supports DNS wildcard filtering like shuffledns.Subdomain enumeration will be performed with combinations of lists. You can specify own list";
			$scanner->save();
			echo "Added \\App\\Jobs\\Discovery\\DnsbJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Discovery\\DnsbJob".PHP_EOL;
		}

	}
	
		
		function subfinder_discovery()
	{
		echo "Searching for \\App\\Jobs\\Discovery\\SubfinderJob".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Discovery\\SubfinderJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Discovery\\SubfinderJob";
			$scanner->has_arguments=false;
			$scanner->type="discovery";
			$scanner->description="Subfinder is a subdomain discovery tool that discovers valid subdomains for websites by using passive online sources. It has a simple modular architecture and is optimized for speed. subfinder is built for doing one thing only - passive subdomain enumeration, and it does that very well. https://github.com/projectdiscovery/subfinder";
			$scanner->save();
			echo "Added \\App\\Jobs\\Discovery\\SubfinderJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Discovery\\SubfinderJob".PHP_EOL;
		}

	}
	
	
	
		function asset_discovery()
	{
		echo "Searching for \\App\\Jobs\\Discovery\\AssetJob".PHP_EOL;
		if(Scanner::where('class','\\Jobs\\Discovery\\AssetJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Discovery\\AssetJob";
			$scanner->has_arguments=false;
			$scanner->type="discovery";
			$scanner->description="Find domains and subdomains potentially related to a given domain. https://github.com/tomnomnom/assetfinder";
			$scanner->save();
			echo "Added \\App\\Jobs\\Discovery\\AssetJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Discovery\\AssetJob".PHP_EOL;
		}

	}
	
	
	
	
	function amass_discovery()
	{
		echo "Searching for \\App\\Jobs\\Discovery\\AmassJob".PHP_EOL;
		if(Scanner::where('class','\\App\\Jobs\\Discovery\\AmassJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Discovery\\AmassJob";
			$scanner->has_arguments=false;
			$scanner->type="discovery";
			$scanner->description="The OWASP Amass Project performs network mapping of attack surfaces and external asset discovery using open source information gathering and active reconnaissance techniques. https://github.com/OWASP/Amass";
			$scanner->save();
			echo "Added \\App\\Jobs\\Discovery\\AmassJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \Jobs\Discovery\AmassJob".PHP_EOL;
		}

	}
	
		function nmap_resource()
	{
		echo "Searching for \\App\\Jobs\\Resource\\NmapJob".PHP_EOL;
		if(Scanner::where('class','\\App\\Jobs\\Resource\\NmapJob')->first()==null)
		{
			$scanner=new Scanner();
			$scanner->class="\\App\\Jobs\\Resource\\NmapJob";
			$scanner->has_arguments=true;
			$scanner->type="resource";
			$scanner->description="Nmap scan. With arguments you can specify ports.";
			$scanner->save();
			echo "Added \\App\\Jobs\\Resource\\NmapJob".PHP_EOL;
		}
		else
		{
			echo "Skipping \\App\\Jobs\\Resource\\NmapJob".PHP_EOL;
		}

	}
	
	
}
