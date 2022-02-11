@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card shadow">
		<div class="card-body">
			<h5 class="card-title mx-auto my-auto text-center" style="max-width: 10.2rem;">
				<span class="mx-auto font-weight-bold ">参加者の更新</span>
			</h5>
			<form class="m-5" id="registerForm" enctype="multipart/form-data">
				<div>
					<div class="mb-5 mx-auto " style="max-width: 15rem; max-height: 15rem">
						<div class="card bg-light mb-3 mx-auto my-auto" style="max-width: 15rem; max-height: 15rem">
							<div class="card-body">
								<div class="form-group">
									{{-- <label>Store Image</label> --}}
									<img src="{{asset('public/storage/uploads/alt_image.png')}}" alt="store" width="220px" height="220px" id="img" class="img-thumbnail">
								</div>
							</div>
						</div>
						<div class="form-group mt-1">
							{{-- <label>Store Image</label> --}}
							<div class=" custom-file">
								<input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.apng,.bmp" id="image">
								<label class="custom-file-label" for="customFile">画像を選択</label>
							</div>
						</div>
					</div>
					<div>
						<div class="form-group">
							<label>事業者名</label>
							<input type="text" class="form-control" name="name" id="name" maxlength="100" required autofocus>
						</div>
						<div class="form-group">
							<label>ＱＲコード</label>
							<input type="text" class="form-control qr" name="store_qr_code" id="store_qr_code" readonly required >
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
						<div class="form-group">
							<label>サービス</label>
							<input type="text" class="form-control" name="service" id="service" maxlength="100" >
						</div>
						<div class="form-group">
							<label>押印条件</label>
							<textarea class="form-control" id="stamping_conditions" name="stamping_conditions" maxlength="255" rows="3"></textarea>
						</div>
						<div class="form-group mt-1 float-right">
							<button type="button" id="btnCancel" class="btn btn-light">キャンセル</button>
							<button type="submit" id="btnUpdate" class="btn btn-success">更新</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- Update Modal -->
	<div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog  modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">更新？ </h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body text-danger">
					まだまだイベントは続いています。更新してもよろしいですか？
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
					<button type="button" id="btnUpdateConfirm" class="btn btn-danger" data-dismiss="modal">確認</button>
				</div>
			</div>
		</div>
    </div>
</div>
<script>
	$( "#storeManagement" ).addClass( "btn-success rounded");

	//get Token
	var authToken = "Bearer " + localStorage.getItem("user_token");
	var canUpdate;

	$(document).ready( function () {
		checkSched()
		var changed = false;
		//token checker
		if(localStorage.getItem("user_token") == null)
			$(window).attr('location','/stamp_rally/login');

		getStore(getParamsId());
		$("#image").change(function(){
			readURL(this)
			changed = true;
		});

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/stamp_rally/management/user')
		});

		$( "#registerForm" ).submit(function( event ) {
			event.preventDefault();
			console.log(canUpdate)
            if(canUpdate)
                updateStore(getParamsId(), formDataFiller(changed));
            else
                $('#updateModal').modal('show')
        });

        $('#btnUpdateConfirm').on('click', function(){
            updateStore(getParamsId(), formDataFiller(changed));
        });

		$("#btnCancel").on('click',function(){
			$(window).attr('location','/stamp_rally/management/store');
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
            url: `/stamp_rally/api/stores/${id}?_method=PUT`,
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
				toastr.success('更新成功。', '',{timeOut: 2000});
            },
            error: function(requestObject, error, errorThrown) {
				//auto log out if token is expired
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login');
					localStorage.clear();
				}
				if(requestObject.status == 404) {
					$(window).attr('location','/stamp_rally/management/store');
				}
				var errors = requestObject.responseJSON;

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
				formFiller(response)
            },
            error: function(requestObject, error, errorThrown) {
				//if request unauthorized
				if(requestObject.status === 401) {
					$(window).attr('location','/stamp_rally/login');
					localStorage.clear();
				}
				//if store does not exist
				if(requestObject.status === 404) {
					$(window).attr('location','/stamp_rally/management/store');
				}

            }
        });
	}

	var checkSched = () => {
		$.ajax({
			url: `/stamp_rally/api/schedule`,
			type: 'get',
			headers: {
			'Authorization' : authToken,
			'Accept' : 'application/json'
			},
			success: function (response) {
                if(response.stamp.message == "available" || response.submission_form.message == "available")
                    canUpdate = false
                else
					canUpdate = true
			},
			error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/login')
					localStorage.clear();
				}
                toastr.error('エラー！ もう一度お試しください。', '',{timeOut: 3000});
			}
        });
    };

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
		$('#hours').val(response.hours);
		$('#regular_holiday').val(response.regular_holiday);
		$('#service').val(response.service);
		$('#stamping_conditions').val(response.stamping_conditions);
		$('#store_qr_code').val(response.store_qr_code);
		$('#img').attr('src', `/stamp_rally/public/storage/${response.image}`);
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

html, body {
	height: 100%;
}
body {
	background-color: #F8F9FA;
}
</style>
@stop