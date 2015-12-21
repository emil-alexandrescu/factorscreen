@extends('layout.layout')

@section('content')
<h3><i class="fa fa-user"></i> Invite new User</h3>
<div class="col-lg-12">
	<div class="form-panel">
		<h4 class="mb"><i class="fa fa-user"></i> New user info</h4>
		<form action="{{ route('users.store') }}" class="form-horizontal style-form" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-12 col-md-4">
					<input type="text" class="form-control" name="name" value="{{ Input::old('name') }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-12 col-md-4">
					<input type="text" class="form-control" name="email" value="{{ Input::old('email') }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-12 col-md-4">
					<input type="password" class="form-control" name="password" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Confirm password</label>
				<div class="col-sm-12 col-md-4">
					<input type="password" class="form-control" name="password_confirmation" value="">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label"></label>
				<div class="col-sm-12 col-md-4">
					<button type="submit" class="btn btn-primary">Save</button>
					<a href="{{ route('users.index') }}" class="btn btn-danger">Back</a>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection