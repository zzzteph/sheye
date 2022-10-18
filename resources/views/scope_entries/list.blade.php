@include('include.header')

<nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">Dashboard</a></li>
    <li class="is-active"><a href="#" class="has-text-warning is-family-monospace" aria-current="page">{{$scope->name}}</a></li>
  </ul>
</nav>
<hr/>

<h1 class="title">Entries</h1>







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
               </td>
			   <td>
                  <a href="{{route('scope_entries-resources-list',['scope_id'=>$scope->id,'id' => $scope_entry->id])}}"> {{ $scope_entry->resources()->count() }}</a><br/>
				  

				  
				  
				  
               </td>
               <td>
                  <a href="{{route('scope_entry-screenshots-list',['scope_id'=>$scope->id,'scope_entry_id' => $scope_entry->id])}}"> {{ $scope_entry->screenshots_count }}</a>
               </td> 
			   
			   			                  <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'critical','type'=>'nuclei'])}}"> {{ $scope_entry->findings()->where('severity','critical')->where('type','nuclei')->count() }}</a>
				
               </td> 
			                  <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'high','type'=>'nuclei'])}}"> {{ $scope_entry->findings()->where('severity','high')->where('type','nuclei')->count() }}</a>
				
               </td> 
               <td>
                  <a href="{{route('scope-entry-findings',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'severity'=>'medium','type'=>'nuclei'])}}"> {{ $scope_entry->findings()->where('severity','medium')->where('type','nuclei')->count() }}</a>
				
               </td> 
			   
			   
               <td>


			   <div class="field is-grouped">
			   
<div class="control">
				   <form method="POST" action="{{route('scope-entry-scan',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'type'=>'nuclei'])}}">

                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						<i class="fas fa-atom"></i>
						</span>
					  </button>
                  </form>
</div>

<div class="control">
				   <form method="POST" action="{{route('scope-entry-scan',['scope_id' => $scope->id,'scope_entry_id' => $scope_entry->id,'type'=>'nmap'])}}">

                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						<i class="fas fa-network-wired"></i>
						</span>
					  </button>
                  </form>
</div>

				  </div>



               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>
      {{ $scope_entries->links() }}

@include('include.footer')