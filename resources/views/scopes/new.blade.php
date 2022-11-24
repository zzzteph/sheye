@include('include.header')

<h1 class="title">Create new project</h1>			
<form method="POST" action="{{route('scopes-create')}}">
			@csrf
	<div class="field">
	  <label class="label">Name</label>
	  <div class="control">
			<input class="input" type="text" name="name"  minlength="4" placeholder="Example.com scan">
	  </div>
	</div>
	<div class="field">
	  <label class="label">Domain list</label>
	  <div class="control">
			 <textarea class="textarea" placeholder="*.example.com" name="domains"></textarea>
	  </div>
	    <p class="help">Each domain should start from new line.</p>

	</div>
<div class="field">
    <div class="control">
      <div class="select">
        <select name="template">
            @foreach ($templates as $template)
		
                <option value="{{$template->id}}">{{$template->name}}</option>
            @endforeach
        </select>
      </div>
    </div>
	 <p class="help">Default workflow.</p>
 </div>


	<div class="field">
	  <div class="control">
		<button class="button is-black">Create</button>
	  </div>
	</div>

</form>

@include('include.footer')