@component('mail::layout')
{{-- Header --}}
@slot('header')

<style>

</style>
<!-- https://stamprally.000webhostapp.com/storage/logo/logo.png -->

<!-- <img src="https://stamprally.000webhostapp.com/storage/logo/logo.png" alt=""> -->
<!-- {{ asset('storage/logo/stamprally.png') }} -->
<!-- {{ config('Stamp Rally') }} -->
<a href="http://genki230project.jp" class="mt-0">
    <div style=" padding-bottom: 15px;text-align: center;">
        <div>
            <img src="https://ccnidev.xsrv.jp/stamp_rally/storage/app/public/logo/logo.png" height="80"  alt="">
        </div>
        <div>
            <img src="https://ccnidev.xsrv.jp/stamp_rally/storage/app/public/logo/stamprally.png" height="16" alt="">
        </div>
    </div>
</a>


@endslot

{{-- Body --}}
{{ $slot }}

{{-- Subcopy --}}
@isset($subcopy)
@slot('subcopy')
@component('mail::subcopy')
{{ $subcopy }}
@endcomponent
@endslot
@endisset

{{-- Footer --}}
@slot('footer')
@component('mail::footer')
© {{ date('Y') }} {{'Stamp Rally' }}. @lang('All rights reserved.')
@endcomponent
@endslot
@endcomponent
