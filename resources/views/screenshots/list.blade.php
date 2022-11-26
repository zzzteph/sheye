@include('include.header')

		
 <nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">Dashboard</a></li>
	<li><a  class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">{{$scope->name}}</a></li>
	@if(isset($scope_entry))
	 <li><a class="has-text-warning is-family-monospace" href="{{route('scope-entry-list',['scope_id'=>$scope_entry->scope_id])}}">{{$scope_entry->source}}</a></li>
 @endif
  </ul>
</nav>
		<hr/>
  
  <h1 class="title">Screenshots</h1>

	
  
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
  
  
  
  
{{ $screenshots->links() }}

  <div class="container">
  
  @foreach ($screenshots as $screenshot)
    <div class="mb-5 columns is-multiline is-vcentered">
      <div class="column is-6-tablet is-5-desktop">
        <div>

          <h2 class="my-4 is-size-4 is-size-5-mobile has-text-weight-bold">{{$screenshot->title }}</h2>
          <span><small class="has-text-grey-dark">{{$screenshot->created_at }}</small></span>
          <p>
		   <a href="{{$screenshot->link }}" target="_blank">{{$screenshot->link }}</a><br/>
 			@if(strlen($screenshot->code)>0)	<p><strong>Code: </strong>{{$screenshot->code }}</p>@endif
			@if(strlen($screenshot->ip)>0)	<p><strong>IP: </strong>{{$screenshot->ip }}</p>@endif
			@if(strlen($screenshot->asn)>0)	<p><strong>ASN: </strong>{{$screenshot->asn }}</p>@endif
			@if(strlen($screenshot->server)>0)	<p><strong>Host: </strong>{{$screenshot->server }}</p>@endif
		  </p>
        </div>
      </div>
      <div class="column is-6-tablet is-offset-1-desktop">
	  <a href="{{$screenshot->link }}" target="_blank">
        <img class="image is-fullwidth" src="{{$screenshot->screen_url }}" alt="">
		</a>
      </div>
    </div>
	@endforeach
  </div>

<hr/>

		

{{ $screenshots->links() }}	


@include('include.footer')