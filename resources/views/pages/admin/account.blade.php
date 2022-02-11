@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card rounded shadow mx-auto" style="max-width: 500px;">
		<div class="card-header">
			<h5 class="mx-auto my-auto text-center" >
				<span class="mx-auto font-weight-bold ">パスワードを変更する</span>
			</h5>
		</div>
		<div class="card-body">
			<form id="changePassForm" class="mr-5 ml-5" >
				<div class="form-group">
					<input type="password" class="form-control" name="current_password" id="current_password" placeholder="現在のパスワード。" required autofocus>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password" id="password" placeholder="パスワード" required autofocus>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="パスワード再入力" required autofocus>
				</div>
				<div class="form-group float-right">
					<button type="submit" id="btnSubmit"  class="btn btn-success">保存</button>
				</div>
			</form>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">変更パスワード?</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					本当に変更を保存しますか？
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
					<button type="button" id="btnConfirm" class="btn btn-primary" data-dismiss="modal">確認</button>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$( "#email" ).addClass( "btn-success rounded");

	var authToken = "Bearer " + localStorage.getItem("user_token");
	var user = localStorage.getItem("email");

	$( "#name" ).val(user);
	$( "#changePassForm" ).submit(function( event ) {
		event.preventDefault();
		// var $form = $( this ),
		// 	data = `${$form.serialize()}&role=2`,
		// 	url = $form.attr( "action" );
			if(passwordChecker($("#password").val(),$("#password_confirmation").val())) {
				$('#confirmModal').modal('toggle');
			}
	});

	$("#btnConfirm").on("click", function () {
		var $form = $('#changePassForm'),
			data = $form.serialize();
		changePass(data);
	});


	// $("#btnSubmit").on('click',function() {
	// 	$('#confirmModal').modal('toggle');
	// });

	//regiter user
	var changePass = (data) => {
		$.ajax({
			url: '/stamp_rally/api/changepass',
			type: "post",
			data: data,
			headers: {
				'Accept' : 'application/json',
				'Authorization': authToken
			},
			success: function (response) {
				$("#password").val('')
				$("#password_confirmation").val('')
				toastr.success('パスワード変更成功!', '',{timeOut: 2000});
			},
			error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}
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