<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;"></a>
@if (trim($slot) === 'stamp_rally')
<!-- <img src="https://laravel.com/img/notification-logo.png" class="logo" alt="Laravel Logo"> -->
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
