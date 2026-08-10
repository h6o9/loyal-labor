<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
<link rel="stylesheet" href="{{ asset('backend/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-social.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/components.css') }}">
<link rel="stylesheet" href="{{ asset('backend/css/bootstrap-toggle.min.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/dev.css') }}">
@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link rel="stylesheet" href="{{ asset_ver('backend/css/rtl.css') }}">
    <link rel="stylesheet" href="{{ asset_ver('backend/css/dev_rtl.css') }}">
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
<link rel="stylesheet" href="{{ asset_ver('css/admin-sidebar-custom.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/admin-theme-orange.css') }}">
