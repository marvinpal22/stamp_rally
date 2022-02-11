@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card rounded shadow">
		<div class="card-body" id="registerForm">
			<h5 class="card-title mx-auto my-auto text-center" style="max-width: 10.2rem;">
				<span class="mx-auto font-weight-bold ">ユーザー登録</span>
			</h5>
			<form action="/stamp_rally/api/user" class="m-5">
				<div class="form-group">
				  <label for="email">メール</label>
				  <input type="text" class="form-control" name="email" id="email" required autofocus>
				</div>
				<div class="form-group">
					<label for="username">ユーザー名</label>
					<input type="text" class="form-control" name="username" id="username" required autofocus>
				</div>
				<div class="form-group">
					<label for="password">パスワード</label>
					<input type="password" class="form-control" name="password" id="password" minlength="6" required >
				</div>
				<div class="form-group">
					<label for="password_confirmation">パスワード再入力</label>
					<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" minlength="6" required >
				</div>
				<div class="form-group float-right">
					<button type="button" id="btnCancel" class="btn btn-light">キャンセル</button>
					<button type="submit" id="btnSubmit" class="btn btn-success">登録</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$( "#userManagement" ).addClass( "btn-success" );

	var authToken = "Bearer " + localStorage.getItem("user_token");

	$("#btnCancel").on('click',function(){
			$(window).attr('location','/stamp_rally/management/user')
		});

		$( "#registerForm" ).submit(function( event ) {
		event.preventDefault();
		var form_data = $('form').serialize();
		if(passwordChecker($("#password").val(),$("#password_confirmation").val())) {
			register(form_data);
		}
	});

//regiter user
var register = (data) => {
	$.ajax({
		url: '/stamp_rally/api/user',
		type: "post",
		data: data,
		headers: {
			'Accept' : 'application/json',
			'Authorization' : authToken,
		},
		success: function (response) {
			$(window).attr('location','/stamp_rally/management/user');
		},
		error: function(requestObject, error, errorThrown) {
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

// var isEmail = (email) => {
//   var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
//   if(!regex.test(email))
// 	  toastr.error('無効なメール。', '',{timeOut: 2000});

// 	console.log(regex.test(email));
//   return regex.test(email);
// }
</script>
<style>
html, body {
	height: 100%;
}
body {
	background-color: #F8F9FA;
}
</style>
@stop