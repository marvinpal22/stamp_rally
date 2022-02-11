@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-5 row mr-1">
		<button type="button" id="addUser" class="btn btn-success float-right ml-auto"><i class="fa fa-plus mr-1"></i>ユーザー追加</button>
	</div>
	<div >
		<table class="table table-bordered table-striped w-100 table-sm table-hover mb-5" id="userTable">
			<thead>
				<tr>
				<th scope="col" style="max-width: 2rem;">メール</th>
				<th scope="col">氏名</th>
				<th scope="col">携帯番号</th>
				<th scope="col">住所</th>
				<th scope="col" style="max-width: 250px; min-width: 250px;">ご感想など</th>
				<th scope="col" >スタンプ数</th>
				<th scope="col" >アクション</th>
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
						<h5 class="modal-title" id="exampleModalLabel">削除。</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						このレコードを削除してもよろしいですか？
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
						<button type="button" id="btnConfirm" class="btn btn-danger" data-dismiss="modal">確認</button>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<script>
	$( "#email" ).addClass( "btn-success rounded");

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {

		dataTable = $('#userTable').DataTable(dtOptions);
		pagination =$('#userTable_paginate')
		$('input[type="search"]').attr("class", "form-control");
		$('select').attr("class", "form-control form-control-sm");
		$('table').addClass( "table table-bordered table-striped table-sm w-100 position-responsive table-hover");
		console.log(pagination)

		$('#userTable tbody').on( 'click', 'i#iconDelete', function () {
			selectedBtn = $(this);
		});

		$('#userTable tbody').on( 'click', 'i#iconStore', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/stamp_rally/management/user/${rowId}/visited`);
		});

		$('span').on( 'click',  function () {
			// var rowId = $(this).attr('data-id');
			console.log('aa');
		});

		$("#addUser").on('click',function(){
			$(window).attr('location','/stamp_rally/management/user/add')
		});

		$("#btnConfirm").on('click',function(){
			deleteUser(selectedBtn.attr('data-id'));
		});
		$('.dataTables_length').addClass('bs-select');
	});

	var dtOptions = {
			ajax: {
				type: 'GET',
				url: '/stamp_rally/api/user',
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
			"order": [[ 6, "desc" ]],
			language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
            },
    		columns: [
				{data: 'email', class: 'align-middle'},
				{data: 'full_name', class: 'align-middle'},
				{data: 'contact_no', class: 'align-middle'},
				{data: 'address', class: 'align-middle'},
				{data: 'impressions', class: 'align-middle'},
				{data: 'stamps_collected', class: 'align-middle'},
				// {
				// 	data: 'id',
				// 	mRender: function (data, type, row) {
				// 		return '<button class="btn"  id="iconStore"'+data+'"><i class="fa fa-eye"></i></button>'
				// 	}
				// },
				{
					data: 'id',
					class: 'align-middle',
					mRender: function (data, type, row) {
						return '<i class="fas fa-store p-1" data-id="'+data+'" id="iconStore"></i><i id="iconDelete" data-toggle="modal" data-target="#exampleModal" data-id="'+data+'" class="fa fa-trash p-1"></i>'
					}
				}

			],
		}

	var deleteUser = id => {
			$.ajax({
				url: `/stamp_rally/api/user/${id}`,
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
					toastr.success('レコードが正常に削除されました。', '',{timeOut: 2000});
				},
				error: function(requestObject, error, errorThrown) {
					if(requestObject.status == 401) {
						$(window).attr('location','/stamp_rally/login')
						localStorage.clear();
					}
					toastr.error('エラー！ もう一度お試しください。', '',{timeOut: 2000});
				}
			});
		};

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
	.btn-success{
		border-radius: 100px;
	}

</style>
@stop