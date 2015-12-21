@extends('auth.layout')

@section('content')
	<div id="login-page">
	  	<div class="container">
		      <form class="form-login" role="form" method="POST" action="{{ url('/password/email') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <h2 class="form-login-heading">Reset Password</h2>
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
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> Send Password Reset Link</button>
		            <br />
		            <a class="btn btn-theme btn-block" href="{{ url('/auth/login') }}"><i class="fa fa-arrow-left"></i> BACK</a>
		            <hr>
		            
		            <!--  <div class="login-social-link centered">
		            <p>or you can sign in via your social network</p>
		                <button class="btn btn-facebook" type="submit"><i class="fa fa-facebook"></i> Facebook</button>
		                <button class="btn btn-twitter" type="submit"><i class="fa fa-twitter"></i> Twitter</button>
		            </div> -->
		
		        </div>
		
		    </form>	  	
	  	
	  	</div>
	</div>
@endsection