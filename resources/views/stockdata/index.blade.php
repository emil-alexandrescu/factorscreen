@extends('layout.layout')

@section('content')
<h3><i class="fa fa-dashboard"></i> Stock data</h3>

<div class="content-panel">
    <div class="adv-table" style="margin:10px;">
        <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered data-table" id="hidden-table-info">
            <thead>
	            <tr>
	                <th>No</th>
	                <th>Title</th>
	                <th>Data count</th>
	                <th>Created at</th>
	                <th style="width:50px;"></th>
	            </tr>
            </thead>
            <tbody>
            	@foreach($stockdata as $index => $sd)
	            <tr>
	            	<td>{{ $index+1 }}</td>
	            	<td>{{ $sd->title }}</td>
	            	<td>{{ count($sd->stockdata_rows) }}</td>
	            	<td>{{ date('n/j/Y', strtotime($sd->created_at)) }}</td>
	            	<td><a href="{{ url('/stockdata') . '/' . $sd->id }}" class="btn btn-sm btn-default">View</a></td>
	            </tr>
	            @endforeach
        </table>
        <div style="clear:both;"></div>
    </div>
</div>
@endsection