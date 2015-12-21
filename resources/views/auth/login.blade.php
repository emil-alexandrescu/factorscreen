@extends('auth.layout')

@section('content')
	<div id="login-page">
	  	<div class="container">
		      <form class="form-login" role="form" method="POST" action="{{ url('/auth/login') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <h2 class="form-login-heading">sign in now</h2>
		        @if (count($errors) > 0)
					<div class="alert alert-danger">
						<ul>
							@foreach ($errors->all() as $error)
								<li>{{ $error }}</li>
							@endforeach
						</ul>
					</div>
				@endif

		        <div class="login-wrap">
					<input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" autofocus />
		            <br />
					<input type="password" class="form-control" name="password" placeholder="Password" />
		            <label class="checkbox">
						<input type="checkbox" name="remember"> Remember Me
		                <span class="pull-right">
		                    <a href="{{ url('/password/email') }}"> Forgot Password?</a>
		                </span>
		            </label>
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> SIGN IN</button>
		            <!-- <hr> -->
		            
		           <!--  <div class="login-social-link centered">
		            <p>or you can sign in via your social network</p>
		                <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
		                <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
		            </div> -->
		            <!-- <div class="registration">
		                Don't have an account yet?<br/>
		                <a class="" href="{{ url('/auth/register') }}">
		                    Create an account
		                </a>
		            </div> -->
		
		        </div>
		
		    </form>	  	
	  	
	  	</div>
	</div>

@endsection
