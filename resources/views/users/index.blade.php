@include('include.header')


<h1 class="title">Packages</h1>
<div class="content">
The project has always been and remains free. Each user gets one place in the queue to perform a particular task. Paid packages do not add or open any hidden additional functionality. You can get additional places in the queue by purchasing a paid package. This will allow you to run multiple tasks simultaneously and significantly speed up your scanning.
</div>



<table class="table is-fullwidth">
  <thead>
    <tr>
      <th>Fast line</th>
      <th>Days left</th>
      <th>Add</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1X</td>
      <td>{{$user->package_timeout(1)}}</td>
      <td>                  <form method="POST" action="{{route('checkout-1')}}">
	  @csrf
                           <button type="submit" class="button is-success is-light is-fullwidth">
						   <span class="icon-text">
						     <span>2</span>
							  <span class="icon">
							  <i class="fas fa-euro-sign"></i>
							  </span>

						</span> </button>
						</form>
	 </td>
	  
    </tr>
	
	
	
	    <tr>
      <td>3X</td>
      <td>{{$user->package_timeout(3)}}</td>
      <td>                  <form method="POST" action="{{route('checkout-3')}}">
	  @csrf
                           <button type="submit" class="button is-success is-light is-fullwidth">
						   <span class="icon-text">
						     <span>5</span>
							  <span class="icon">
							  <i class="fas fa-euro-sign"></i>
							  </span>

						</span> </button>
						</form>
	 </td>
	  
    </tr>
	
	
	
	    <tr>
      <td>5X</td>
     <td>{{$user->package_timeout(5)}}</td>
      <td>                  <form method="POST" action="{{route('checkout-5')}}">
	  @csrf
                           <button type="submit" class="button is-success is-light is-fullwidth">
						   <span class="icon-text">
						     <span>7</span>
							  <span class="icon">
							  <i class="fas fa-euro-sign"></i>
							  </span>

						</span> </button>
						</form>
	 </td>
	  
    </tr>
	
	
	
  </tbody>
</table>





@include('include.footer')