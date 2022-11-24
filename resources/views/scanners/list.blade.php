@include('include.header')

<h1 class="title">Scanners</h1>


   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Name</th>
               <th>Type</th>
			   <th>Arguments</th>
			   <th>Class</th>
			   <th></th>
            </tr>
         </thead>
         <tbody>
            @foreach ($scanners as $scanner)
            <tr>
               <td>
			      <a href="{{route('scanners-view',['id' => $scanner->id])}}"> {{ $scanner->name }}</a>
                    

               </td>
               <td>
                   {{ $scanner->scanner->type }}
               </td>
			    <td>
					{{ $scanner->arguments }}
               </td>
			    <td>
                   <code>{{ $scanner->scanner->class }}</code>
               </td>			    
			   <td>
                  				   <form method="POST" action="{{route('scanners-delete',['id' => $scanner->id])}}">
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


@include('include.footer')