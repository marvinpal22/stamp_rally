<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<link rel="icon" href="{{ asset('public/storage/logo/stamp_rally_logo.png') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- <title>{{ config('app.name', 'Laravel') }}</title> -->
    <title>Stamp Rally</title>

	 <!-- Fonts -->
	<link rel="dns-prefetch" href="//fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
	<!-- JQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	<!-- Bootstrap -->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
	<!-- DataTables -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
	<!-- Toastr -->
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" />
	<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous"></script>
	<!-- Date Picker -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css" integrity="sha512-YdYyWQf8AS4WSB0WWdc3FbQ3Ypdm0QCWD2k4hgfqbQbRCJBEgX0iAegkl2S1Evma5ImaVXLBeUkIlP6hQ1eYKQ==" crossorigin="anonymous" />
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/i18n/datepicker.ja-JP.min.js" integrity="sha512-ZP3x/vrH154LojT7mCIBPQoioAD64+Qx8LQ1LZSP5DO6gFOx79U2AMl4t3dfwKHPNRIR4MmG4/SOcgagUngtaQ==" crossorigin="anonymous"></script>
</head>
<body>
    <div id="app">
		@section('nav')
		<nav class="navbar navbar-expand-lg navbar-dark bg-dark relative-top">
			<a class="navbar-brand mr-auto" href="/">Stamp Rally</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
			</button>
			@if(!str_contains(Request::url(),'/stamp_rally/login'))

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item active">
							<a class="btn " type="button" id="userManagement">
								<span class="text-light">ユーザー管理</span>
							</a>
						</li>
						<li class="nav-item active">
							<a class="btn " type="button" id="storeManagement">
								<span class="text-light">参加者管理</span>
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-light" href="#" id="notification" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								お知らせ
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
								<button class="dropdown-item" id="pushNotification" type="button">通知を送る</button>
								<button class="dropdown-item" id="notificationList" type="button">通知リスト</button>
							</div>
						</li>
						<li class="nav-item">
							<a class="btn" type="button" id="schedule">
								<span class="text-light">スケジュール管理</span>
							</a>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle text-light" href="#" id="email" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Dropdown
							</a>
							<div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
								<button class="dropdown-item" id="account" type="button">アカウント</button>
								<button class="dropdown-item" id="adminManagement" type="button">管理者を登録する</button>
								<button class="dropdown-item" id="logout" type="button">ログアウト</button>
							</div>
						</li>
					</ul>
				</div>
			@endif

		</nav>
		@endsection

		@yield('nav')
        <main class="py-4">
            @yield('content')
        </main>
	</div>
</body>
</html>

<script>
	var authToken = "Bearer " + localStorage.getItem("user_token");
	var user = localStorage.getItem("username");


	$('main').hide();
	if(localStorage.getItem("user_token") == null && window.location.pathname !== '/stamp_rally/login') {
		// $(window).attr('location','/stamp_rally/login');
		$('main').show();
	}
	else {
		$('main').show();
	}

	$(document).ready( function () {
		$('#email').text(user);

		$("#notificationList").on('click', function (){
			$(window).attr('location','/stamp_rally/management/notifications');
		});

		$("#logout").on('click', function (){
			logout();
		});

		$("#userManagement").on('click', function (){
			$(window).attr('location','/stamp_rally/management/user');
		});

		$("#storeManagement").on('click', function (){
			$(window).attr('location','/stamp_rally/management/store');
		});

		$("#pushNotification").on('click', function (){
			$(window).attr('location','/stamp_rally/management/notification');
		});

		$("#schedule").on('click', function (){
			$(window).attr('location','/stamp_rally/management/schedule');
		});

		$("#account").on('click', function (){
			$(window).attr('location','/stamp_rally/account');
		});

		$("#adminManagement").on('click', function (){
			$(window).attr('location','/stamp_rally/admin/registration');
		});
	});

	//functions here can be used in everry pages that extends app layout.


	var logout = () => {
		$.ajax({
            url: '/stamp_rally/api/logout',
			type: "post",
            headers: {
				'Accept' : 'application/json',
				'Authorization' : authToken,
			},
            success: function (response) {
				localStorage.clear();
				$(window).attr('location','/stamp_rally/login');
            },
            error: function(requestObject, error, errorThrown) {
				console.log(requestObject.status);
            }
        });
	}

	//settings for toastr
	toastr.options = {
		showMethod: 'fadeIn',
		newestOnTop: false,
	};

	//for showing errors
	var showToast = (errors) => {
		var time,x = 1;
		for(const error in errors) {
			x++;
			time =  100 * x;
			setTimeout(
				function() {
					toastr.error(errors[error], '',{timeOut: 3000});
				},
				time
			);

		}
	}
</script>
<style>
	i:hover:not([id="jicon"]){
		cursor: pointer;
	}
	.dataTables_wrapper {
		min-width: 100px;
	}
	.custom-file-input ~ .custom-file-label::after {
        content: "閲覧する";
    }

</style>