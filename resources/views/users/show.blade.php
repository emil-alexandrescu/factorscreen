@extends('layout.layout')

@section('content')
<h3><i class="fa fa-user"></i> Users</h3>
<div class="col-lg-12">
	<div class="form-panel">
		<h4 class="mb"><i class="fa fa-user"></i> {{ $user->name }}</h4>
		<form class="form-horizontal style-form" method="post">
			<input type="hidden" name="_token" value="{{ csrf_token() }}">
			<div class="form-group">
				<label class="col-sm-2 control-label">Name</label>
				<div class="col-sm-12 col-md-4">
					{{ $user->name }}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-12 col-md-4">
					{{ $user->email }}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Type</label>
				<div class="col-sm-12 col-md-4">
					{{ $user->type == 1 ? 'Admin' : 'User' }}
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">Gross logins</label>
				<div class="col-sm-12 col-md-4">
					{{ count($user->usage) }}
				</div>
			</div>
		</form>
	</div>
</div>
@endsection