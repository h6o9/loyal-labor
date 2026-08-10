<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/style.css') }}?v={{$setting?->version}}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/components.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/dev.css') }}?v={{$setting?->version}}">
@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link rel="stylesheet" href="{{ asset('backend/css/rtl.css') }}?v={{$setting?->version}}">
    <link rel="stylesheet" href="{{ asset('backend/css/dev_rtl.css') }}?v={{$setting?->version}}">
@endif
<link rel="stylesheet" href="{{ asset('backend/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/tagify.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-tagsinput.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/toast-theme.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/admin-theme-orange.css') }}">
