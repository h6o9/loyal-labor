<!DOCTYPE html>
<html lang="en">

    <head>
        <link type="image/x-icon" href="" rel="shortcut icon">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">

        @yield('title')

        <link href="{{ asset('backend/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/fontawesome/css/all.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/css/style.css') }}?v={{ $setting?->version }}" rel="stylesheet">
        <link href="{{ asset('backend/css/bootstrap-social.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/css/components.css') }}?v={{ $setting?->version }}" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('backend/css/iziToast.min.css') }}">
        <link rel="stylesheet" href="{{ asset_ver('backend/css/toast-theme.css') }}">
        <link href="{{ asset('backend/css/bootstrap4-toggle.min.css') }}" rel="stylesheet">
        <link href="{{ asset('backend/css/dev.css') }}?v={{ $setting?->version }}" rel="stylesheet">
        <style>
            :root { --loyal-primary: #FE7701; }
            .btn-primary, .btn-primary:hover, .btn-primary:focus, .btn-primary:active {
                background-color: var(--loyal-primary) !important;
                border-color: var(--loyal-primary) !important;
            }
            .card-primary .card-header {
                background-color: var(--loyal-primary) !important;
                color: #fff;
            }
            .text-primary, a.text-primary { color: var(--loyal-primary) !important; }
        </style>
        @if (session()->has('text_direction') && session()->get('text_direction') !== 'ltr')
            <link href="{{ asset('backend/css/rtl.css') }}?v={{ $setting?->version }}" rel="stylesheet">
            <link href="{{ asset('backend/css/dev_rtl.css') }}?v={{ $setting?->version }}" rel="stylesheet">
        @endif

        <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>

    </head>

    <body>
        <div id="app">
            @yield('content')
        </div>

        <script src="{{ asset('backend/js/popper.min.js') }}"></script>
        <script src="{{ asset('backend/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('backend/js/jquery.nicescroll.min.js') }}"></script>
        <script src="{{ asset('backend/js/moment.min.js') }}"></script>
        <script src="{{ asset('backend/js/stisla.js') }}"></script>
        <script src="{{ asset('backend/js/scripts.js') }}?v={{ $setting?->version }}"></script>
        <script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
        <script src="{{ asset_ver('backend/js/toast-common.js') }}"></script>

        <script>
            @if (Session::has('message'))
                var type = "{{ Session::get('alert-type', 'info') }}"
                switch (type) {
                    case 'info':
                        toastr.info("{{ Session::get('message') }}");
                        break;
                    case 'success':
                        toastr.success("{{ Session::get('message') }}");
                        break;
                    case 'warning':
                        toastr.warning("{{ Session::get('message') }}");
                        break;
                    case 'error':
                        toastr.error("{{ Session::get('message') }}");
                        break;
                }
            @endif
        </script>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <script>
                    toastr.error('{{ $error }}');
                </script>
            @endforeach
        @endif

    </body>

</html>
