@include('include.header')
    <section class="hero">
        <div class="hero-body">
            <div class="container has-text-centered">
                <div class="column is-4 is-offset-4">
                    <h3 class="title has-text-black">Sign-in</h3>
																		
												
					
                    <hr class="login-hr">
                    <p class="subtitle has-text-black">Please login to proceed.</p>
                    <div class="box">
                        <form method="POST" action="{{route('authentificate')}}">
						@csrf
                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="text" name="name" placeholder="Name" autofocus="">
                                </div>
                            </div>

                            <div class="field">
                                <div class="control">
                                    <input class="input is-large" type="password" name="password" placeholder="Your Password">
                                </div>
                            </div>
							

							  </div>
                            <button class="button is-block is-primary is-large is-fullwidth">Login <i class="fas fa-sign-in-alt"></i></button>
                        </form>

						
						
                    </div>
                </div>
            </div>
        </div>
    </section>
@include('include.footer')


