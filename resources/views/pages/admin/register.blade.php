@extends('layouts.app')
@section('content')
<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="card rounded shadow mx-auto" style="width: 30rem;">
			<div class="card-header">
				<h5 class="mx-auto my-auto text-center" style="max-width: 10.2rem;">
					<span class="mx-auto font-weight-bold ">管理者を登録する</span>
				</h5>

			</div>
			<div class="card-body">
				<form action="/stamp_rally/api/user" id="registerForm" autocomplete="off">
						<div class="form-group">
						<label for="exampleInputEmail1">メール</label>
						<input type="text" class="form-control" name="email" id="email" required>
						</div>
						<div class="form-group">
							<label for="exampleInputEmail1">ユーザー名</label>
							<input type="text" class="form-control" name="username" id="username" required>
						</div>
						<div class="form-group">
						<label for="exampleInputPassword1">パスワード</label>
						<input type="password" class="form-control" name="password" autocomplete="off" id="password" required>
						</div>
						<div class="form-group">
							<label for="exampleInputPassword1">パスワード再入力</label>
							<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
						</div>
						<div >
							<button type="submit" class="btn btn-success float-right">登録</button>
						</div>
					</form>
			</div>
		  </div>
	</div>
</div>
<script>
	$( "#email" ).addClass( "btn-success rounded");

	var authToken = "Bearer " + localStorage.getItem("user_token");
	$("#btnCancel").on('click',function() {
		$(window).attr('location','/stamp_rally/login');
	});
	$( "#registerForm" ).submit(function( event ) {
		event.preventDefault();

		var $form = $( this ),
			data = `${$form.serialize()}&role=2`,
			url = $form.attr( "action" );
			if(passwordChecker($("#password").val(),$("#password_confirmation").val())) {
				register(data, url);
			}
	});


	$("#btnCancel").on('click',function() {
		$(window).attr('location','/stamp_rally/login');
	});

	//regiter user
	var register = (data,url) => {
		$.ajax({
			url: url,
			type: "post",
			data: data,
			headers: {
				'Accept' : 'application/json',
				'Authorization' : authToken,
			},
			success: function (response) {
				toastr.success('ユーザーが正常に作成されました', '',{timeOut: 3000});
                $("form")[0].reset();
			},
			error: function(requestObject, error, errorThrown) {
				//get error list
				var errors = requestObject.responseJSON;

				showToast(errors);
			}
		});
	}

	//check if password is valid
	var passwordChecker = (pass,passCon) => {
		if(pass.length < 6) {
			toastr.error('パスワードは6文字以上でなければなりません。', '',{timeOut: 2000});
			return false;
		}
		else if(pass !== passCon) {
			toastr.error('パスワードミスマッチ。', '',{timeOut: 2000});
			return false;
		}
		else {
			return true;
		}
	}

	//show errors in toastr
	var showToast = (errors) => {
		var time,x = 1;
		for(const error in errors) {
			x++;
			time =  100 * x;
			setTimeout(
				function() {
					toastr.error(errors[error], '',{timeOut: 2000});
				},
				time
			);

		}
	}

</script>
<style>

</style>
@stop