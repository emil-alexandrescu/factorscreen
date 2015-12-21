@extends('auth.layout')

@section('content')
	<div id="login-page">
	  	<div class="container">
		      <form class="form-login" role="form" method="POST" action="{{ url('/auth/register') }}">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
		        <h2 class="form-login-heading">register now</h2>
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
					<input type="text" class="form-control" name="name" placeholder="Name" value="{{ old('name') }}" autofocus />
		            <br />
					<input type="email" class="form-control" name="email" placeholder="Email" value="{{ old('email') }}" autofocus />
		            <br />
					<input type="password" class="form-control" name="password" placeholder="Password" />
		            <br />
					<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm password" />
		            <br />
		            <button class="btn btn-theme btn-block" type="submit"><i class="fa fa-lock"></i> REGISTER</button>
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
