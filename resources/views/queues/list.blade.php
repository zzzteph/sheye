 @include('include.header')

		
 <nav class="breadcrumb" aria-label="breadcrumbs">
  <ul>
    <li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">Dashboard</a></li>
		@if(isset($scope))
	<li><a class="has-text-warning is-family-monospace" href="{{route('dashboard')}}">{{$scope->name}}</a></li>
 @endif

  </ul>
</nav>
		<hr/>
  
{{ $queues->links() }}
<div class="table-container ">
			<table class="table is-fullwidth">
			<thead>
				<tr>
				   
					<th>Type</th>
					<th>Resource</th>
					<th>Status</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
			
		   
				@foreach ($queues as $queue)
		   <tr>
		  
				<td>
				
				{{$queue->scanner_entry->name}}
				
				</td>

				<td>
				
					 @if($queue->object_type=='scope_entry')
						 
						<a href="{{route('scope_entries-resources-list',['scope_id' =>$queue->scope_id,'id' =>$queue->object_id])}}">
							{{$queue->scope_entry()->source}}
						</a>

					 @elseif($queue->object_type=='resource')
					 
					 	<a href="{{route('resources-view',['scope_id' =>$queue->scope_id,'scope_entry_id' =>$queue->resource()->scope_entry_id,'resource_id'=>$queue->object_id])}}">
							{{$queue->resource()->name}}
						</a>
					  @elseif($queue->object_type=='service')
					 
					 	<a href="{{route('resources-view',['scope_id' =>$queue->scope_id,'scope_entry_id' =>$queue->service()->scope_entry_id,'resource_id'=>$queue->service()->resource_id])}}">
							{{$queue->service()->resource->name}}
						</a>
					 
					 
					 
						@endif
				</td>
									<td>
				
					
					@if($queue->status=='todo')
						
					<span class="tag is-warning is-light">todo</span>
					@elseif($queue->status=='queued')
					
					<span class="tag is-info">queued</span>
					@elseif($queue->status=='running')
					
					<span class="tag is-primary">running</span>
					@elseif($queue->status=='done')
					
						<span class="tag is-success">done</span>
					 @endif
					 
				
				</td>
								<td>
				
					 {{$queue->created_at}}
					 
				
				</td>
				
												<td>
				
							   <form method="POST" action="{{route('queues-delete',['queue_id' => $queue->id])}}">
                     @method('delete')
                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						  <i class="fas fa-times"></i>
						</span>
					  </button>
                  </form>
					 
				
				</td>
				
				
			</tr>
			@endforeach
		</tbody>
	</table>
	</div>
{{ $queues->links() }}
	


@include('include.footer')