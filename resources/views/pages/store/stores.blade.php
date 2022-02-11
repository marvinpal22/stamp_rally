@extends('layouts.app')
@section('content')
<div class="container-fluid w-75">
	<div class="mb-5 row">
		<button type="button" id="addStore" class="btn btn-success float-right ml-auto"><i class="fa fa-plus mr-1"></i>参加者の追加</button>
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
					<th scope="col">ＱＲ<a id="downloadAll" class="text-dark" href="/stamp_rally/qrcodes"><i class="fa fa-download pl-3"></i></a></th>
					<th scope="col">アクション</th>
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

	{{-- <img src="{{asset('storage/14.png')}}" alt=""> --}}
</div>
<script>
	$( "#storeManagement" ).addClass( "btn-success rounded");

	var authorizationToken = "Bearer " + localStorage.getItem("user_token");
    var selectedBtn, dataTable, hasDisabled;
    var canUpdate = false, disabledOnly = false, activeOnly = false
    var disabledCtr = 0;

	$(document).ready( function () {

        dataTable = $('#storeTable').DataTable(dtOptions);

		$('table').addClass( "table table-bordered table-responsive table-sm w-100 position-relative");
		$('tbody tr:even').addClass('even');
		checkSched()

		$('#storeTable tbody').on( 'click', 'button#iconDisable', function () {
            selectedBtn = $(this);
            if(selectedBtn.find('i').hasClass('fa-plus-circle'))
                restoreStore(selectedBtn.attr('data-id'))
		});

		$('#storeTable tbody').on( 'click', 'button#iconEye', function () {
			var rowId = $(this).attr('data-id');
            $(window).attr('location',`/stamp_rally/management/stores/${rowId}/visited`);
		});

		$('#storeTable tbody').on( 'click', 'button#iconUpdate', function () {
			var rowId = $(this).attr('data-id');
			$(window).attr('location',`/stamp_rally/management/store/update/${rowId}`);
		});

		$("#addStore").on('click',function(){
			$(window).attr('location','/stamp_rally/management/store/add')
		});

		$("#btnConfirm").on('click',function(){
            data = {password:$('#password').val()}
            if(selectedBtn.find('i').hasClass('fa-minus-circle'))
                deleteStore(selectedBtn.attr('data-id'),data);
		});

		$('.dataTables_length').addClass('bs-select');

		$('#storeTable tbody').on( 'click', 'i#storeImage', function () {
			var src = $(this).attr("image-path");
            $(".modal-img").prop("src",src);
        });

        $.fn.dataTable.ext.search.push(
            function( settings, searchData, index, rowData, counter ) {
            if (disabledOnly && !rowData.deleted_at) {
                return false;
            }
            else if (activeOnly && rowData.deleted_at) {
                return false;
            }
            return true;
            }
        );

        // prevent submit via enter
        $('form input').keydown(function (e) {
            if (e.keyCode == 13) {
                var inputs = $(this).parents("form").eq(0).find(":input");
                if (inputs[inputs.index(this) + 1] != null) {
                    inputs[inputs.index(this) + 1].focus();
                }
                e.preventDefault();
                return false;
            }
        });
	});

	var dtOptions = {
		ajax: {
			type: 'GET',
			url: '/stamp_rally/api/stores',
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
			}
        },
        createdRow: function( row, data, dataIndex ) {
			if (!!data.deleted_at) {
                disabledCtr++
                $('#addStore').prop('disabled',true)
                $(row).addClass('disabled-row');
                $(row).find('.icon-btn').addClass('icon-disabled')
            }
			if(!canUpdate) {
				$(row).find('#iconCircle').hide();
				$(row).find('#iconUpdate').addClass('ml-1 mx-auto');
			}
        },
        initComplete: function(settings, json) {
			var position,width;
			console.log($('select'))

            if(canUpdate) {
                position = '81';
                width = '70';
            }
            else {
                position = '46';
                width = '35';
			}

			$('select').on('change', function() {
				$('td:last-child, th:last-child').attr('style',`right: 0%;min-width: ${width}px;`)
            	$('td:nth-child(12), th:nth-child(12)').attr('style',`right: ${position}px;min-width: 70px;`)
			});

			$('#storeTable').on( 'page.dt', function () {
				setTimeout(function() {
					$('td:last-child, th:last-child').attr('style',`right: 0%;min-width: ${width}px;`)
					$('td:nth-child(12), th:nth-child(12)').attr('style',`right: ${position}px;min-width: 70px;`)
				},50)
			});

            $('td:last-child, th:last-child').attr('style',`right: 0%;min-width: ${width}px;`)
            $('td:nth-child(12), th:nth-child(12)').attr('style',`right: ${position}px;min-width: 70px;`)

            $('#storeTable_filter ').before(`<div class="form-check mt-1 float-left"><input type="checkbox" onclick="uncheck(this,'disable')" class="form-check-input" id="showDisabled"><label class="form-check-label checkbox-label mr-5" >無効のみを表示</label></div>
            <div class="form-check mt-1 float-left"><input type="checkbox" onclick="uncheck(this,'active')" class="form-check-input" id="showEnabled"><label class="form-check-label checkbox-label" >アクティブに表示</label></div>`)
        },
		columnDefs: [
			{ "width": "7rem", "targets": [2,3,4,5,6,7,8,9,10,11,12] },
			{ "width": "5rem", "targets": 0 },
			{ "bSortable": false,"searchable": false, "targets": [1,11,12] },
			{ "bSortable": false,"searchable": false, "targets": [12] },
		],
		language: {
			url: '//cdn.datatables.net/plug-ins/1.10.21/i18n/Japanese.json'
		},
		"order": [[ 0, "asc" ]],
		columns: [
			{data: 'id', class: 'align-middle'},
			{
				data: 'image',
				class: 'align-middle',
				mRender: function (data, type, row) {
					return `<i href="#imagemodal" id="storeImage" class="icon-btn fa fa-eye" data-toggle="modal" data-target="#imagemodal" image-path="{{asset('public/storage')}}/${data}"></i>`
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
				data: 'qr_image',
				class: 'align-middle pt-2',
				mRender: function (data, type, row) {
                    var icon,rowClass = '';
                    if(!!!row.deleted_at)
                        icon = 'fa fa-minus-circle'
                    else
                    {
                        icon = 'fa fa-plus-circle'
                        rowClass = 'icon-disabled'
                    }
					return `<i href="#imagemodal" id="storeImage" class="icon-btn fa fa-eye p-1" data-toggle="modal" data-target="#imagemodal" image-path="{{asset('public/storage/qrCode')}}/${data}"></i><a href="{{asset('public/storage/qrCode')}}/${data}" class="icon-btn text-dark" download><i class="fa fa-download p-1"></i></a>`
				}
			},
			{
				data: 'id',
				class: 'align-middle',
				mRender: function (data, type, row) {
                    var icon,rowClass = '',deleteAttr ='';
                    if(!!!row.deleted_at)
                    {
                        icon = 'fa fa-minus-circle'
                        deleteAttr = 'data-toggle="modal" data-target="#exampleModal"'
                    }
                    else
                    {
                        icon = 'fa fa-plus-circle'
                        rowClass = 'icon-disabled'
                    }

                    return `<button class="btn icon-btn ${rowClass}" id="iconUpdate" data-id="${data}"><i class="fas fa-pen-alt"></i></button> <button class="btn"  id="iconDisable" ${deleteAttr} data-id="${data}"><i id="iconCircle" class="${icon}"></i></button>`
                }
			}

		],
	}

	var deleteStore = (id,data) => {
		$.ajax({
			url: `/stamp_rally/api/stores/${id}`,
			type: 'delete',
			data: data,
			headers: {
			'Authorization' : authorizationToken,
			'Accept' : 'application/json'
			},
			success: function (response) {
                // change deleted at so it wont show for filter
                dataTable
                    .row( selectedBtn.parents('tr') )
                    .data().deleted_at = 1;

                selectedBtn.parents('tr').addClass('disabled-row')

                $('#addStore').prop('disabled',true)
                toastr.success('レコードが正常に削除されました。', '',{timeOut: 3000});
                disabledCtr++;
                checkForDelete();
                rowStatus('disable')

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
        $('#password').val('');
	};

	var checkSched = () => {
		$.ajax({
			url: `/stamp_rally/api/schedule`,
			type: 'get',
			headers: {
			'Authorization' : authorizationToken,
			'Accept' : 'application/json'
			},
			success: function (response) {
                if(response.stamp.message == "available" || response.submission_form.message == "available")
                    canUpdate = false
                else
                    canUpdate = true
			},
			error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}
                toastr.error('エラー！ もう一度お試しください。', '',{timeOut: 3000});
			}
        });
    };

    var checkForDelete = () => {
        if(disabledCtr <= 0)
            $('#addStore').prop('disabled',false)
        else if(disabledCtr > 0)
            $('#addStore').prop('disabled',true)
    }

    var restoreStore = (id) => {
        $.ajax({
			url: `/stamp_rally/api/store/restore/${id}`,
			type: 'post',
			headers: {
			'Authorization' : authorizationToken,
			'Accept' : 'application/json'
			},
			success: function (response) {
                // change deleted at so it wont show for filter
                dataTable
                    .row( selectedBtn.parents('tr') )
                    .data().deleted_at = '';

                selectedBtn.parents('tr').removeClass('disabled-row')
                disabledCtr--;
                checkForDelete();
                rowStatus('restore')
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
    }

    //  detect check uncheck event
    var uncheck = (cb,type) => {
        if (type == 'disable') {
            disabledOnly = !disabledOnly;
			$("#showEnabled").prop("checked",false);
            activeOnly = false;
        }
        else if(type == 'active') {
            activeOnly = !activeOnly;
			$("#showDisabled").prop("checked",false);
            disabledOnly = false;
        }
        dataTable.draw();
    }

    var rowStatus = (type) => {
        var tr = selectedBtn.closest('tr')
        if(type == 'restore') {
            tr.removeClass('disabled-row')
            tr.find('#iconCircle').removeClass('fa fa-plus-circle').addClass('fa fa-minus-circle')
            tr.find('.icon-btn').removeClass('icon-disabled')
            tr.find('#iconDisable').attr('data-toggle','modal')
            tr.find('#iconDisable').attr('data-target','#exampleModal')
        }
        else if(type == 'disable') {
            tr.addClass('disabled-row')
            tr.find('#iconCircle').removeClass('fa fa-minus-circle').addClass('fa fa-plus-circle')
            tr.find('.icon-btn').addClass('icon-disabled')
            tr.find('#iconDisable').attr('data-toggle','')
            tr.find('#iconDisable').attr('data-target','')
        }
    }

</script>
<style id="style" lang="sass">
	.modal-img {
		width:100%;
	}

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
        background: #ffa5a3;
	}
	tr:nth-of-type(even)>td, th:first-child , th:last-child {
        background: #ffa5a3;
	}
    tr:nth-of-type(odd):not(.disabled-row)>td{
	    background: #F3F3F3;
	}
	tr:nth-of-type(even):not(.disabled-row)>td, th:first-child , th:last-child, th:nth-child(12) {
	    background: white;
	}
	td {
		overflow: auto;
		padding: 0px !important;
	}
	#iconDisable, #iconUpdate{
		padding: 5px;
	}
	td:first-child, th:first-child{
		margin: 0%;
		/* min-width: 50px !important; */
		left: 0%;
		position: sticky !important;
		border: 0px;\;
		z-index: 1;
		/* background: #F3F3F3; */
	}

	td:last-child, th:last-child{
		position: sticky !important;
		z-index: 1;
	}

    td:nth-child(12), th:nth-child(12){
		position: sticky !important;
		z-index: 1;
	}

	td {
		word-break: break-word;
	}
	li {
		padding: 0px !important;
		margin: 0px !important;
		border: 0px !important;
	}
	.btn-success{
		border-radius: 100px;
	}
	/* width */
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

/* The Close Button */
.close {
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

.icon-disabled {
  pointer-events: none;
  opacity: 0.6;
  cursor: not-allowed;
}
.disabled-row{
    color: rgba(0, 0, 0, 0.5);
}
</style>
@stop
