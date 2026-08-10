<link href="{{ asset('website/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/nice-select.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/slick.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/animate.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/venobox.min.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/range_slider.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/sticky_menu.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/spacing.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset_ver('backend/css/toast-theme.css') }}" rel="stylesheet">
<link href="{{ asset('website/css/style.css') }}?v={{ $setting?->version }}" rel="stylesheet">
<link href="{{ asset('website/css/responsive.css') }}?v={{ $setting?->version }}" rel="stylesheet">
<link href="{{ asset('website/css/dev.css') }}?v={{ $setting?->version }}" rel="stylesheet">

@if (session()->get('text_direction', 'ltr') == 'rtl')
    <link href="{{ asset('website/css/rtl.css') }}?v={{ $setting?->version }}" rel="stylesheet">
@endif

@if (customCode() && filled(customCode()?->css))
    <style>
        {!! customCode()->css !!}
    </style>
@endif
