@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-3 mr-1">
		<div class="row m-1">
			<button type="button" id="addUser" class="btn btn-top btn-success float-right ml-auto"><i class="fa fa-plus mr-1"></i>ユーザー追加</button>
		</div>
		<div class="row m-1">
			<button type="button" id="viewSubmission" class="btn btn-top bg-warning float-right pl-4 pr-4 ml-auto"><span class="pl-1 pr-1">提出された</span></button>
		</div>
	</div>
	<div >
		<table class="table table-bordered table-striped w-100 table-sm table-hover mb-5" id="userTable">
			<thead>
				<tr>
				<th scope="col" style="max-width: 1rem;">ID</th>
				<th scope="col">メール</th>
				<th scope="col">ユーザー名</th>
				<th scope="col" style="max-width: 1.5rem;">提出された</th>
				<th scope="col">スタンプ数</th>
				<th scope="col" style="max-width: 1rem;">アクション</th>
				</tr>
			</thead>
			<tbody>

			</tbody>
		</table>

		<!-- Modal -->
		<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog  modal-dialog-centered" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">削除。</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body text-danger">
						このレコードを削除してもよろしいですか？
					</div>
					<form autocomplete="off" class="pl-3 pr-3">
						<div class="form-group">
						  <input type="password" class="form-control" name="password" autocomplete="off" id="password" required>
						</div>
					</form>
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
	$( "#userManagement" ).addClass( "btn-success" );

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {

		dataTable = $('#userTable').DataTable(dtOptions);
		pagination =$('#userTable_paginate')
		$('table').addClass( "table table-bordered table-striped table-sm w-100 position-responsive table-hover");
		console.log(pagination)

		$('#userTable tbody').on( 'click', 'i#iconDelete', function () {
			selectedBtn = $(this);
		});

		$('#userTable tbody').on( 'click', 'i#iconStore', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/stamp_rally/management/user/${rowId}/visited`);
		});

		$('#viewSubmission').on( 'click',  function () {
			$(window).attr('location',`/stamp_rally/management/user/submitted`);
		});

		$("#addUser").on('click',function(){
			$(window).attr('location','/stamp_rally/management/user/add')
		});

		$("#btnConfirm").on('click',function(){
			var $form = $('form'),
			data = `${$form.serialize()}`;

			deleteUser(selectedBtn.attr('data-id'),data);
		});
		// $('.dataTables_length').addClass('bs-select');
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
			"order": [[ 4, "desc" ]],
			language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
            },
    		columns: [
				{data: 'id', class: 'align-middle'},
				{data: 'email', class: 'align-middle'},
				{data: 'username', class: 'align-middle'},
				{
					data: 'is_submit',
					class: 'align-middle',
					mRender: function (data, type, row) {
						if(data == 1)
							return '<i class="fa fa-check p-1" id="jicon">';
						else
							return '';
					}
				},
				{data: 'stamps_collected', class: 'align-middle'},
				{
					data: 'id',
					class: 'align-middle',
					mRender: function (data, type, row) {
						return '<i class="fas fa-store p-1" data-id="'+data+'" id="iconStore"></i><i id="iconDelete" data-toggle="modal" data-target="#exampleModal" data-id="'+data+'" class="fa fa-trash p-1"></i>'
					}
				}

			],
		}

	var deleteUser = (id,data) => {
			$.ajax({
				url: `/stamp_rally/api/user/${id}`,
				type: 'delete',
				data: data,
				headers: {
				'Authorization' : authorizationToken,
				'Accept' : 'application/json'
				},
				success: function (response) {
					dataTable
						.row( selectedBtn.parents('tr') )
						.remove()
						.draw();
					toastr.success('レコードが正常に削除されました。', '',{timeOut: 3000});
				},
				error: function(requestObject, error, errorThrown) {
					if(requestObject.status == 401) {
						$(window).attr('location','/stamp_rally/login')
						localStorage.clear();
					}
					else if(requestObject.status == 422)
						toastr.error('誤ったパスワード。', '',{timeOut: 3000});
					else
						toastr.error('エラー！ もう一度お試しください。', '',{timeOut: 3000});
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
	.btn-top{
		border-radius: 100px;
	}

</style>
@stop