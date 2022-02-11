@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card shadow">
		<div class="card-body">
			<h5 class="card-title mx-auto my-auto text-center" style="max-width: 10.2rem;">
				<span class="mx-auto font-weight-bold ">参加者登録</span>
			</h5>
			<form class="m-5" id="updateForm" enctype="multipart/form-data">
				<div>
					<div class="mb-5 mx-auto " style="max-width: 15rem; max-height: 15rem">
						<div class="card bg-light mb-3 mx-auto my-auto" style="max-width: 15rem; max-height: 15rem">
							<div class="card-body">
								<div class="form-group">
									<img src="{{asset('storage/uploads/alt_image.png')}}" alt="store" width="220px" height="220px" id="img" class="img-thumbnail">
								</div>
							</div>
						</div>
						<div class="form-group mt-1">
                            <div class="input-group">
                                <div class="custom-file" style="max-width: 300px;">
                                    <input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.bmp" id="image" lang="es">
                                    <label class="custom-file-label " for="customFile" id="customFileLabel">画像を選択</label>
                                </div>
                            </div>
                        </div>
					</div>
					<div>
						<div class="form-group">
							<label>事業者名</label>
							<input type="text" class="form-control" name="name" id="name" maxlength="100" required autofocus>
						</div>
						<div class="form-group">
							<label>住所</label>
							<input type="text" class="form-control" name="address" id="address" maxlength="100" required >
						</div>
						<div class="form-group">
							<label>業種</label>
							<input type="text" class="form-control" name="industry" id="industry" maxlength="100" >
						</div>
						<div class="form-group">
							<label>TEL</label>
							<input type="text" class="form-control" name="tel" id="tel" maxlength="100" autofocus>
						</div>
						<div class="form-group">
							<label>FAX</label>
							<input type="text" class="form-control" name="fax" id="fax" maxlength="100" >
						</div>
						<div class="form-group">
							<label>営業時間</label>
							<input type="text" class="form-control" name="hours" id="hours" maxlength="100">
						</div>
						<div class="form-group">
							<label>定休日</label>
							<input type="text" class="form-control" name="regular_holiday" id="regular_holiday" maxlength="100" autofocus>
						</div>
						<div class="form-group">
							<label>	サービス</label>
							<input type="text" class="form-control" name="service" id="service" maxlength="100" >
						</div>
						<div class="form-group">
							<label>押印条件</label>
							<textarea class="form-control" id="stamping_conditions" name="stamping_conditions"maxlength="255" rows="3"></textarea>
						</div>
						<div class="form-group mt-1 float-right">
							<button type="button" id="btnCancel" class="btn btn-light">キャンセル</button>
							<button type="submit" id="btnSubmit" class="btn btn-success">保存</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$( "#storeManagement" ).addClass( "btn-success rounded" );

$(document).ready(function(){

	var authToken = "Bearer " + localStorage.getItem("user_token");

	var changed = false;

	$(document).ready( function () {

		$( "#updateForm" ).submit(function( event ) {
			event.preventDefault();
			addStore(formDataFiller());
		});

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/stamp_rally/management/store')
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
            url: '/stamp_rally/api/stores',
			type: 'post',
			data: data,
			headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
			contentType: false,
			processData: false,
            success: function (response) {
				$(window).attr('location','/stamp_rally/management/store')

            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}

				// Get errors
				var errors = requestObject.responseJSON;
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
[hidden] {
  display: none !important;
}

html, body {
	height: 100%;
}
body {
	background-color: #F8F9FA;
}
</style>
@stop