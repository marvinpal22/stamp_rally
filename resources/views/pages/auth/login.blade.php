@extends('layouts.app')
@section('content')
<div class="container mt-5 h-50">
	<div class="row d-flex justify-content-center">
		<div class="card rounded shadow mb-3 my-auto pl-3 pr-3" style="width: 25rem;">
			<div class="card-body">
				<h5 class="card-title mx-auto my-auto text-center " style="max-width: 10.2rem;">
					<span class="mx-auto font-weight-bold ">ログイン</span>
				</h5>
				<form class="mt-3" action="/stamp_rally/api/admin/login" id="loginForm">
				<div class="form-group">
				  <label for="exampleInputUsername1">ユーザー名</label>
				  <input class="form-control" name="username" id="username" required>
				</div>
				<div class="form-group">
					<label for="password">パスワード</label>
					<input type="password" class="form-control" name="password" id="password" required>
				</div>
				<div>
				<div class="form-group text-center mt-4">
					<button type="submit" class="btn btn-primary">ログイン</button>
				</div>

				</div>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
$(document).ready( function () {
	$('main').hide();
	if(localStorage.getItem("user_token") != null)
		$(window).attr('location','management/user')
	else
		$('main').show();
});

$( "#loginForm" ).submit(function( event ) {
 	event.preventDefault();

	var $form = $( this ),
		data = `${$form.serialize()}&role=2`,
		url = $form.attr( "action" );

		$.ajax({
            url: url,
			type: "post",
			data: data,
            headers: {
				'Accept' : 'application/json'
			},
            success: function (response) {
				localStorage.setItem('user_token', response.token);
				localStorage.setItem('username', response.user.username);
				$(window).attr('location','/stamp_rally/management/user');
            },
            error: function(requestObject, error, errorThrown) {
				console.log(requestObject, error, errorThrown);
				toastr.error('電子メールまたはパスワードが正しくありません', '',{timeOut: 2000});
            }
        });
});

</script>
<style>

html, body, #app, main, .row {
    height:100% !important;
}

</style>
@stop