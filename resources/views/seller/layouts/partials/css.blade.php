<link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/fontawesome/css/all.min.css') }}" rel="stylesheet">
<link href="{{ asset_ver('backend/css/style.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-social.css') }}" rel="stylesheet">
<link href="{{ asset_ver('backend/css/components.css') }}" rel="stylesheet">
<link rel="stylesheet" href="{{ asset_ver('backend/css/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset_ver('backend/css/datatable-common.css') }}">
<link href="{{ asset('backend/css/bootstrap-toggle.min.css') }}" rel="stylesheet">
<link href="{{ asset_ver('backend/css/dev.css') }}" rel="stylesheet">
@if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
    <link href="{{ asset_ver('backend/css/rtl.css') }}" rel="stylesheet">
    <link href="{{ asset_ver('backend/css/dev_rtl.css') }}" rel="stylesheet">
@endif
<link href="{{ asset('backend/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/tagify.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-tagsinput.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/fontawesome-iconpicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('backend/clockpicker/dist/bootstrap-clockpicker.css') }}" rel="stylesheet">
<link href="{{ asset('backend/datetimepicker/jquery.datetimepicker.css') }}" rel="stylesheet">
<link href="{{ asset('backend/css/iziToast.min.css') }}" rel="stylesheet">
<link href="{{ asset_ver('backend/css/toast-theme.css') }}" rel="stylesheet">
