@include('include.header')

<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">Dashboard</a></li>
    <li class="is-active"><a href="#" class="has-text-warning is-family-monospace" aria-current="page">{{$scope->name}}</a></li>
  </ul>
</nav>
<hr/>

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
<br/>


<h1 class="title">{{$scope->name}}</h1>





<div class="box">
			
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
      <div class="select">
        <select name="template">
		 <option value="" disabled selected>Select template</option>
            @foreach ($templates as $template)
			
			@if($scope->scope_template!==null && $template->id == $scope->scope_template->template_id)
                <option value="{{$template->id}}" selected>{{$template->name}}</option>
			@else
				<option value="{{$template->id}}">{{$template->name}}</option>
			@endif
            @endforeach
        </select>
      </div>
    </div>
	 <p class="help">Default workflow.</p>
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

</div>


<hr/>


	


<h5 class="title is-5">Entries({{$scope->scope_entries()->count()}})</h5>



{{ $scope_entries->links() }}



<br/>
   <div class="table-container">
      <table class="table is-fullwidth">
         <thead>
            <tr>
               <th>Entries</th>
			   <th>Resources</th>
               <th><span class="icon"><i class="fas fa-image"></i></span></th>
						   <th><span class="icon"><i class="fas fa-exclamation-triangle"></i></span></th>
			    <th><span class="icon"><i class="fas fa-exclamation-circle"></i></span></th>
			   <th><span class="icon"><i class="fas fa-exclamation"></i></span></th>
			   <th></th>
            </tr>
         </thead>
         <tbody>
            @foreach ($scope_entries as $scope_entry)
            <tr>
               <td>
                 <a href="{{route('scope_entries-resources-list',['scope_id'=>$scope->id,'id' => $scope_entry->id])}}"> {{ $scope_entry->source }}</a>
				  <p class="help">{{$scope_entry->created_at}}</p>
               </td>
			   <td>
                  <a href="{{route('scope_entries-resources-list',['scope_id'=>$scope->id,'id' => $scope_entry->id])}}"> {{ $scope_entry->resources()->count() }}</a><br/>
				  

				  
				  
				  
               </td>
               <td>
                  <a href="{{route('scope_entry-screenshots-list',['scope_id'=>$scope->id,'scope_entry_id' => $scope_entry->id])}}"> {{ $scope_entry->screenshots_count }}</a>
               </td> 
			   
			   			                  <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'critical','type'=>'nuclei'])}}"> {{ $scope_entry->outputs()->where('severity','critical')->where('type','nuclei')->count() }}</a>
				
               </td> 
			                  <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'high','type'=>'nuclei'])}}"> {{ $scope_entry->outputs()->where('severity','high')->where('type','nuclei')->count() }}</a>
				
               </td> 
               <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'medium','type'=>'nuclei'])}}"> {{ $scope_entry->outputs()->where('severity','medium')->where('type','nuclei')->count() }}</a>
				
               </td> 
			   
			   
               <td>


  <form method="POST" action="{{route('scope-entry-delete',['scope_id'=>$scope->id,'id'=>$scope_entry->id])}}">
  @method('DELETE')
			@csrf

  
                       <button class="button is-small has-background-danger-dark has-text-white-bis">
						
						<span class="icon is-small">
						  <i class="fas fa-trash-alt"></i>
						</span>
					  </button>
</form>



               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>
      {{ $scope_entries->links() }}

<hr/>


<section class="section">
		 <div class="container">
<article class="message is-danger">
  <div class="message-header">
    <p>Danger area!</p>

  </div>
  <div class="message-body">
  <form method="POST" action="{{route('scopes-delete',['id' => $scope->id])}}">
                     @method('delete')
                     @csrf
                       <button class="button is-black is-large is-fullwidth">
Delete project
					  </button>
					  </form>
  </div>
</article>

    </div>

    </section>



@include('include.footer')