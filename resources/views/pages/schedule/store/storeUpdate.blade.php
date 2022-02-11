@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title">Store Update</h5>
			<form class="m-5" enctype="multipart/form-data">
				<div>
					<div class="mb-5 mx-auto " style="max-width: 15rem; max-height: 15rem">
						<div class="card bg-light mb-3 mx-auto my-auto" style="max-width: 15rem; max-height: 15rem">
							<div class="card-body">
								<div class="form-group">
									{{-- <label>Store Image</label> --}}
									<img src="{{asset('storage/uploads/alt_image.png')}}" alt="store" width="220px" height="220px" id="img" class="img-thumbnail">
								</div>
							</div>
						</div>
						<div class="form-group mt-1">
							{{-- <label>Store Image</label> --}}
							<div class=" custom-file">
								<input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.apng,.bmp" id="image">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
					</div>
					<div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" id="name" required autofocus>
						</div>
						<div class="form-group">
							<label>Qr Code</label>
							<input type="text" class="form-control qr" name="store_qr_code" id="store_qr_code" readonly required >
						</div>
						<div class="form-group">
							<label>Address</label>
							<input type="text" class="form-control" name="address" id="address" required >
						</div>
						<div class="form-group">
							<label>Industry</label>
							<input type="text" class="form-control" name="industry" id="industry" required >
						</div>
						<div class="form-group">
							<label>Tel</label>
							<input type="text" class="form-control" name="tel" id="tel" required autofocus>
						</div>
						<div class="form-group">
							<label>Fax</label>
							<input type="text" class="form-control" name="fax" id="fax" required >
						</div>
						<div class="form-group">
							<label>Hours</label>
							<input type="text" class="form-control" name="hours" id="hours" required >
						</div>
						<div class="form-group">
							<label>Regular Holiday</label>
							<input type="text" class="form-control" name="regular_holiday" id="regular_holiday" required autofocus>
						</div>
						<div class="form-group">
							<label>Service</label>
							<input type="text" class="form-control" name="service" id="service" required >
						</div>
						<div class="form-group">
							<label>Stamping Conditions</label>
							<textarea class="form-control" id="stamping_conditions" name="stamping_conditions" rows="3"></textarea>
						</div>
						<div class="form-group mt-1 float-right">
							<button type="button" id="btnCancel" class="btn btn-light">Cancel</button>
							<button type="button" id="btnUpdate" class="btn btn-success">Update</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	//get Token
	var authToken = "Bearer " + localStorage.getItem("user_token");

	$(document).ready( function () {

		var changed = false;
		//token checker
		if(localStorage.getItem("user_token") == null)
			$(window).attr('location','/login');

		getStore(getParamsId());
		$("#image").change(function(){
			readURL(this)
			changed = true;
		});
		$("#btnUpdate").on('click',function(){
			updateStore(getParamsId(), formDataFiller(changed));
		});

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/management/store');
		});

		$('#store_qr_code').on( 'click',  function () {
			copyToClipboard($(this))
			toastr.success('クリップボードにコピー。','',{timeOut: 1000});
		});


		$('input[type="file"]').change(function(e){
			var fileName = e.target.files[0].name;
			$('.custom-file-label').html(fileName);
		});

	});

	//for updating store
	var updateStore = (id,data) => {
		$.ajax({
            url: `/api/stores/${id}?_method=PUT`,
			type: "post",
			data: data,
			processData: false,
    		contentType: false,
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				// $(window).attr('location','/management/store');
				toastr.success('更新成功.', '',{timeOut: 2000});
            },
            error: function(requestObject, error, errorThrown) {
				//auto log out if token is expired
				if(requestObject.status == 401) {
					$(window).attr('location','/login');
					localStorage.clear();
				}
				if(requestObject.status == 404) {
					$(window).attr('location','/management/store');
				}
				var errors = requestObject.responseJSON.errors;

				showToast(errors);
            }
        });
	}

	// for retrieving store
	var getStore = id => {
		$.ajax({
            url: `/api/stores/${id}`,
			type: "get",
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				formFiller(response)
            },
            error: function(requestObject, error, errorThrown) {
				//if request unauthorized
				if(requestObject.status === 401) {
					$(window).attr('location','/login');
					localStorage.clear();
				}
				//if store does not exist
				if(requestObject.status === 404) {
					$(window).attr('location','/management/store');
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

	//copy toclipboard
	var copyToClipboard = (element) => {
		element.select();
		document.execCommand("copy");
	}

	var readURL = (input) => {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#img').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
		}
	}

	//fill form data from the form
	var formDataFiller = changed => {
		var fd = new FormData();
		var image = $('#image')[0].files[0];
		var name = $('#name').val();
		var address = $('#address').val();
		var industry = $('#industry').val();
		var tel = $('#tel').val();
		var fax = $('#fax').val();
		var hours = $('#hours').val();
		var regular_holiday = $('#regular_holiday').val();
		var service = $('#service').val();
		var stamping_conditions = $('#stamping_conditions').val();

		fd.append('name',name);
		fd.append('address',address);
		fd.append('industry',industry);
		fd.append('tel',tel);
		fd.append('fax',fax);
		fd.append('hours',hours);
		fd.append('regular_holiday',regular_holiday);
		fd.append('service',service);
		fd.append('stamping_conditions',stamping_conditions);
		if (changed)
			fd.append('image',image);

		return fd;
	}

	// fill form  with the data of getStore Method
	var formFiller = response => {
		$('#name').val(response.name);
		$('#address').val(response.address);
		$('#tel').val(response.tel);
		$('#fax').val(response.fax);
		$('#industry').val(response.industry);
		$('#hours').val(response.industry);
		$('#regular_holiday').val(response.regular_holiday);
		$('#service').val(response.service);
		$('#stamping_conditions').val(response.stamping_conditions);
		$('#store_qr_code').val(response.store_qr_code);
		$('#img').attr('src', `/storage/${response.image}`);
	}

</script>
<style>
.qr{
	cursor: pointer;
}
img{
    max-height:200px !important;
    max-width:200px !important;
	min-width: 200px !important;
	max-width: 200px !important;
    height:auto !important;
    width:auto !important;
}
.container {
	min-width: 1000px;
}

label {
	overflow: hidden;
}
</style>
@stop