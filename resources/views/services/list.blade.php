@include('include.header')

<h1 class="title">Services</h1>


   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Port</th>
               <th>Count</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($services as $key=>$value)
            <tr>
               <td>
			   {{$key}}

               </td>
               <td>
			   {{$value}}
				  
               </td>
			   


            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>


@include('include.footer')