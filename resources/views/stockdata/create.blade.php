<style>
.sk-spinner-cube-grid {
  /*
   * Spinner positions
   * 1 2 3
   * 4 5 6
   * 7 8 9
   */ }
  .sk-spinner-cube-grid.sk-spinner {
    width: 100px;
    height: 17px;
    margin: 0 auto; }
  .sk-spinner-cube-grid .sk-cube {
    width: 17px;
    height: 17px;
   border-radius:17px;
    background-color: #4ECDC4;
    float: left;
   margin-right:3px;
    -webkit-animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out;
            animation: sk-cubeGridScaleDelay 1.3s infinite ease-in-out; }
  .sk-spinner-cube-grid .sk-cube:nth-child(1) {
    -webkit-animation-delay: 0.2s;
            animation-delay: 0.2s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(2) {
    -webkit-animation-delay: 0.3s;
            animation-delay: 0.3s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(3) {
    -webkit-animation-delay: 0.4s;
            animation-delay: 0.4s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(4) {
    -webkit-animation-delay: 0.5s;
            animation-delay: 0.5s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(5) {
    -webkit-animation-delay: 0.6s;
            animation-delay: 0.6s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(6) {
    -webkit-animation-delay: 0.7s;
            animation-delay: 0.7s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(7) {
    -webkit-animation-delay: 0.8s;
            animation-delay: 0.8s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(8) {
    -webkit-animation-delay: 0.9s;
            animation-delay: 0.9s; }
  .sk-spinner-cube-grid .sk-cube:nth-child(9) {
    -webkit-animation-delay: 1s;
            animation-delay: 1s; }
@-webkit-keyframes sk-cubeGridScaleDelay {
  0%, 70%, 100% {
    -webkit-transform: scale3D(1, 1, 1);
            transform: scale3D(1, 1, 1); }
  35% {
    -webkit-transform: scale3D(0, 0, 1);
            transform: scale3D(0, 0, 1); } }
@keyframes sk-cubeGridScaleDelay {
  0%, 70%, 100% {
    -webkit-transform: scale3D(1, 1, 1);
            transform: scale3D(1, 1, 1); }
  35% {
    -webkit-transform: scale3D(0, 0, 1);
            transform: scale3D(0, 0, 1); } }
</style>
<div class="modal fade" id="uploadStockDataModal" tabindex="-1" role="dialog" aria-labelledby="uploadStockDataModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close btn-close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="uploadStockDataModalLabel">Upload Stock Data</h4>
      </div>
      <div class="modal-body">

   			<form id="frmUploadStockData" class="form-horizontal style-form" method="post" action="{{ url('/stockdata/create') }}" enctype="multipart/form-data">

				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<div id="msg" class="form-group">
					<div class="alert alert-danger" style="display:none;"></div>
					<div class="alert alert-info">Please fill following form and click [Upload] button. It will take some time depending on the file size.</div>
					<div class="sk-spinner sk-spinner-cube-grid alert-loading" style="display:none;">
						<div class="sk-cube"></div>
						<div class="sk-cube"></div>
						<div class="sk-cube"></div>
						<div class="sk-cube"></div>
						<div class="sk-cube"></div>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-4 col-sm-4 control-label">Title</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" id="title" name="title">
					</div>
				</div>
				<div class="form-group last">
					<label class="control-label col-md-4">Stock data file(*.CSV)</label>
					<div class="col-sm-8">
						<input type="file" class="default" id="file" name="file" /><br />
						Please check the <a href="{{ asset('/data/template.csv') }}">template</a> file.
					</div>
				</div>
	        	<button type="button" class="btn btn-default btn-close pull-right" data-dismiss="modal">Close</button>
	        	<button type="submit" class="btn btn-primary btn-upload pull-right" style="margin-right:10px;">Upload</button>
	        	<div style="clear:both;"></div>
	      	</form>
      </div>
    </div>
  </div>
</div>

<script language="javascript">
$(document).ready(function() {
	var $msg = $('#msg');
	var $form = $('#frmUploadStockData');

	$form.submit(function(e) {
		e.preventDefault();
		enableButtons(false);
		showLoading();
		if($('#frmUploadStockData #title').val() == "") {
			enableButtons(true);
			showError('Please enter title.');
			return;
		}
		else if($('#frmUploadStockData #file').val() == "") {
			enableButtons(true);
			showError('Please select stock data file.');
			return;
		}

		var formData = new FormData($form[0]);

	    $.ajax({
	        url: $form.attr('action'),
	        type: 'POST',
	        data: formData,
	        async: false,
	        success: function (data) {
	        	var json = JSON.parse(data);
	        	if(json.status == 1) {
	        		showMsg(json.message);
	        		document.location = '{{ url("stockdata/") }}/' + json.stockdata.id;
	        	}
	        	else {
	        		showError(json.message);
	        		enableButtons(true);
	        	}
	        },
	        cache: false,
	        contentType: false,
	        processData: false
	    });
	});
});
function enableButtons(enable) {
	$('#uploadStockDataModal .btn-close').attr('disabled', !enable);
	$('#uploadStockDataModal .btn-upload').attr('disabled', !enable);
}
function showLoading() {
	$('#msg .alert-info').hide();
	$('#msg .alert-loading').show();
	$('#msg .alert-danger').hide();
}
function showError(msg) {
	$('#msg .alert-info').hide();
	$('#msg .alert-loading').hide();
	$('#msg .alert-danger').show();
	$('#msg .alert-danger').html(msg);
}
function showMsg(msg) {
	$('#msg .alert-danger').hide();
	$('#msg .alert-loading').hide();
	$('#msg .alert-info').show();
	$('#msg .alert-info').html(msg);
}
</script>