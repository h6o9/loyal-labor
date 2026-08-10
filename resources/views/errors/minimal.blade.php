<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>@yield('title')</title>
        <link href="{{ asset('website/css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('website/css/style.css') }}?v={{ $setting?->version }}" rel="stylesheet">
        <link href="{{ asset('website/css/responsive.css') }}?v={{ $setting?->version }}" rel="stylesheet">
    </head>

    <body>
        <section class="custom_errors mt_95 xs_mt_75 mb_115 xs_mb_95">
            <div class="container">
                <div class="row justify-content-center wow fadeInUp">
                    <div class="col-md-8 col-lg-6 col-xxl-5 text-center">
                        <div class="custom_errors_text">
                            <h1>
                                @yield('code')
                            </h1>
                            <h4>
                                @yield('message')
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </section>

        <script src="{{ asset('website/js/bootstrap.bundle.min.js') }}"></script>

    </body>

</html>
