@include('include.header')

		
 <nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a href="{{route('dashboard')}}">Dashboard</a></li>
	<li><a href="{{route('dashboard')}}">{{$scope->name}}</a></li>
	 <li><a href="{{route('scope-entry-list',['scope_id'=>$scope_entry->scope_id])}}">{{$scope_entry->source}}</a></li>
  </ul>
</nav>
		<hr/>
  


<h1 class="title">{{$resource->name}}</h1>

		<table class="table is-fullwidth">
			<thead>
				<tr>
				   
					<th>Port</th>
					<th>Service</th>

				</tr>
			</thead>
			<tbody>
			
		   
				@foreach ($resource->services as $service)
		   <tr>
		  
				<td>
				
					 {{$service->port }}
				
				</td>

				 				<td>
				
					{{$service->service }}
				
					</td>
			</tr>
			@endforeach
		</tbody>
	</table>

			<table class="table is-fullwidth">
			<thead>
				<tr>
				   
					<th>Name</th>
					<th>Image</th>
					<th>Info</th>
					
				</tr>
			</thead>
			<tbody>
			
		   
				@foreach ($resource->screenshots as $screenshot)
		   <tr>
		  
				<td>
				
					 <a href="{{$screenshot->link }}" _target=blank>{{$screenshot->link }}</a>
					 
				
				</td>

				 <td>
					<p><strong>{{$screenshot->title }}</strong></p>
					<figure class="image">
						<a href="{{$screenshot->screen_url }}" target="_blank"><img src="{{$screenshot->screen_url }}"></a>
					</figure>
					</td>
					
					<td>
				
				<p><strong>Code:</strong>{{$screenshot->code }}</p>
				<p><strong>IP:</strong>{{$screenshot->ip }}</p>
				<p><strong>ASN:</strong>{{$screenshot->asn }}</p>
				<p><strong>Host:</strong>{{$screenshot->server }}</p>
				<p>{{$screenshot->created_at }}</p>
				
					</td>
					
			</tr>
			@endforeach
		</tbody>
	</table>

	


@include('include.footer')