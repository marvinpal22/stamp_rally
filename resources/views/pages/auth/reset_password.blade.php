


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
<!------ Include the above in your HEAD tag ---------->
<style>
.form-gap {
    padding-top: 200px;
}
</style>
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

 <body>

 <div class="form-gap"></div>
<div class="container">
    <div class="row">
      <div class="col-md-4 col-md-offset-4">
            <div class="panel panel-default">
              <div class="panel-body">
                <div class="text-center">
                  <p>You can reset your password here.</p>
                  <div class="panel-body">

                    <form id="resetPasswordForm" method="POST" action="">
                    {{csrf_field()}}
                    {{ session()->get( 'token' ) }}

                    <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                          <input id="email" name="email" placeholder="Email" class="form-control"  value="{{$email ?? ''}}" type="email" readonly="readonly">
                        </div>
                      </div>

                      <input id="token" name="token" placeholder="Token" class="form-control"  value="{{$token ?? ''}}" type="hidden" readonly="readonly">

                      <div class="form-group">
                        <div class="input-group">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue"></i></span>
                          <input id="password" name="password" placeholder="Password" class="form-control"  type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <div class="input-group ">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-lock color-blue "></i></span>
                          <input id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" class="form-control pt-3"  type="password">
                        </div>
                      </div>

                      <div class="form-group">
                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" id="submit_btn" type="button">
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
 </body>


  <!-- <script>
    var password = $('#password').val();
    var password_con  = $('#password_confirmation').val();

    if(password === ""){
      alert("Password is empty.");
      $("#password").focus();
    }
    else if(password_con === ""){
      alert("Password confirmation is empty.");
      $("#password_confirmation").focus();
    }
  </script> -->
 <script>
    $("#submit_btn").on('click',function(){
        reset();
    });



    function reset(){
        $.ajax({
            url: "/api/password/reset",
            type: "post",
            data:  $("#resetPasswordForm").serialize(),
            header:('Content-Type', 'text/plain'),
            success: function (response) {
                alert(response);
            // You will get response from your PHP page (what you echo or print)
            },
            error: function(jqXHR, textStatus, errorThrown) {
              var message  = jqXHR.responseJSON.errors.password[0];
               alert(jqXHR.responseJSON.errors.password[0]);
               if(message == 'パスワードは6文字以上でなければなりません。'){
                $("#password").focus();
               }
               if(message == 'パスワードミスマッチ。'){
                $("#re_password").focus();
               }
            }
        });
    }
</script>

<script>
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
</script>

