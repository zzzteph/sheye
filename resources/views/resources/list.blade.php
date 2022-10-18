@include('include.header')


 <nav class="breadcrumb " aria-label="breadcrumbs">
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

	<h1 class="title ">Resources</h1>	

	




					

	
	

		
		@if(isset($scope_entry))
  <form method="POST" action="{{route('resources-search',['scope_entry_id'=>$scope_entry->id])}}">
@else
	<form method="POST" action="{{route('scopes-search',['scope_id'=>$scope->id])}}">
@endif
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
<br/>	



	
		{{ $resources->links() }}
	


	@foreach ($resources as $resource)
  <div class="container">


        <h2 class="mt-2 mb-4 is-size-5-mobile is-size-4 is-multiline">
			     <a href="{{route('resources-view',['scope_id' =>$scope->id,'scope_entry_id' =>$resource->scope_entry_id,'resource_id'=>$resource->id])}}">
			<p class=" is-3">{{$resource->name }}</p></a>
		<span class="help">{{$resource->updated_at}}</span>
		
		</h2>
        <p class="subtitle">
		
					@foreach ($resource->services as $service)
				<span class="subtitle is-6"><strong>{{$service->port}}</strong> {{$service->service}}</span><br/>
			@endforeach
		
		</p>

    <div class="mb-5 columns is-multiline">
	  @foreach ($resource->screenshots as $screenshot)
	  @if($loop->count==4)
	  @break
		@endif
      <div class="column is-6 is-4-desktop mb-4">
        <div class="box p-5 has-background-light">
          <div class="mb-4 is-relative">
			<a href="{{$screenshot->link}}" target="_blank">
            <img class="image is-fullwidth" src="{{$screenshot->preview_url}}" alt="">
			</a>
          </div>
          
          <h2 class="mb-4 is-size-4 has-text-weight-bold">{{$screenshot->title_short}}</h2>
		  <span><small class="has-text-grey-dark">{{$screenshot->created_at}}</small></span>
        				
				@if(strlen($screenshot->ip)>0)<p><strong>IP: </strong>{{$screenshot->ip }}</p>@endif

				@if(strlen($screenshot->server)>0)<p><strong>Host: </strong>{{$screenshot->server }}</p>@endif
        </div>
      </div>
	@endforeach




    </div>

  </div>
<hr/>  
   @endforeach	





{{ $resources->links() }}
@include('include.footer')