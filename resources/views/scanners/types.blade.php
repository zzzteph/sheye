@include('include.header')

<h1 class="title">Available scanners types</h1>


   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Class</th>
               <th>Type</th>
			   <th>Arguments</th>
			   <th>Description</th>
			   <th>Available</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($scanners as $scanner)
            <tr>
               <td>
                    {{ $scanner->class }}

               </td>
               <td>
                   {{ $scanner->type }}
               </td>
			    <td>
                   @if($scanner->has_arguments)
					   
				   	<span class="icon">
					<i class="fas fa-check-circle"></i>
					</span>
				   @else
					   
				   	   	<span class="icon">
					<i class="fas fa-times"></i>
					</span>
				   @endif
               </td>
			    <td>
                   {{ $scanner->type }}
               </td>			    
			   <td>
                   {{ $scanner->type }}
               </td>
			   
          
            </tr>
            @endforeach
         </tbody>
      </table>
	  </div>


@include('include.footer')