@extends('layout.layout')

@section('content')
<h3><i class="fa fa-user"></i> Usages</h3>
<div class="content-panel">
	<center>
	<div class="white-panel search-panel pn" style="width:600px;">
	<form method="get">
		<div class="white-header" style="margin-bottom:0px;">
			<h5>Filter options</h5>
		</div>
		<div class="row">
			<div class="form-group">
				<div class="col-sm-1"></div>
				<div class="col-sm-8">
					<div class="input-group input-large date-range" data-date="01/01/2014" data-date-format="yyyy-mm-dd">
                      <input type="text" class="form-control date-from" name="start" value="{{$start}}" />
                      <span class="input-group-addon">To</span>
                      <input type="text" class="form-control date-to" name="end" value="{{$end}}" />
                  </div>
				</div>
				<div class="col-sm-2">
					<button class="btn btn-primary pull-right" type="submit">Filter</button>
				</div>
				<div class="col-sm-1"></div>
			</div>
		</div>
	</form>
	</div>
	<script language="javascript">
		var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('.date-from').datepicker({
        	format: 'yyyy-mm-dd',
            onRender: function(date) {
                return date.valueOf() < now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
                if (ev.date.valueOf() > checkout.date.valueOf()) {
                    var newDate = new Date(ev.date)
                    newDate.setDate(newDate.getDate() + 1);
                    checkout.setValue(newDate);
                }
                checkin.hide();
                $('.date-to')[0].focus();
            }).data('datepicker');
        var checkout = $('.date-to').datepicker({
        	format: 'yyyy-mm-dd',
            onRender: function(date) {
                return date.valueOf() <= checkin.date.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
                checkout.hide();
            }).data('datepicker');
	</script>

    <div class="adv-table" style="margin:10px;">
        <table cellpadding="0" cellspacing="0" border="0" class="display table table-bordered data-table" id="hidden-table-info">
            <thead>
	            <tr>
	                <th>No</th>
	                <th>User</th>
	                <th>Logins</th>
	            </tr>
            </thead>
            <tbody>
            	@foreach($usages as $index => $usage)
				@if ($user = App\User::find($usage->user_id))
	            <tr>
	            	<td>{{ $index+1 }}</td>
	            	<td>{{ $user->name }}</td>
	            	<td>{{ $usage->usage }}</td>
	            </tr>
				@endif
	            @endforeach
        </table>
        <div style="clear:both;"></div>
    </div>
</div>
@endsection
