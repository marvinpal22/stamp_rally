@extends('layouts.app')
@section('content')
<div class="container mt-5 ">
	<table id="example" class="display" style="width:100%">
        <thead>
            <tr>
                <th>File Name</th>
                <th>Action</th>
        </thead>
        <tbody>
            <tr>
                <td>manifest.plist</td>
                <td><a href="itms-services://?action=download-manifest&url=https://ccnidev.xsrv.jp/stamp_rally/apps/manifest.plist" mimeType="application/octet-stream" mimeType="application/octet-stream">Download</a></td>
			</tr>
        </tbody>
	</table>
</div>
<script>
	var authToken = "Bearer " + localStorage.getItem("user_token");

	$('document').ready(function() {

	});


</script>
<style>
label {
	overflow: hidden;
}
.col{
	align-content: center !important;
}
.btn__Set {
	min-width: 100px;
}
.docs-date {
	cursor:pointer;
}
</style>
@stop
