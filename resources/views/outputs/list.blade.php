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
  
  <h1 class="title">Findings</h1>

	
  
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
  
  
  
  
{{ $findings->links() }}

  <div class="container">
  
  @foreach ($findings as $finding)
    <div class="mb-5 columns is-multiline is-vcentered">
      <div class="column is-6-tablet is-5-desktop">
        <div>

          <h2 class="my-4 is-size-4 is-size-5-mobile has-text-weight-bold">
		  
		  <a href="{{route('resources-view',
		  ['scope_id'=>$finding->resource->scope_id,
		  'scope_entry_id'=>$finding->resource->scope_entry_id,
		  'resource_id'=>$finding->resource->id])}}">{{$finding->resource->name }}</a></h2>
          <span><small class="has-text-grey-dark">{{$finding->created_at }}</small></span>
        </div>
      </div>
      <div class="column is-6-tablet is-offset-1-desktop">
			  <p><a href="{{route('finding-view',
		  ['id'=>$finding->id])}}">View</a></p>
			{!! $finding->fine_report !!}
			
		
      </div>
    </div>
	@endforeach
  </div>

<hr/>

		

{{ $findings->links() }}	


@include('include.footer')