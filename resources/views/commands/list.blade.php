@include('include.header')
<div class="content">
@if ($type =='amass')
<h1 class="title">Amass</h1>
 
The OWASP Amass Project performs network mapping of attack surfaces and external asset discovery using open source information gathering and active reconnaissance techniques.
<a href="https://github.com/OWASP/Amass" target="_blank">https://github.com/OWASP/Amass</a>
@elseif($type =='subfinder')
<h1 class="title">Subfinder</h1>
Subfinder is a subdomain discovery tool that discovers valid subdomains for websites by using passive online sources. It has a simple modular architecture and is optimized for speed. subfinder is built for doing one thing only - passive subdomain enumeration, and it does that very well.
<a href="https://github.com/projectdiscovery/subfinder" target="_blank">https://github.com/projectdiscovery/subfinder</a>
@elseif($type =='assetfinder')
<h1 class="title">Assetfinder</h1>
Find domains and subdomains potentially related to a given domain.

<a href="https://github.com/tomnomnom/assetfinder" target="_blank">https://github.com/tomnomnom/assetfinder</a>
@elseif($type =='gau')
<h1 class="title">gau</h1>
getallurls (gau) fetches known URLs from AlienVault's Open Threat Exchange, the Wayback Machine, Common Crawl, and URLScan for any given domain. Inspired by Tomnomnom's waybackurls.
<a href="https://github.com/lc/gau" target="_blank">https://github.com/lc/gau</a>
@elseif($type =='nuclei')
<h1 class="title">Nuclei</h1>
Nuclei is used to send requests across targets based on a template, leading to zero false positives and providing fast scanning on a large number of hosts. Nuclei offers scanning for a variety of protocols, including TCP, DNS, HTTP, SSL, File, Whois, Websocket, Headless etc. With powerful and flexible templating, Nuclei can be used to model all kinds of security checks.
<a href="https://github.com/projectdiscovery/nuclei" target="_blank">https://github.com/projectdiscovery/nuclei</a>

@elseif($type =='dirsearch')
<h1 class="title">Dirsearch</h1>
As a feature-rich tool, dirsearch gives users the opportunity to perform a complex web content discovering, with many vectors for the wordlist, high accuracy, impressive performance, advanced connection/request settings, modern brute-force techniques and nice output.
<a href="https://github.com/maurosoria/dirsearch" target="_blank">https://github.com/maurosoria/dirsearch</a>

@elseif($type =='nmap')
<h1 class="title">Nmap</h1>

<p><code>nmap -sT -sV --open </code></p>
<a href="https://nmap.org/" target="_blank">https://nmap.org/</a>

@elseif($type =='dnsb')
<h1 class="title">DNSX subdomain enumeration</h1>
<a href="https://github.com/projectdiscovery/dnsx" target="_blank">dnsx</a> is a fast and multi-purpose DNS toolkit allow to run multiple probes using retryabledns library, that allows you to perform multiple DNS queries of your choice with a list of user supplied resolvers, additionally supports DNS wildcard filtering like shuffledns.

<p>Subdomain enumeration will be performed with combinations of lists</p>

<ul>
	<li><a href="https://github.com/zzzteph/substats" target="_blank">https://github.com/zzzteph/substats</a></li>
		<li><a href="https://github.com/TheRook/subbrute" target="_blank">Subbrute names.txt</a></li>
	

</ul>

@endif




</div>
<div class="box">
	<form method="POST" action="{{route('commands-create')}}">
@csrf

<div class="field">
  <label class="label">Name</label>
  <div class="control">
    <input class="input" type="text" name="argument" placeholder="Your domain">
  </div>
</div>

 <input type="hidden" id="command" name="command" value="{{$type}}">


<div class="field">
  <div class="control">
    <button class="button is-black is-family-monospace">Launch</button>
  </div>

</div>


</form> 
</div>
<br/>		
		{{ $queues->links() }}

	
	   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
			   <th></th>
               <th>Domain</th>
               <th>Date</th>
			   <th></th>
            </tr>
         </thead>
         <tbody>
            @foreach ($queues as $queue)
            <tr>
			   <td>
                 @if($queue->status=='done')
				 <span class="icon is-small">
						 <i class="fas fa-check-circle"></i>
						</span>
				 @elseif($queue->status=='queued' || $queue->status=='running')
				 
				 				 <span class="icon is-small">
						  <i class="fas fa-cog fa-spin"></i>
						</span>
				 @elseif($queue->status=='todo')
				 
				 
				 @elseif($queue->status=='error')
				 
				 	 <span class="icon is-small">
						<i class="fas fa-exclamation-triangle"></i>
						</span>

				 @endif
               </td> 
               <td>
                 <a href="{{route('commands-view',['id' => $queue->id])}}"> {{ $queue->argument }}</a>
               </td>

               <td>
                   <a href="{{route('commands-view',['id' => $queue->id])}}"> {{ $queue->updated_at }}</a>
               </td> 



               <td>

				 <div class="control">
				   <form method="POST" action="{{route('commands-delete',['id' => $queue->id])}}">
                     @method('delete')
                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						  <i class="fas fa-times"></i>
						</span>
					  </button>
                  </form>
</div>

               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>


{{ $queues->links() }}
@include('include.footer')