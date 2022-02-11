@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-5 row">
	</div>
	<div class="row">
		<table id="storeTable">
			<thead>
				<tr>
					<th scope="col" style="min-width: 70px;">スタンプNO.</th>
					<th scope="col" style="min-width: 75px;">画像</th>
					<th scope="col" style="min-width: 230px;">事業者名</th>
					<th scope="col" style="min-width: 250px;">住所</th>
					<th scope="col" style="min-width: 150px;">業種</th>
					<th scope="col" style="min-width: 150px;">TEL</th>
					<th scope="col" style="min-width: 150px;">FAX</th>
					<th scope="col" style="min-width: 200px;">営業時間</th>
					<th scope="col" style="min-width: 200px;">定休日</th>
					<th scope="col" style="min-width: 400px;">押印条件</th>
					<th scope="col" style="min-width: 150px;">サービス</th>
					<th scope="col" style="min-width: 100px;">訪問日</th>
					<th scope="col" style="min-width: 70px;">アクション</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>
	</div>
	<!-- Image Modal -->
	<div class="modal fade " id="imagemodal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-btn">
			{{-- <div class="close">&times;</i></div> --}}

		</div>
		<div class="modal-dialog modal-lg modal-dialog-centered">
			<div class="modal-content">
				<img class="modal-img" download="alt_image.png"/>
			</div>
		</div>
	</div>
</div>
<script>
	$( "#userManagement" ).addClass( "btn-success" );

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {
		dataTable = $('#storeTable').DataTable(dtOptions);

		$('table').addClass("table table-bordered table-responsive table-sm w-100 position-relative table-hover");


		$('#storeTable tbody').on( 'click', 'button#iconEye', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/stamp_rally/management/store/update/${rowId}`);
		});

		$('#storeTable tbody').on( 'click', 'i#storeImage', function () {
			var src = $(this).attr("image-path");
			$(".modal-img").prop("src",src);
		});
		$('#storeTable thead').on( 'click', 'a#downloadAll', function () {
			downloadFiles();
		});
	});

		// get Id from Url
	var getParamsId = () => {
		var url = window.location.pathname;
		var urlArr = url.split('/');
		return urlArr[urlArr.length-2];
	}

	var getDateTime = (data) => {
		var date = new Date(data);
		var fdate = `${dateNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
		var ftime = date.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric',second: 'numeric', hour12: true });

		return fdate +'<br/>' + ftime;
	}

	var dateNames = Date.prototype.monthNames = [
		"一月", "二月", "三月",
		"四月", "五月", "六月",
		"七月", "八月", "九月",
		"十月", "十一月", "十二月"
	];

	var dtOptions = {
		ajax: {
			type: 'GET',
			url: `/stamp_rally/api/user/${getParamsId()}/visited`,
			dataSrc: 'stores',
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
		columnDefs: [
			// { "width": "6rem", "targets": 4 },
			{ "width": "6rem", "targets": 3 }
		],
		language: {
				url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
			},
		"order": [[ 11, "desc" ]],
		columns: [
			{data: 'id', class: 'align-middle'},
			{
				data: 'image',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return `<i href="#imagemodal" id="storeImage" class="fa fa-eye" data-toggle="modal" data-target="#imagemodal" image-path="{{asset('/storage/app/public')}}/${data}"></i>`
				}
			},
			{data: 'name', class: 'align-middle'},
			{data: 'address', class: 'align-middle'},
			{data: 'industry', class: 'align-middle'},
			{data: 'tel', class: 'align-middle'},
			{data: 'fax', class: 'align-middle'},
			{data: 'hours', class: 'align-middle'},
			{data: 'regular_holiday', class: 'align-middle'},
			{data: 'stamping_conditions', class: 'align-middle'},
			{data: 'service', class: 'align-middle'},
			{
				data: 'created_at',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return getDateTime(data);
				}
			},
			{
				data: 'id',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return '<button class="btn"  id="iconEye" data-id="'+data+'"><i class="fas fa-pen-alt"></i></button>'
				}
			}

		],
	}

	var copyToClipboard = (element) => {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}



</script>
<style>
	.modal-img {
			width:100%;
		}
	.table td,th{
	text-align: center;
	}
	table{
		table-layout: fixed;
	}

	tr:nth-of-type(odd)>td {
	background: #F3F3F3;
	}
	tr:nth-of-type(even)>td, th:first-child, th:last-child {
	background: white;
	}
	td {
		overflow: auto;
	}
	#iconDelete, #iconUpdate{
		padding: 5px;
	}
	td:first-child, th:first-child{
		margin: 0%;
		/* min-width: 50px !important; */
		left: 0%;
		position: sticky !important;
		border: 0px;
		z-index: 1;
		/* background: #F3F3F3; */
	}

	td:last-child, th:last-child{
		right: 0%;
		position: sticky !important;
		z-index: 1;
		/* border: 1px solid black; */
	}

	td {
		word-break: break-word;
	}
	li {
		padding: 0px !important;
		margin: 0px !important;
		border: 0px !important;
	}

	*::-webkit-scrollbar {
	width: 10px;
	}

	/* Track */
	*::-webkit-scrollbar-track {
	background: #f1f1f1;
	}

	/* Handle */
	*::-webkit-scrollbar-thumb {
	background: #888;
	}

	/* Handle on hover */
	*::-webkit-scrollbar-thumb:hover {
	background: #555;
	}
</style>
@stop