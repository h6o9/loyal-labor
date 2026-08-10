<section class="wsus__breadcrumbs" style="background: url({{ asset($setting->breadcrumb_image) }});">
    <div class="wsus__breadcrumbs_overly">
        <div class="container">
            <div class="row">
                <div class="col-12 wow fadeInUp">
                    <div class="wsus__breadcrumb_text">
                        <h1>{{ $title }}</h1>
                        <ul>
                            <li>
                                <a href="{{ route('website.home') }}">
                                    <i class="fas fa-home-lg"></i>{{ __('Home') }}
                                </a>
                            </li>
                            <li>{{ $title }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
