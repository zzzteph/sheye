@include('include.header')
<h1 class="title">Edit</h1>

	<div class="container" id="app">


  <form method="POST" action="{{route('scanners-update',['id' => $scan_entry->id])}}">
  @csrf
   @method('put')
    <div class="field">
  <label class="label">Name *</label>
  <div class="control">
    <input class="input" name="name" type="text" placeholder="Text input" required value="{{$scan_entry->name}}">
  </div>
</div>
  
  <div class="field">
    <label class="label">Scanner type *</label>
    <div class="control">
      <div class="select">
        <select name="template_type" @change="onChange($event)" >
		
		@foreach ($scanners as $scanner)
		@if($scanner->id==$scan_entry->scanner->id)
		     <option value="{{$scanner->id}}" selected>{{$scanner->class}}</option>
		@else
			
		<option value="{{$scanner->id}}">{{$scanner->class}}</option>
		@endif
		
		@endforeach

        </select>
      </div>
    </div>
  </div>
 <template v-if="type !== null">
  <div class="field">
    <label class="label">Type</label>
    <div class="control">
  <input class="input" name="type" type="text" placeholder="Text input" :value="type.type" disabled>

    </div>
  </div>
  
    <div class="field">
    <label class="label">Description</label>
    <div class="control">
  <textarea class="input" type="text" placeholder="Text input" :value="type.description" disabled>
</textarea>
    </div>
  </div>
  
  
  
</template>
 <template v-if="type !== null && type.has_args=='1'">
  <div class="field">
    <label class="label">Optional arguments</label>
    <div class="control">
  <input class="input" name="arguments" type="text" placeholder="Text input" value="{{$scan_entry->arguments}}">

    </div>
  </div>
</template>


<div class="field">
  <div class="control">
    <button class="button is-link"  :disabled="type == null">Submit</button>
  </div>
</div>


</form>
</div>

<script>
var app = new Vue({
  el: '#app',
  data: {
	  
	  	@foreach ($scanners as $scanner)
		@if($scanner->id==$scan_entry->scanner->id)
	type:{ id: "{{$scanner->id}}",has_args:"{{$scanner->has_arguments}}",name:"{{$scanner->class}}",type:"{{$scanner->type}}",description:"{{$scanner->description}}" },
@endif
	@endforeach
	  
	
	types: [
	@foreach ($scanners as $scanner)
	{ id: "{{$scanner->id}}",has_args:"{{$scanner->has_arguments}}",name:"{{$scanner->class}}",type:"{{$scanner->type}}",description:"{{$scanner->description}}" },
	@endforeach
	],
  },

  methods: {

    onChange(event) {

		
				var arrayLength = this.types.length;
				for (var i = 0; i < arrayLength; i++) {

					if(this.types[i].id==event.target.value)
					  {
						  this.type=this.types[i];
						  break;
					  }
				}

			
        },
    }
})
</script>
@include('include.footer')