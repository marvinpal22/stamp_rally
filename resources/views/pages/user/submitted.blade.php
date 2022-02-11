@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-5 row mr-1">
	</div>
	<div >
		<table class="table table-bordered table-responsive table-sm w-100 position-relative mt-5 mb-5" id="userTable">
			<thead>
				<tr>
					<th scope="col" style="max-width: 2rem;">ID</th>
					<th scope="col">ユーザー名</th>
					<th scope="col" style="max-width: 4rem;">メール</th>
					<th scope="col">氏名</th>
					<th scope="col">携帯番号</th>
					<th scope="col" style="max-width: 300px; min-width: 300px;">ご感想など</th>
					<th scope="col"  style="max-width: 300px; min-width: 300px;">住所</th>
					<th scope="col" style="max-width: 100px;">スタンプ数</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script>
	$( "#userManagement" ).addClass( "btn-success" );

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {

		dataTable = $('#userTable').DataTable(dtOptions);
		$('table').addClass( "table table-bordered table-striped table-sm w-100 position-responsive table-hover");
	});

	var dtOptions = {
			ajax: {
				type: 'GET',
				url: '/stamp_rally/api/user/submitted',
				dataSrc: 'data',
				dataType: 'json',
				contentType: 'application/json',
				headers: {
					'Authorization' : authorizationToken,
					'Accept' : 'application/json'
				},
				error: function(requestObject, error, errorThrown) {
					if(requestObject.status == 401) {
						$(window).attr('location','/stamp_rally/login')
						localStorage.clear();
					}
				},
			},
			"order": [[ 7, "desc" ]],
			language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
            },
    		columns: [
				{data: 'id', class: 'align-middle'},
				{data: 'username', class: 'align-middle'},
				{data: 'email', class: 'align-middle'},
				{data: 'full_name', class: 'align-middle'},
				{data: 'contact_no', class: 'align-middle'},
				{data: 'impressions', class: 'align-middle'},
				{data: 'address', class: 'align-middle'},
				{data: 'stamps_collected', class: 'align-middle'},
			],
		}

</script>
<style>
	.table td,th{
	text-align: center;
	}
	/*
	table {
		/* max-width: 100px !important; */
		color: red !important;
	} */
	th:hover{
		cursor: pointer;
	}

	li {
		padding: 0px !important;
		margin: 0px !important;
		border: 0px !important;
	}
</style>
@stop