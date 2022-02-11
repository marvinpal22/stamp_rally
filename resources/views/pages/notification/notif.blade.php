@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card rounded shadow">
		<div class="card-header">
			<h5 class="mx-auto my-auto text-center" style="max-width: 10.2rem;">
				<span class="mx-auto font-weight-bold ">通知を送信する</span>
			</h5>
		</div>

		<div class="card-body">
			<form id="sendNotif" class="mr-5 ml-5" enctype="multipart/form-data">
				<div class="form-group">
				  <input type="text" class="form-control" name="title" id="title" maxlength="255" placeholder="題名" required autofocus>
				</div>
				<div class="form-group">
					<div class="input-group">
						<div class="custom-file" style="max-width: 300px;">
							<input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.bmp" id="image" lang="es">
							<label class="custom-file-label " for="customFile" id="customFileLabel">画像を選択</label>
						</div>
					</div>
				</div>
				<div class="form-group">
					<textarea class="form-control" name="body" id="body" rows="7" minlength="5" maxlength="15000" placeholder="メッセージ..."  required></textarea>
				</div>
				<div class="form-group float-right">
					<button type="submit" id="btnSubmit" class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing Order">送信</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$( "#notification").addClass( "btn-success rounded");
	var authToken = "Bearer " + localStorage.getItem("user_token");


	$( "#sendNotif" ).submit(function( event ) {
		event.preventDefault();

		var $this = $('#btnSubmit');
		var loadingText = '送信中...';
		if ($($this).html() !== loadingText) {
			$this.data('original-text', $($this).html());
			$this.html(loadingText);
			$this.attr('disabled', true);
		}

			sendNotif(formDataFiller());
	});


	$('#btnSubmit').on('click', function() {

  	});


	$('input[type="file"]').change(function(e){
		var fileName = e.target.files[0].name;
		$('.custom-file-label').html(fileName);
	});

	var sendNotif = (data) => {
		$.ajax({
            url: "/stamp_rally/api/notification/send",
			type: "post",
			data: data,
			processData: false,
    		contentType: false,
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				toastr.success('通知が送信されました。','',{timeOut: 3000});
				$('#title').val('');
				$('#body').val('');
				$('#customFileLabel').text('画像を選択');
				$("#image").val('');
				revertBtn($('#btnSubmit'));
            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}
				revertBtn($('#btnSubmit'));
				toastr.error('エラー！ もう一度お試しください.','',{timeOut: 3000});
            }
        });
	}

	var formDataFiller = () => {
		var fd = new FormData();
		var image = $('#image')[0].files[0];
		var title = $('#title').val();
		var body = $('#body').val();

		fd.append('title',title);
		fd.append('body',body);
		fd.append('image',image);

		return fd;
	}

	var revertBtn = btn => {
		btn.html(btn.data('original-text'));
		btn.attr('disabled', false);
	}


</script>
<style>
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