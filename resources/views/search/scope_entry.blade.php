@include('include.header')

 <nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">Dashboard</a></li>
	@if(!isset($scope_entry))
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">{{$scope->name}}</a></li>
	  <li><a class="has-text-warning is-family-monospace" href="#">Resources</a></li>
	@else
	<li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">{{$scope->name}}</a></li>
	 <li><a class="has-text-warning is-family-monospace" href="{{route('scope-entry-list',['scope_id'=>$scope_entry->scope_id])}}">{{$scope_entry->source}}</a></li>

	@endif
  </ul>
</nav>
<hr/>

<h1 class="title">Search</h1>
<form method="POST" action="{{route('scopes-search',['scope_id'=>$scope->id])}}">
@csrf

<div class="field has-addons">
   <div class="control is-expanded">
    <input class="input" type="text" name="term" placeholder="Search for a string...">
  </div>
  <div class="control">
    <button class="button is-black is-family-monospace">
      Search
    </button>
  </div>
</div>
</form>


@if($responses->count()>0)
	 <div class="table-container">
      <table class="table is-fullwidth">
         <thead>
            <tr>
               <th>Link</th>
			   <th>Image</th>
			   <th>Info</th>
			   <th>Open Resource</th>
			   
            </tr>
         </thead>
         <tbody>
				@foreach ($responses as $response)
		   <tr>
		  
				<td>
					 <a href="{{$response->link }}" _target=blank>{{$response->link }}</a>
					 
				
				</td>

				 <td>
					<p><strong>{{$response->title }}</strong></p>
					<figure class="image">
						<a href="{{$response->screen_url }}" target="_blank"><img src="{{$response->screen_url }}"></a>
					</figure>
					</td>
					
					<td>
				
				<p><strong>Code: </strong>{{$response->code }}</p>
				<p><strong>IP: </strong>{{$response->ip }}</p>
				<p><strong>ASN: </strong>{{$response->asn }}</p>
				<p><strong>Host: </strong>{{$response->server }}</p>
				<p>{{$response->created_at }}</p>
				
					</td>
									<td>
					   <a href="{{route('resources-view',['scope_id' =>$response->scope_id,'scope_entry_id' =>$response->scope_entry_id,'resource_id'=>$response->resource_id])}}"> 
					 
					 {{$response->resource->name}}
						</a>
				</td>

			</tr>
			@endforeach
         </tbody>
      </table>
	  </div>
@endif




@include('include.footer')