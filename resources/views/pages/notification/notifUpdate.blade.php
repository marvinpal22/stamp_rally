@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<div class="card rounded shadow">
		<div class="card-header">
			<h5 class="mx-auto my-auto text-center" style="max-width: 10.2rem;">
				<span class="mx-auto font-weight-bold ">通知を送信する</span>
			</h5>
		</div>
        {{-- <div class="input-group">
            <input type="file" class="form-control" aria-label="...">
            <div class="input-group-btn">
                <button type="submit" id="btnSubmit" class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing Order">送信</button>
            </div>
          </div> --}}
		<div class="card-body">
			<form id="sendNotif" class="mr-5 ml-5" enctype="multipart/form-data">
				<div class="form-group">
				  <input type="text" class="form-control" name="title" id="title" maxlength="255" placeholder="題名" required autofocus>
				</div>
				{{-- <div class="form-group">
					<div style="max-width: 300px;" class=" custom-file flex">
						<input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.bmp" id="image" lang="es">
                        <label class="custom-file-label " for="customFile" id="customFileLabel">画像を選択</label>
                    </div>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" type="button" id="inputGroupFileAddon04">Button</button>
                      </div>
                </div> --}}
                <div class="form-group">
                    <div class="input-group">
                        <div class="custom-file" style="max-width: 300px;">
                            <input type="file" class="custom-file-input" name="image" accept=".png,.jpg,.jpeg,.bmp" id="image" lang="es">
                            <label class="custom-file-label " for="customFile" id="customFileLabel">イメージの変更</label>
                        </div>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="viewBtn"  data-toggle="modal" data-target="#imagemodal">ビュー</button>
                            <button class="btn btn-outline-secondary" type="button" id="removeBtn">削除する</button>
                        </div>
                    </div>
                </div>
				<div class="form-group">
					<textarea class="form-control" name="body" id="body" rows="7" minlength="5" maxlength="15000" placeholder="メッセージ..."  required></textarea>
				</div>
				<div class="form-group float-right">
					<button type="submit" id="btnSubmit" class="btn btn-success" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing Order">保存</button>
				</div>
			</form>
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
</div>
{{-- <i class="fa fa-trash"></i><i class="fa fa-eye p-1" ></i> --}}
<script>

	var authToken = "Bearer " + localStorage.getItem("user_token");
    var isRemoved = false;
    var imageLink = '';
    // $custom-file-text: (
    //     en: "Browse",
    //     es: "Elegir"
    // );

	$(document).ready(function() {
        $( "#notification").addClass( "btn-success rounded");
        getNotif(getParamsId());
        // $('#image').fileinput({
        //     'showUpload': false
        // });
        console.log()
		$( "#sendNotif" ).submit(function( event ) {
			event.preventDefault();

		});

		$('input[type="file"]').change(function(e){
			var fileName = e.target.files[0].name;
			$('.custom-file-label').html(fileName);
		});

		$('#btnSubmit').on('click', function() {
            updateNotif(formDataFiller(),getParamsId())
        });

        $('#removeBtn').on('click', function() {
            isRemoved = true
            imageLink = ''
            $('#viewBtn').addClass('d-none')
        });

        $('#viewBtn').on( 'click', function () {
			$(".modal-img").prop("src",imageLink);
        });
	})

	var getNotif = (id) => {
		$.ajax({
            url: `/stamp_rally/api/notification/${id}`,
			type: "get",
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
				toastr.success('通知が送信されました。','',{timeOut: 3000});
				$('#title').val(response.title);
				$('#body').val(response.body);
				$('#customFileLabel').text('画像を選択');
                imageLink = response.image;
                if(response.image == '') {
                    $('#viewBtn').addClass('d-none')
                    $('#customFileLabel').text('画像を追加');
                }
            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}
				if(requestObject.status === 404)
					$(window).attr('location','/stamp_rally/management/notifications');
            }
        });
    }

    var updateNotif = (data,id) => {
		$.ajax({
            url: `/stamp_rally/api/notification/update/${id}?_method=PATCH`,
			type: "post",
			data: data,
			processData: false,
    		contentType: false,
            headers: {
				'Authorization' : authToken,
				'Accept' : 'application/json'
			},
            success: function (response) {
                $(window).attr('location','/management/notifications');
            },
            error: function(requestObject, error, errorThrown) {
				if(requestObject.status == 401) {
					$(window).attr('location','/stamp_rally/login')
					localStorage.clear();
				}
				toastr.error('エラー！ もう一度お試しください.','',{timeOut: 3000});
            }
        });
    }

	var formDataFiller = () => {
		var fd = new FormData();
		var image = (isRemoved) ? '' : $('#image')[0].files[0];
		var title = $('#title').val();
		var body = $('#body').val();

		fd.append('title',title);
		fd.append('body',body);
		fd.append('image',image);

		return fd;
	}

	// get Id from Url
	var getParamsId = () => {
		var url = window.location.pathname;
		var urlArr = url.split('/');
		return urlArr[urlArr.length-1];
	}


</script>
<style>
    /* .modal-dialog {
        max-width: 600px;
    } */
    .modal-img {
		width: 100%;
        max-height: 800px;
	}
    label {
        overflow: hidden;
    }

    html, body {
        height: 100%;
    }
    body {
        background-color: #F8F9FA;
    }
</style>
@stop
