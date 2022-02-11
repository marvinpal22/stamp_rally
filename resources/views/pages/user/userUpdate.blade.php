@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Store Edit</h5>
			<form class="m-5">
				<div class="form-group">
				  <label for="exampleFormControlInput1">Name</label>
				  <input type="text" class="form-control" name="store_name" id="store_name" required autofocus>
				</div>
				<div class="form-group">
					<label for="exampleFormControlInput1">Address</label>
					<input type="text" class="form-control" name="store_address" id="store_address" required >
				</div>
				<div class="form-group">
					<label for="exampleFormControlInput1">Store Info</label>
					<input type="text" class="form-control" name="store_info" id="store_info" required >
				</div>
				<div class="form-group">
					<label for="exampleFormControlInput1">QR Code</label>
					<input type="text" class="form-control" name="store_qr_code" id="store_qr_code" required >
				</div>
				<div class="form-group float-right">
					<button type="button" id="btnCancel" class="btn btn-light">Cancel</button>
					<button type="button" id="btnUpdate" class="btn btn-success">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$( "#userManagement" ).addClass( "btn-success" );

	//get Token
	var authToken = "Bearer " + localStorage.getItem("user_token");

	$(document).ready( function () {


		//token checker
		if(localStorage.getItem("user_token") == null)
			$(window).attr('location','/stamp_rally/login');

		getStore(getParamsId());

		$("#btnUpdate").on('click',function(){
			var form_data = $('form').serialize();
			updateStore(getParamsId(), form_data);
		});

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/stamp_rally/management/user');
		});
	});

	//for updating store
	var updateStore = (id,data) => {
		$.ajax({
            url: `/stamp_rally/api/stores/${id}`,
			type: "patch",
			data: data,
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				// $(window).attr('location','/management/store');
				toastr.success('更新成功。', '',{timeOut: 2000});
            },
            error: function(requestObject, error, errorThrown) {
				//auto log out if token is expired
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login');
					localStorage.clear();
				}
				var errors = requestObject.responseJSON.errors;

				showToast(errors);
            }
        });
	}

	// for retrieving store
	var getStore = id => {
		$.ajax({
            url: `/stamp_rally/api/stores/${id}`,
			type: "get",
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
                $('#store_name').val(response.store_name);
				$('#store_address').val(response.store_address);
				$('#store_info').val(response.store_info);
				$('#store_qr_code').val(response.store_qr_code);
            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login');
					localStorage.clear();
				}
            }
        });
	}

	// get Id from Url
	var getParamsId = () => {
		var url = window.location.pathname;
		var urlArr = url.split('/');
		return urlArr[urlArr.length-1];
	}


</script>
<style>

</style>
@stop