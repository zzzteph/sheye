@include('include.header')
<h1 class="title">Add new template</h1>


	<div class="container" id="app">


  <form method="POST" action="{{route('templates-update',['id'=>$template->id])}}">
  @csrf
  @method('put')
    <div class="field">
  <label class="label">Name *</label>
  <div class="control">
    <input class="input" name="name" type="text" placeholder="Text input" required value="{{$template->name}}">
  </div>
</div>
  <hr/>
  <h2 class="subtitle">Discovery Step</h2>
  
    <div class="field has-addons" v-for="(input, index) in discovery_templates" :key="`discovery_templates-${index}`">
    <div class="control">
      <div class="select">
        <select name="discovery_templates[]"  v-model="input.template_id">
        <option disabled selected value> -- select an option -- </option>
            @foreach ($scanners as $scanner)
				@if($scanner->scanner->type=="discovery")
				
                <option value="{{$scanner->id}}">{{$scanner->name}}</option>
				@endif
            @endforeach
        </select>
      </div>
    </div>
	<div class="control">
  <a class="button is-danger" @click="removeDiscovery(index)">Remove</a>
</div>

  


</div>
<div class="field">
  <div class="control">
      <a class="button" @click="addDiscovery()">Add</a>
	    </div>
</div>

  <hr/>

  <h2 class="subtitle">Resource Step</h2>
  
    <div class="field has-addons" v-for="(input, index) in resource_templates" :key="`resource_templates-${index}`">
    <div class="control">
      <div class="select">
        <select name="resource_templates[]"  v-model="input.template_id">
        <option disabled selected value> -- select an option -- </option>
            @foreach ($scanners as $scanner)
			@if($scanner->scanner->type=="resource")
                <option value="{{$scanner->id}}">{{$scanner->name}}</option>
			@endif
            @endforeach
        </select>
      </div>
    </div>
	<div class="control">
  <a class="button is-danger" @click="removeResource(index)">Remove</a>
</div>

  


</div>
<div class="field">
  <div class="control">
      <a class="button" @click="addResource()">Add</a>
	    </div>
</div>


  <hr/>

  <h2 class="subtitle">Service Step</h2>
  
    <div class="field has-addons" v-for="(input, index) in service_templates" :key="`service_templates-${index}`">
    <div class="control">
      <div class="select">
        <select name="service_templates[]"  v-model="input.template_id">
        <option disabled selected value> -- select an option -- </option>
            @foreach ($scanners as $scanner)
			@if($scanner->scanner->type=="service")
                <option value="{{$scanner->id}}">{{$scanner->name}}</option>
			@endif
            @endforeach
        </select>
      </div>
    </div>
	<div class="control">
  <a class="button is-danger" @click="removeService(index)">Remove</a>
</div>

  


</div>
<div class="field">
  <div class="control">
      <a class="button" @click="addService()">Add</a>
	    </div>
</div>




<div class="field">
  <div class="control">
    <button class="button is-link">Submit</button>
  </div>
</div>


</form>
</div>


<script>

var app = new Vue({
  el: '#app',
  data: {
	discovery_templates: [
	
		  	@foreach ($template->template_entries as $entry)
		@if($entry->scanner_entry->scanner->type=="discovery")
		{template_id:"{{$entry->scanner_entry->id}}"},
@endif
	@endforeach
	
	
	],
	resource_templates: [
	
			  	@foreach ($template->template_entries as $entry)
		@if($entry->scanner_entry->scanner->type=="resource")
		{template_id:"{{$entry->scanner_entry->id}}"},
@endif
	@endforeach
	
	
	
	],
	service_templates: [
	
	
	
			  	@foreach ($template->template_entries as $entry)
		@if($entry->scanner_entry->scanner->type=="service")
		{template_id:"{{$entry->scanner_entry->id}}"},
@endif
	@endforeach
	
	
	
	
	],
  },
  methods: {

	addDiscovery() {
      this.discovery_templates.push({ template_id: 0 });
    },
    removeDiscovery(index) {
       this.discovery_templates.splice(index, 1);
   
   },
   addResource() {
      this.resource_templates.push({ template_id: 0 });
    },
    removeResource(index) {
       this.resource_templates.splice(index, 1);
   
   },
   addService() {
      this.service_templates.push({ template_id: 0 });
    },
    removeService(index) {
       this.service_templates.splice(index, 1);
   
   }
   
   
   
   
   
   
   
   
    }
  
  
})
</script>
</script>
@include('include.footer')