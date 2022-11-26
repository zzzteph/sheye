<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Scanner;
use App\Models\ScannerEntry;
use App\Models\Template;
use App\Models\TemplateEntry;
class TemplateSeeder extends Seeder
{
		public $amass;
		public $assetfinder;
		public $subfinder;
		public $dnsx;
		public $wayback;
		public $ncc;
		public $nch;
		public $ncm;
		public $nmap_all;
		public $nmap_default;
		public $nmap_http;
		public $screenshot;
		public $dirsearch;
    public function run()
    {
		$this->amass=ScannerEntry::where('name',"Amass")->first();
		$this->assetfinder=ScannerEntry::where('name',"Assetfinder")->first();
		$this->subfinder=ScannerEntry::where('name',"Subfinder")->first();
		$this->dnsx=ScannerEntry::where('name',"DNSX bruteforce")->first();
		$this->wayback=ScannerEntry::where('name',"WayBack")->first();
		$this->ncc=ScannerEntry::where('name',"Nuclei critical")->first();
		$this->nch=ScannerEntry::where('name',"Nuclei high")->first();
		$this->ncm=ScannerEntry::where('name',"Nuclei medium")->first();
		$this->nmap_all=ScannerEntry::where('name',"Nmap All")->first();
		$this->nmap_default=ScannerEntry::where('name',"Nmap default")->first();
		$this->nmap_http=ScannerEntry::where('name',"Nmap HTTP")->first();
		$this->screenshot=ScannerEntry::where('name',"Screenshots")->first();
		$this->dirsearch=ScannerEntry::where('name',"Dirsearch")->first();
		if($this->amass==null)
		{
			echo "amass missing";
			return;
		}
				if($this->assetfinder==null)
		{
			echo "assetfinder missing";
			return;
		}
				if($this->subfinder==null)
		{
			echo "subfinder missing";
			return;
		}
				if($this->dnsx==null)
		{
			echo "dnsx missing";
			return;
		}
				if($this->wayback==null)
		{
			echo "wayback missing";
			return;
		}
				if($this->ncc==null)
		{
			echo "ncc missing";
			return;
		}
				if($this->nch==null)
		{
			echo "nch missing";
			return;
		}
				if($this->ncm==null)
		{
			echo "ncm missing";
			return;
		}
				if($this->nmap_all==null)
		{
			echo "nmap_all missing";
			return;
		}
				if($this->nmap_default==null)
		{
			echo "nmap_default missing";
			return;
		}
				if($this->nmap_http==null)
		{
			echo "nmap_http missing";
			return;
		}
				if($this->screenshot==null)
		{
			echo "screenshot missing";
			return;
		}
		if($this->dirsearch==null)
		{
			echo "dirsearch missing";
			return;
		}
			
		
		
		
        $this->discovery_only_fast_scan();
		$this->discovery_only_full_scan();
		$this->default();
		$this->scan_full();
		$this->scan_full_tcp();
		$this->scan_full_tcp_nuclei();
		$this->scan_full_nuclei();
		$this->default_scan_nuclei();
		
		
		$this->nuclei_only();
		$this->find_vulns();
		$this->dirsearch_only();
    }
	
	
	
					public function dirsearch_only()
	{

		

		$template=new Template;
		$template->name="Dirsearch";
		$template->save();


				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dirsearch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		echo "Dirsearch only".PHP_EOL;
	}
	
	
				public function find_vulns()
	{

		

		$template=new Template;
		$template->name="Vulns only";
		$template->save();

		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncc->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncm->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dirsearch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		echo "Vulns only".PHP_EOL;
	}
	
	
			public function nuclei_only()
	{

		

		$template=new Template;
		$template->name="Nuclei only";
		$template->save();

		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncc->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncm->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		echo "Nuclei only".PHP_EOL;
	}
	
	
	
	
	
			public function default_scan_nuclei()
	{

		

		$template=new Template;
		$template->name="Default scan with nuclei";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();

		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();

		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncc->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncm->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		echo "Default scan (amass+subfinder+assetfinder)=>nmap_default=>(screenshot+nuclei all)".PHP_EOL;
	}
	
	
		public function scan_full_tcp_nuclei()
	{

		

		$template=new Template;
		$template->name="Full scan + tcp full scan with nuclei";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dnsx->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->wayback->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_all->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncc->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncm->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
	
		
		echo "Full scan + tcp full scan with nuclei (amass+subfinder+assetfinder+dnsx+wayback)=>(nmap_default+nmap_all)=>(screenshot+nuclei all)".PHP_EOL;
	}
	
	
	
			public function scan_full_nuclei()
	{

		

		$template=new Template;
		$template->name="Full scan with nuclei";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dnsx->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->wayback->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncc->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nch->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->ncm->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		echo "Scan full with nuclei added (amass+subfinder+assetfinder+dnsx+wayback)=>(nmap_default)=>(screenshot+nuclei all)".PHP_EOL;
	}
	
	
	
	
	
	
	
	
	
	
	public function scan_full_tcp()
	{

		

		$template=new Template;
		$template->name="Full scan + tcp full scan";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dnsx->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->wayback->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
				$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_all->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		echo "Scan full + full tcp added (amass+subfinder+assetfinder+dnsx+wayback)=>(nmap_default+nmap_all)=>(screenshot)".PHP_EOL;
	}
	
	
	
			public function scan_full()
	{

		

		$template=new Template;
		$template->name="Full scan";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dnsx->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->wayback->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		echo "Scan full added (amass+subfinder+assetfinder+dnsx+wayback)=>nmap_default=>screenshot".PHP_EOL;
	}
	
	
	
		public function default()
	{

		

		$template=new Template;
		$template->name="Default scan";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->nmap_default->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->screenshot->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		
		
		
		echo "Default fast added (amass+subfinder+assetfinder)=>nmap_default=>screenshot".PHP_EOL;
	}
	
	
	
	
	
		public function discovery_only_full_scan()
	{

		

		$template=new Template;
		$template->name="Discovery only full";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->dnsx->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->wayback->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		echo "Discovery full added (amass+subfinder+assetfinder+dnsx+wayback)".PHP_EOL;
	}
	
	
	
	
	public function discovery_only_fast_scan()
	{

		

		$template=new Template;
		$template->name="Discovery only fast";
		$template->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->amass->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->subfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		$template_entry=new TemplateEntry;
		$template_entry->scanner_entry_id=$this->assetfinder->id;
		$template_entry->template_id=$template->id;
		$template_entry->save();
		echo "Discovery Fast added (amass+subfinder+assetfinder)".PHP_EOL;
	}
	
	
}
