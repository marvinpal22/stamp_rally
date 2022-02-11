@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card">
		<div class="card-body">
			<h5 class="card-title mx-auto my-auto" style="max-width: 10.2rem;">
				<span class="mx-auto">Store Registration</span>
			</h5>
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
								<label class="custom-file-label" for="image">Choose file</label>
							</div>
						</div>
					</div>
					<div>
						<div class="form-group">
							<label>Name</label>
							<input type="text" class="form-control" name="name" id="name" required autofocus>
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
							<button type="button" id="btnSubmit" class="btn btn-success">Submit</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>

$(document).ready(function(){

	var authToken = "Bearer " + localStorage.getItem("user_token");

	var changed = false;

	$(document).ready( function () {
		$("#btnSubmit").on('click',function(){
			addStore(formDataFiller());
		});

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/management/store')
		});

		$("#image").change(function(){
			readURL(this)
			changed = true;
		});

		$('input[type="file"]').change(function(e){
			var fileName = e.target.files[0].name;
			$('.custom-file-label').html(fileName);
		});
	});

	var addStore = (data) => {
		$.ajax({
            url: '/api/stores',
			type: 'post',
			data: data,
			headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
			contentType: false,
			processData: false,
            success: function (response) {
				$(window).attr('location','/management/store')

            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/login')
					localStorage.clear();
				}

				// Get errors
				var errors = requestObject.responseJSON.errors;
				//display errors
				showToast(errors);

            }
        });
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
	var formDataFiller = () => {
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


});


</script>
<style>
img{
    max-height:200px;
    max-width:200px;
	min-width: 200px;
	min-height: 200px;
    height:auto;
    width:auto;
}
.container {
	min-width: 1000px;
}
label {
	overflow: hidden;
}
</style>
@stop