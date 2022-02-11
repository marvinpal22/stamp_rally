@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div >
		<table class="table w-100 table-sm table-hover mb-5" id="userTable">
			<thead>
				<tr>
				<th scope="col" style="max-width: 50px;">ID</th>
				<th scope="col" style="max-width: 100px;">題名</th>
				<th scope="col">メッセージ</th>
				{{-- <th scope="col" style="max-width: 1.5rem;">提出された</th> --}}
				<th scope="col"  style="max-width: 100px;">送信日</th>
				<th  style="max-width: 50px;">メッセージ</th>
				<th scope="col" style="max-width: 75px;">アクション</th>
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

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
	var selectedBtn, dataTable;

	$(document).ready( function () {
		$( "#notification").addClass( "btn-success rounded");

		dataTable = $('#userTable').DataTable(dtOptions);
		pagination =$('#userTable_paginate')
		$('table').addClass( "table table-striped table-sm w-100 position-responsive table-hover");

		$('#userTable tbody').on( 'click', 'i#iconDelete', function () {
			selectedBtn = $(this);
		});

		$('#userTable tbody').on( 'click', 'i#iconUpdate', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/stamp_rally/management/notification/${rowId}`);
		});

		$("#btnConfirm").on('click',function(){
			var $form = $('form'),
			data = `${$form.serialize()}`;

			deleteNotification(selectedBtn.attr('data-id'),data);
		});

		$('#userTable tbody').on( 'click', 'i.rotate', function () {
			$(this).toggleClass("down");
		});
	});

	 // Add event listener for opening and closing details
	 $('#userTable tbody').on('click', 'i.rotate', function () {
        var tr = $(this).closest('tr');
        var row = dataTable.row( tr );

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } );

	var dtOptions = {
			ajax: {
				type: 'GET',
				url: '/stamp_rally/api/notification',
				dataSrc: 'notifications',
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
			"order": [[ 0, "desc" ]],
			language: {
                url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
            },
    		columns: [
				{data: 'id', class: 'align-middle'},
				{data: 'author', class: 'align-middle'},
				{data: 'title', class: 'align-middle'},
				// {data: 'body', class: 'align-middle'},
				{
					data: 'created_at',
					class: 'align-middle',
					mRender: function (data, type, row) {
						return getDateTime(data)
					}
				},
				{
					data: 'body',
					class: 'align-middle',
					mRender: function (data, type, row) {
						return `<i class="fa fa-angle-down rotate" aria-hidden="true"></i>`
					}
				},
				{
					data: 'id',
					class: 'align-middle',
					mRender: function (data, type, row) {
						return '<i id="iconDelete" data-toggle="modal" data-target="#exampleModal" data-id="'+data+'" class="fa fa-trash p-1"></i><i id="iconUpdate" data-id="'+data+'" class="fas fa-pen-alt p-1"></i>'
					}
				},

			],
		}

	var deleteNotification = (id,data) => {
			$.ajax({
				url: `/stamp_rally/api/notification/${id}`,
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

		function format ( d ) {
			return '<p>'+d.body+'</p>';
		}

		var getDateTime = data => {
			var date = new Date(data);
			var fdate = `${dateNames[date.getMonth()]} ${date.getDate()}, ${date.getFullYear()}`;
			// var ftime = date.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric',second: 'numeric', hour12: true });

			return fdate ;
		}

		var dateNames = Date.prototype.monthNames = [
			"Jan", "Feb", "Mar",
			"Apr", "May", "Jun",
			"Jul", "Aug", "Sep",
			"Oct", "Nov", "Dec"
		];

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

	.rotate{
		-moz-transition: all 0.25s linear;
		-webkit-transition: all 0.25s linear;
		transition: all 0.25s linear;
	}

	.rotate.down{
		-ms-transform: rotate(180deg);
		-moz-transform: rotate(180deg);
		-webkit-transform: rotate(180deg);
		transform: rotate(180deg);
	}
	table {
		border: 1px solid #DEE2E6;
	}
</style>
@stop