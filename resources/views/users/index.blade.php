@extends('layout.layout')

@section('content')
<h3><i class="fa fa-user"></i> Users
<a href="{{ route('users.create') }}" class="btn btn-xs btn-info pull-right"><i class="fa fa-plus" style="font-size:14px;"></i>Invite new user</a></h3>


<div class="content-panel">
    <div class="adv-table" style="margin:10px;">
        <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered data-table" id="hidden-table-info">
            <thead>
	            <tr>
	                <th>No</th>
	                <th>Name</th>
	                <th>Email</th>
	                <th>Type</th>
	                <th>Gross logins</th>
	                <th>Created at</th>
	                <th style="width:90px;"></th>
	            </tr>
            </thead>
            <tbody>
            	@foreach($users as $index => $user)
	            <tr>
	            	<td>{{ $index+1 }}</td>
	            	<td>{{ $user->name }}</td>
	            	<td>{{ $user->email }}</td>
	            	<td>{{ $user->type == 1 ? 'Admin' : 'User' }}</td>
	            	<td>{{ count($user->usage) }}</td>
	            	<td>{{ date('n/j/Y', strtotime($user->created_at)) }}</td>
	            	<td>
	            		<a href="{{ url('users') . '/' . $user->id . '/edit' }}" class="btn btn-sm btn-primary" style="width:80px;">Edit</a><br />
	            		<a href="{{ url('users') . '/' . $user->id . '/login' }}" class="btn btn-sm btn-default" style="width:80px;margin-top:5px;">Login in as</a><br />
	            		{!! Form::open(['method' => 'DELETE', 'route' => ['users.destroy', $user->id]]) !!}
	            			{!! Form::submit('Delete', ['class' => 'btn btn-danger btn-confirm', 'style' => 'margin-top:5px;width:80px;']) !!}
	            		{!! Form::close() !!}
	            	</td>
	            </tr>
	            @endforeach
        </table>
        <div style="clear:both;"></div>
    </div>
</div>
@endsection