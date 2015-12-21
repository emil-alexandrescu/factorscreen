@extends('layout.layout')

@section('content')
<h3><i class="fa fa-gear"></i> Settings</h3>
<div class="col-lg-12">
	<div class="form-panel">
		<h4 class="mb"><i class="fa fa-gear"></i> Settings</h4>
		<form class="form-horizontal style-form" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-12 col-md-4">
					<input type="text" class="form-control" name="name" value="{{ $user->name }}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 col-sm-2 control-label">Password</label>
				<div class="col-sm-12 col-md-4">
					<input type="password" class="form-control" name="password">
					<span class="help-block">Please leave this blank if you don't want to change the password.</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 col-sm-2 control-label">Re enter password</label>
				<div class="col-sm-12 col-md-4">
					<input type="password" class="form-control" name="password_confirmation">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 col-sm-2"></label>
				<div class="col-sm-12 col-md-4">
					<button type="submit" class="btn btn-theme">Save</button>
				</div>
			</div>
		</form>
	</div>
</div>
@endsection