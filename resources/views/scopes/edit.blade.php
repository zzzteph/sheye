@include('include.header')

<h1 class="title">Create new project</h1>					
<form method="POST" action="{{route('scopes-update',['id'=>$scope->id])}}">
@method('PUT')
			@csrf

		<label class="label">Name</label>
	<div class="field">

	  <p class="control is-expanded">
			<input class="input" type="text" name="name" value="{{$scope->name}}">
	  </p>
</div>


<div class="field">
  <div class="control">
    <button class="button is-success">Update</button>
  </div>
</div>





</form>




<br/>
<hr/>
<h5 class="title is-5">Bulk add</h5>
<form method="POST" action="{{route('scope-entry-bulk-update',['scope_id'=>$scope->id])}}">
@method('PUT')
			@csrf

		<div class="field">
	  <label class="label">Domain list</label>
	  <div class="control">
			 <textarea class="textarea" placeholder="*.example.com" name="domains"></textarea>
	  </div>
	    <p class="help">Each domain should start from new line.</p>

	</div>

<div class="field">
  <div class="control">
    <button class="button is-success">Add</button>
  </div>
</div>




</form>




<hr/>

<h5 class="title is-5">Entries({{$scope->scope_entries()->count()}})</h5>

{{ $scope_entries->links() }}

<table class="table is-fullwidth">

<thead>
	<tr>	
		<th>
		Name
		</th>
		<th></th>
	</tr>
</thead>


<tbody>

@foreach($scope_entries as $entry)
<tr>
@if ($entry->type =='domain' || $entry->type =='domain_list')
	

	<td>
<strong>{{$entry->source}}</strong>

   <p class="help">{{$entry->created_at}}</p>
	</td>



<td>

  <form method="POST" action="{{route('scope-entry-delete',['scope_id'=>$scope->id,'id'=>$entry->id])}}">
  @method('DELETE')
			@csrf

  
                       <button class="button is-small has-background-danger-dark has-text-white-bis">
						
						<span class="icon is-small">
						  <i class="fas fa-trash-alt"></i>
						</span>
					  </button>
</form>
</td>



  



@endif
</tr>
@endforeach





</tbody>

</table>








	

@include('include.footer')