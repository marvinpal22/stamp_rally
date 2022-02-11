@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="row">
		<div class="col">
			<div class="card" style="min-width: 25rem;">
				<div class="card-header text-center ">
				  <strong class="mx-auto">スタンプラリースケジュール</strong>
				</div>
				<div class="card-body">
					<form id="stampForm" sched-type="scan">
						<div class="form-group">
							<div class="row mx-auto">
								<div class="col text-center">
									<label for="start_date"><strong>開始日</strong></label>
									<input type="text" data-toggle="datepicker" name="start_date" id="scan_start_date" readonly placeholder="Pick a date" autocomplete="off" class="form-control docs-date text-center">
								</div>
								<div class="col text-center">
									<label  for="start_date"><strong>終了日</strong></label>
									<input type="text" data-toggle="datepicker" name="end_date" id="scan_end_date" readonly placeholder="Pick a date" autocomplete="off" class="form-control docs-date text-center">
								</div>
							</div>
							<div class="form-group mt-4 text-center">
								<button type="submit" class="btn btn-primary mx-auto btn__Set">保存</button>
							</div>
						</div>
					</form>
				</div>
			  </div>
		</div>
		<div class="col">
			<div class="card" style="min-width: 25rem;">
				<div class="card-header text-center">
				  <strong>提出スケジュール</strong>
				</div>
				<div class="card-body">
					<form id="submitForm" sched-type="submit">
						<div class="form-group">
							<div class="row mx-auto">
								<div class="col text-center">
									<label for="start_date"><strong>開始日</strong></label>
									<input type="text" data-toggle="datepicker" name="start_date" id="submit_start_date" readonly placeholder="Pick a date" autocomplete="off" class="form-control docs-date text-center">
								</div>
								<div class="col text-center">
									<label  for="start_date"><strong>終了日</strong></label>
									<input type="text" data-toggle="datepicker" name="end_date" placeholder="Pick a date" readonly id="submit_end_date" autocomplete="off" class="form-control docs-date text-center">
								</div>
							</div>
							<div class="form-group mt-4 text-center">
								<button type="submit" class="btn btn-primary mx-auto btn__Set">保存</button>
							</div>
						</div>
					</form>
				</div>
			  </div>
		</div>
	</div>
</div>
<script>
	$( "#schedule").addClass( "btn-success rounded");
	var authToken = "Bearer " + localStorage.getItem("user_token");

	$('document').ready(function() {
		getSched('scan');
		getSched('submit');

		$('input[data-toggle="datepicker"]').datepicker({
			format: 'yyyy-mm-dd',
			language: 'ja-JP',
			autoHide: true,
		});

		$( "form" ).submit(function( event ) {
			event.preventDefault();
			var $form = $( this ),
			type = $form.attr('sched-type');
			setSched(formDataFiller(),type);
		});

		$('input[data-toggle="datepicker"]').datepicker({
			format: 'yyyy-mm-dd',
			language: 'ja-JP',
			autoPick: true,
			autoHide: true,
		});
	});

	var setSched = (data,type) => {
		$.ajax({
            url: `/stamp_rally/api/schedule/${type}?_method=PATCH`,
			type: "post",
			data: data,
			processData: false,
			contentType: false,
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				toastr.success('スケジュールが保存されました。.','',{timeOut: 2000});
            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}

				var errors = requestObject.responseJSON.errors;
				//display errors
				showToast(errors);
            }
        });
	}

	var getSched = type => {

		$.ajax({
			url: `/stamp_rally/api/schedule/${type}`,
			type: 'get',
			headers: {
				'Accept': 'application/json',
				'Authorization': authToken,
			},
			success: function(response)  {
				$(`#${type}_start_date`).val(response.start_date)
				$(`#${type}_end_date`).val(response.end_date)
			},
			error: (error) => {

			}
		});
	}

	//fill form data from the form
	var formDataFiller = () => {
		var fd = new FormData();
		var scan_start_date = $('#scan_start_date').val();
		var scan_end_date = $('#scan_end_date').val();
		var submit_start_date = $('#submit_start_date').val();
		var submit_end_date = $('#submit_end_date').val();

		fd.append('scan_start_date',scan_start_date);
		fd.append('scan_end_date',scan_end_date);
		fd.append('submit_start_date',submit_start_date);
		fd.append('submit_end_date',submit_end_date);

		return fd;
	}
</script>
<style>
label {
	overflow: hidden;
}
.col{
	align-content: center !important;
}
.btn__Set {
	min-width: 100px;
}
.docs-date {
	cursor:pointer;
}
</style>
@stop