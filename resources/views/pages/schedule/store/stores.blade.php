@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-5 row">
		<button type="button" id="addStore" class="btn btn-primary float-right ml-auto"><i class="fa fa-trash"></i>Add Store</button>
	</div>
	<table id="storeTable">
		<thead>
			<tr>
				<th scope="col">Stamp No.</th>
				<th scope="col" style="min-width: 200px;">Store Image</th>
				<th scope="col" style="min-width: 230px;">Name</th>
				<th scope="col" style="min-width: 250px;">Address</th>
				<th scope="col" style="min-width: 150px;">Industry</th>
				<th scope="col" style="min-width: 150px;">Tel</th>
				<th scope="col" style="min-width: 150px;">Fax</th>
				<th scope="col" style="min-width: 200px;">Hours</th>
				<th scope="col" style="min-width: 200px;">Regular Holiday</th>
				<th scope="col" style="min-width: 400px;">Stamping Conditions</th>
				<th scope="col" style="min-width: 150px;">Service</th>
				<th scope="col">Qr Code</th>
				{{-- <th scope="col">Stores vistited</th> --}}
				<th scope="col" >Action</th>
			</tr>
		</thead>
		<tbody>

		</tbody>
	</table>

	<!-- Modal -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Delete store</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					Are you sure you want to delete this record ?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="button" id="btnConfirm" class="btn btn-danger" data-dismiss="modal">Confirm</button>
				</div>
			</div>
		</div>
	</div>
	{{-- <img src="{{asset('storage/14.png')}}" alt=""> --}}
</div>
<script>
	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {

		dataTable = $('#storeTable').DataTable(dtOptions);
		$('input[type="search"]').attr("class", "form-control");
		$('select').attr("class", "form-control form-control-sm");
		$('table').addClass( "table table-bordered table-responsive table-sm w-100 position-relative");


		$('#storeTable tbody').on( 'click', 'button#iconDelete', function () {
			selectedBtn = $(this);
		});

		$('#storeTable tbody').on( 'click', 'button#iconEye', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/management/stores/${rowId}/visited`);
		});

		$('#storeTable tbody').on( 'click', 'p#qr', function () {
			copyToClipboard($(this))
			toastr.success('クリップボードにコピー。','',{timeOut: 2000});
		});

		$('#storeTable tbody').on( 'click', 'button#iconUpdate', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/management/store/update/${rowId}`);
		});

		$("#addStore").on('click',function(){
			$(window).attr('location','/management/store/add')
		});

		$("#btnConfirm").on('click',function(){
			deleteStore(selectedBtn.attr('data-id'));
		});
		$('.dataTables_length').addClass('bs-select');
	});

	var dtOptions = {
		ajax: {
			type: 'GET',
			url: '/api/stores',
			dataSrc: 'stores',
			dataType: 'json',
			contentType: 'application/json',
			headers: {
				'Authorization' : authorizationToken,
				'Accept' : 'application/json'
			},
			error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/login')
					localStorage.clear();
				}
			}
		},
		columnDefs: [
			{ "width": "7rem", "targets": [2,3,4,5,6,7,8,9,10,11,12] },
			{ "width": "5rem", "targets": 0 }
		],
		"order": [[ 0, "desc" ]],
		columns: [
			{data: 'id', class: 'align-middle'},
			{
				data: 'image',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return `<img class="img-thumbnail" width="200rem" height="200rem" src="{{asset('storage')}}/${data}" alt="">`
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
				data: 'store_qr_code',
				class: 'align-middle pt-2',
				mRender: function (data, type, row) {
					return '<p class="qr" id="qr">'+ data + '</p>'
				}
			},
			{
				data: 'id',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return '<button class="btn"  id="iconDelete" data-toggle="modal" data-target="#exampleModal" data-id="'+data+'"><i class="fa fa-trash"></i></button> <button class="btn" id="iconUpdate" data-id="'+data+'"><i class="fas fa-pen-alt"></i></button>'
				}
			}

		],
	}

	var deleteStore = id => {
		$.ajax({
			url: `/api/stores/${id}`,
			type: 'delete',
			headers: {
			'Authorization' : authorizationToken,
			'Accept' : 'application/json'
			},
			success: function (response) {
				dataTable
					.row( selectedBtn.parents('tr') )
					.remove()
					.draw();
				toastr.success('正常に削除されました。', '',{timeOut: 2000});
			},
			error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/login')
					localStorage.clear();
				}
				toastr.error('もう一度お試しください。', '',{timeOut: 2000});
			}
		});
	};

	var copyToClipboard = (element) => {
		var $temp = $("<input>");
		$("body").append($temp);
		$temp.val($(element).text()).select();
		document.execCommand("copy");
		$temp.remove();
	}

</script>
<style>

	.table td,th{
	text-align: center;
	}
	table{
		table-layout: fixed;
	}
	.qr, th:hover{
		cursor: pointer;
		margin: 0px;
		padding-top: 0.8rem;
	}
	tr:nth-of-type(odd)>td{
	background: #F3F3F3;
	}
	tr:nth-of-type(even)>td, th:first-child , th:last-child {
	background: white;
	}
	td {
		overflow: auto;
		padding: 0px !important;
	}
	#iconDelete, #iconUpdate{
		padding: 5px;
	}
	td:nth-child(12) {
		max-width: 350px;
		min-width: 350px;
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

</style>
@stop