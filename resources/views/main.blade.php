<!DOCTYPE html>
<html lang="en">
<head>
    <title>ShrewdEye</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="/favicon.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/bulma.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/css/inter.css')}}">
    <script src="{{ asset('assets/js/jquery-3.6.0.js')}}"></script>
   <script src="{{ asset('assets/js/main.js')}}"></script>
   <script src="https://kit.fontawesome.com/487ccd8ae9.js" crossorigin="anonymous"></script>
   <meta name="title" content="Workflow automation and Bugbounty assets management | ShrewdEye" />
	<meta name="description" content="Use a set of utilities bundled into a single automated workflow to improve, simplify, and speed up resource discovery. Launch your favorite discovery tools from your browser and enjoy the reports. " />
	<meta name="author" content="shrewdeye" />
	<meta name="keywords" content="security testing,bug bunty,bug bounty hacking,pentestration testing,pentesting,bug hunting,automated penetration testing,automation, online tools" />
	<link rel="canonical" href="https://shrewdeye.app/" />

</head>
<body>
    <div class="">
                
      
      
                
      <section class="section pt-0"> 
        <nav class="navbar py-4">
          <div class="navbar-brand">
            <a class="navbar-item" href="/"><img src="/assets/images/shrewdeye-4.png" alt="" width="30"></a>
            <a class="navbar-burger" role="button" aria-label="menu" aria-expanded="false">
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
              <span aria-hidden="true"></span>
            </a>
          </div>
          <div class="navbar-menu">
            <div class="navbar-end">
      <a class="navbar-item" href="/dashboard">Dashboard</a><a class="navbar-item" href="https://github.com/zzzteph/sheye">Docs</a><a class="navbar-item" href="https://github.com/zzzteph/sheye">Github</a>
      </div>
            <div class="navbar-item"></div>
          </div>
        </nav>
        <div class="container pt-5">
          <div class="mb-5 is-vcentered columns is-multiline">
            <div class="column is-12 is-5-desktop mb-5 mr-auto">
              <h2 class="mb-6 is-size-1 is-size-3-mobile has-text-weight-bold">Automated scans</h2>
              <p class="subtitle has-text-grey mb-6">Don't waste your time on installing, running tools, and saving the results. With just a few clicks, you will get a fully automated scan chain.</p>
              <div class="buttons">
      
      </div>
            </div>
            <div class="column is-12 is-6-desktop">
              <img class="image is-fullwidth" src="/assets/images/shrewdeye-4.png" alt="">
            </div>
          </div>
          <div class="is-block-desktop is-hidden-touch has-text-centered">
            
          </div>
        </div>
      </section>
                
      <section class="section">
        <div class="container">
          <div class="columns is-multiline is-centered">
            <div class="column is-8 is-4-desktop has-text-centered" id="login">
              
              <h3 class="mb-5 is-size-4 has-text-weight-bold">Sign In</h3>
              <form method="POST" action="{{route('authentificate')}}">
                @csrf
                <div class="field">
                  <div class="control">
                    <input class="input" name="name" type="input" placeholder="Username">
                  </div>
                </div>
                <div class="field">
                  <div class="control">
                    <input class="input" name="password" type="password" placeholder="Password">
                  </div>
                </div>
                
                <button class="button is-primary mb-3 is-fullwidth is-large">Sign In</button>
              </form>
              
            </div>
          </div>
        </div>
      </section>
                
      <footer class="section">
        <div class="container">
          <div class="pb-5 is-flex is-flex-wrap-wrap is-justify-content-between is-align-items-center">
            <div class="mr-auto mb-2">
              <a class="is-inline-block" href="#">
                
              </a>
            </div>
            <div>
              <ul class="is-flex is-flex-wrap-wrap is-align-items-center is-justify-content-center">
                <li class="mr-4"><a class="button is-white" href="/login">Sign In</a></li>
                <li class="mr-4"><a class="button is-white" href="https://github.com/zzzteph/sheye">Docs</a></li>
                <li class="mr-4"><a class="button is-white" href="https://github.com/zzzteph/sheye">Github</a></li>
                <li>
              </ul>
            </div>
          </div>
        </div>
        <div class="pt-5" style="border-top: 1px solid #dee2e6;"></div>
        <div class="container">
          <div class="is-flex-tablet is-justify-content-between is-align-items-center">
		            <div>
            <a class="mr-4 is-inline-block" href="mailto:steph@appsec.study">
                          <span class="icon is-black has-text-black">
           <i class="fas fa-envelope"></i>
            </span>
            </a>
            <a class="mr-4 is-inline-block" href="https://twitter.com/w34kp455">
                         <span class="icon is-black has-text-black">
            <i class="fab fa-twitter"></i>
            </span>
            </a>
            <a class="mr-4 is-inline-block" href="https://github.com/zzzteph">
			
			
                          <span class="icon is-black has-text-black">
      <i class="fab fa-github"></i>
            </span>

            </a>
            <a class="mr-4 is-inline-block" href="https://www.buymeacoffee.com/shrewdeye">
                          <span class="icon is-black has-text-black">
        <i class="fas fa-coffee"></i>
            </span>
            </a>
          </div>
        </div>
            <div class="py-2 is-hidden-tablet"></div>
            <div class="ml-auto">

            </div>
          </div>
        </div>
      </footer>
    </div>
</body>
</html>
