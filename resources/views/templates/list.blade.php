@include('include.header')

<h1 class="title">Templates</h1>


   <div class="table-container ">
      <table class="table is-fullwidth ">
         <thead>
            <tr>
               <th>Name</th>
			   <th></th>
            </tr>
         </thead>
         <tbody>
            @foreach ($templates as $template)
            <tr>
               <td>
			      <a href="{{route('templates-view',['id' => $template->id])}}"> {{ $template->name }}</a>
                    

               </td>		    
			   <td>
                  				   <form method="POST" action="{{route('templates-delete',['id' => $template->id])}}">
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