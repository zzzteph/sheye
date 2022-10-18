@include('include.header')

<h1 class="title">Dashboard</h1>

{{ $scopes->links() }}
   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Projects</th>
               <th>Resources</th>
			   <th><span class="icon"><i class="fas fa-image"></i></span></th>

			   <th><span class="icon"><i class="fas fa-exclamation-triangle"></i></span></th>
			    <th><span class="icon"><i class="fas fa-exclamation-circle"></i></span></th>
			   <th><span class="icon"><i class="fas fa-exclamation"></i></span></th>
			   
			   @if((new \Jenssegers\Agent\Agent())->isDesktop())
			   <th>Jobs</th>  
			   @endif
				<th></th>
               <th></th>
            </tr>
         </thead>
         <tbody>
            @foreach ($scopes as $scope)
            <tr>
               <td>
                   <a href="{{route('scope-entry-list',['scope_id' => $scope->id])}}"> {{ $scope->name }}</a>

               </td>
               <td>
                  <a href="{{route('resources-list',['id' => $scope->id])}}"> {{ $scope->resources()->count() }}</a>
				  
               </td>
			   


			   
               <td>
                  <a href="{{route('scope-screenshots-list',['scope_id' => $scope->id])}}"> {{ $scope->screenshots_count }}</a>
               </td> 

			                  <td>
                  <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'critical','type'=>'nuclei'])}}"> {{ $scope->findings()->where('severity','critical')->where('type','nuclei')->count() }}</a>
				
               </td> 
			                  <td>
                  <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'high','type'=>'nuclei'])}}"> {{ $scope->findings()->where('severity','high')->where('type','nuclei')->count() }}</a>
				
               </td> 
               <td>
                  <a href="{{route('scope-findings',['scope_id' => $scope->id,'severity'=>'medium','type'=>'nuclei'])}}"> {{ $scope->findings()->where('severity','medium')->where('type','nuclei')->count() }}</a>
				
               </td> 

			  <td>
@if((new \Jenssegers\Agent\Agent())->isDesktop())			  
			  			  	@if($scope->queues()->where('status', 'running')->count()>0)  
								  <a class="is-hidden-mobile" href="{{route('scopes-queues-list',['scope_id' => $scope->id,'type'=>'running'])}}">
					  <span class="icon">
					 <i class="fas fa-cog fa-spin"></i>
					</span>
				</a>
				@endif

			  <a href="{{route('scopes-queues-list',['scope_id' => $scope->id,'type'=>'done'])}}">{{ $scope->queues()->where('status', 'done')->count() }}</a> 
				/
				<a href="{{route('scopes-queues-list',['scope_id' => $scope->id,'type'=>'todo'])}}">{{ $scope->queues()->where('status', 'todo')->count() }}</a>

				 
@else
	

<progress class="progress" value="{{ $scope->queues()->where('status', 'done')->count() }}" max="100">15%</progress>



@endif




               </td> 



<td>
			   <div class="field is-grouped">
			   
<div class="control">
				   <form method="POST" action="{{route('scope-scan',['scope_id' => $scope->id,'type'=>'nuclei'])}}">

                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						<i class="fas fa-atom"></i>
						</span>
					  </button>
                  </form>
</div>

<div class="control">
				   <form method="POST" action="{{route('scope-scan',['scope_id' => $scope->id,'type'=>'nmap'])}}">

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

               <td>
			   <div class="field is-grouped">

			   
			   <div class="control">
			    <a href="{{route('scopes-edit',['id' => $scope->id])}}">
			   <button class="button  is-small is-black">
					<span class="icon">
					<i class="fas fa-edit"></i>
					</span>
					</button>
				 </a>
				 </div>
				 <div class="control">
				   <form method="POST" action="{{route('scopes-delete',['id' => $scope->id])}}">
                     @method('delete')
                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						  <i class="fas fa-times"></i>
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
      {{ $scopes->links() }}

@include('include.footer')