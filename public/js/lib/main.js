$(document).ready(function() {
	$('table.data-table').dataTable();


	$('.date').datepicker();

	$('.btn-confirm').click(function(e) {
		if(!confirm("Do you want to proceed?")) {
			e.preventDefault();
		}
	});
});