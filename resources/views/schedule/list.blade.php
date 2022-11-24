@include('include.header')


<h1 class="title">New scheduler</h1>
<form method="POST" action="{{route('schedule-insert')}}">
			@csrf


		 <div class="field">
		 <div class="control">
			<div class="select is-fullwidth">
			  <select name="scope" placeholder="Your email">
			  <option value="" disabled selected>Select your project</option>
			 @foreach ($scopes as $scope)
			 
			 <option value="{{$scope->id}}">{{$scope->name}}</option>
			 
			 @endforeach
				
			  </select>
			</div>
			</div>
		  </div>
		  <div class="field">
		  <div class="control">
			<div class="select is-fullwidth">
			  <select name="frequency" >
			  <option value="" disabled selected>Select frequency</option>
				<option value="daily">daily</option>
				<option value="weekly">weekly</option>
				<option value="monthly">monthly</option>
				<option value="quarterly">quarterly</option>
				

			  </select>
			</div>
			</div>
		  </div>
		  
		  
		  <div class="field">
		  <div class="control">
			<div class="select is-fullwidth">
			  <select name="template" >
			  <option value="" disabled selected>Select template</option>
			@foreach ($templates as $template)
			 
			 <option value="{{$template->id}}">{{$template->name}}</option>
			 
			 @endforeach
				

			  </select>
			</div>
			</div>
		  </div>
		  
		  
		  
		  
		  
		  
		  <div class="field">
		  <div class="control">
			<button class="button is-black">Create</button>
			</div>
		</div>
		  




</form>
<hr/>


<h5 class="title is-5">Tasks list</h5>
   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Projects</th>
               <th>Time</th>
			   <th>Template</th>
			   <th></th>
			   <th></th>  
            </tr>
         </thead>
         <tbody>
            @foreach ($schedulers as $schedule)
			@if($schedule->scope==null)
				 @continue
			 @endif
            <tr>
               <td>
                   <a href="{{route('scope-entry-list',['scope_id' => $schedule->scope->id])}}"> {{ $schedule->scope->name }}</a>
               </td>
               <td>
                 {{ $schedule->frequency }}
               </td>

               <td>
                 {{ $schedule->template->name }}
               </td>


               <td>

   <form method="POST" action="{{route('schedule-delete',['schedule_id' => $schedule->id])}}">
                     @method('delete')
				 <div class="control">
				
                     @csrf
                       <button class="button is-black is-small">
						
						<span class="icon is-small">
						  <i class="fas fa-times"></i>
						</span>
					  </button>
                  
</div>
</form>
               </td>
            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>
@include('include.footer')