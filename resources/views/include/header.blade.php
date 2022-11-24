<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scanner</title>
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/bulma.css')}}">
    <script src="{{ asset('assets/js/jquery-3.6.0.js')}}"></script>
	<script src="{{ asset('assets/js/vue.js')}}"></script>
	<script src="{{ asset('assets/js/axios.min.js')}}"></script>
	<script src="{{ asset('assets/js/selectize.min.js')}}"></script>
    <script src="{{ asset('assets/css/selectize.min.css')}}"></script>
	<script src="https://kit.fontawesome.com/487ccd8ae9.js" crossorigin="anonymous"></script>


	    <style type="text/css" media="screen">
      body {
        display: flex;
        min-height: 100vh;
        flex-direction: column;
      }
      #wrapper {
        flex: 1;
      }
    </style>
  </head>
  <body>



    <section class="section pb-1 is-family-monospace">
    <div class="container"  id="header">
  <nav class="navbar" role="navigation" aria-label="main navigation">
  <div class="navbar-brand">
     <a class="navbar-item" href="/">
      <img src="/logo.svg" width="32" height="32">
    </a>

    <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>

   <div id="navbarBasicExample" class="navbar-menu is-size-5 is-family-monospace">
    <div class="navbar-start">


        <a class="navbar-item" href="{{route('dashboard')}}">
          Dashboard
        </a>
		
		      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Online tools
        </a>

        <div class="navbar-dropdown">
				  <a class="navbar-item" href="#">
            <strong>Standalone</strong>
          </a>
          <a class="navbar-item" href="{{route('commands-list',['type'=>'amass'])}}">
            Amass
          </a>
          <a class="navbar-item" href="{{route('commands-list',['type'=>'subfinder'])}}">
            Subfinder
          </a>
          <a class="navbar-item" href="{{route('commands-list',['type'=>'assetfinder'])}}">
            Assetfinder
          </a>
		  <a class="navbar-item" href="{{route('commands-list',['type'=>'gau'])}}">
            GAU
          </a>
		  <a class="navbar-item" href="{{route('commands-list',['type'=>'dnsb'])}}">
           DNSX
          </a>
		  		  <a class="navbar-item" href="{{route('commands-list',['type'=>'nuclei'])}}">
           Nuclei
          </a>
		  
		  
		  		  		  <a class="navbar-item" href="{{route('commands-list',['type'=>'dirsearch'])}}">
           Dirsearch
          </a>
		  		  		  		  <a class="navbar-item" href="{{route('commands-list',['type'=>'nmap'])}}">
           Nmap 1000 ports
          </a>
        </div>
      </div>
		

		        <a class="navbar-item" href="{{route('schedule-list')}}">
          Monitor
        </a>
		
		<div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Templates
        </a>

        <div class="navbar-dropdown">
          <a class="navbar-item" href="{{route('templates-list')}}">
            List
          </a>
          <a class="navbar-item" href="{{route('templates-new')}}">
            Add
          </a>
        </div>
      </div>






		      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          Scanners
        </a>

        <div class="navbar-dropdown">

          <a class="navbar-item" href="{{route('scanners-types')}}">
            Types
          </a>
          <a class="navbar-item" href="{{route('scanners-list')}}">
            List
          </a>
          <a class="navbar-item" href="{{route('scanners-new')}}">
            Add
          </a>

        </div>
      </div>












		
    </div>

    <div class="navbar-end is-size-5">
      <div class="navbar-item">
	  
	  
	  
        <div class="buttons is-size-5">
		@if(Auth::check())


  <a class="button is-black" href="{{route('scopes-add-new-template')}}">
          <span class="is-size-5 "> New project</span>
	</a>

		  
				   <form method="POST" action="{{route('logout')}}">
                     @method('delete')
                     @csrf
                       <button class="button has-background-danger-dark has-text-white-bis">
						
						<span class="icon is-small">
						  <i class="fas fa-sign-out-alt"></i>
						</span>
					  </button>
                  </form>
			@endif 
        </div>

		



      </div>
    </div>
  </div>
</nav>


</div>
</section>
  <section class="section is-family-monospace">
<div class="container">

	@if ($errors->any() || Session::has('success'))

	 <div class="container is-size-5">
@if ($errors->any())
							<article class="message is-danger">
  <div class="message-header">
    <p>Error</p>
  </div>
  <div class="message-body has-text-left">
   <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
  </div>
</article>
<br/>
@endif
@if (Session::has('success'))

							<article class="message is-success">
  <div class="message-header">

  </div>
  <div class="message-body has-text-left">
   <ul>
<li>{{ Session::get('success') }}</li>
        </ul>
  </div>
</article>



@endif


 </div>

@endif