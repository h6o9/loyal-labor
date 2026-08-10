<script src="{{ asset('website/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('website/js/Font-Awesome.js') }}"></script>
<script src="{{ asset('website/js/select2.min.js') }}"></script>
<script src="{{ asset('website/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('website/js/slick.min.js') }}"></script>
<script src="{{ asset('website/js/simplyCountdown.js') }}"></script>
<script src="{{ asset('website/js/venobox.min.js') }}"></script>
<script src="{{ asset('website/js/jquery.countup.min.js') }}"></script>
<script src="{{ asset('website/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('website/js/range_slider.js') }}"></script>
<script src="{{ asset('website/js/wow.min.js') }}"></script>
<script src="{{ asset('backend/js/iziToast.min.js') }}"></script>
<script src="{{ asset_ver('backend/js/toast-common.js') }}"></script>
<script src="{{ asset('website/js/main.js') }}?v={{ $setting?->version }}"></script>

@if ($setting->recaptcha_status == 'active')
    <script src="https://www.google.com/recaptcha/api.js?render={{ getSettings('recaptcha_site_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const recaptchaSiteKey = "{{ $setting->recaptcha_site_key ?? '' }}";

            if (typeof grecaptcha === 'undefined' || !grecaptcha.ready) {
                toastr.error("reCAPTCHA failed to load. Please refresh the page.");
                return;
            }

            const buttons = document.querySelectorAll('.g-recaptcha-btn');

            buttons.forEach(button => {
                const form = button.closest('form');

                button.addEventListener('click', function(e) {
                    e.preventDefault();

                    grecaptcha.ready(function() {
                        grecaptcha.execute(button.dataset.sitekey, {
                                action: button.dataset.action
                            })
                            .then(function(token) {
                                const tokenInput = form.querySelector(
                                    '.recaptcha-token');

                                if (tokenInput) {
                                    tokenInput.value = token;
                                    form.submit();
                                } else {
                                    console.error(
                                        'Hidden input with class `.recaptcha-token` not found in form.'
                                    );
                                }
                            });
                    });
                });
            });
        });
    </script>
@endif

<script>
    @session('message')
    var type = "{{ Session::get('alert-type', 'info') }}"
    switch (type) {
        case 'info':
            toastr.info("{{ $value }}");
            break;
        case 'success':
            toastr.success("{{ $value }}");
            break;
        case 'warning':
            toastr.warning("{{ $value }}");
            break;
        case 'error':
            toastr.error("{{ $value }}");
            break;
    }
    @endsession
</script>

@if ($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

@if ($setting->cookie_status == 'active')
    <script src="{{ asset('website/js/cookieconsent.min.js') }}"></script>

    <script>
        window.addEventListener("load", function() {
            window.wpcc.init({
                "border": "{{ $setting->border }}",
                "corners": "{{ $setting->corners }}",
                "colors": {
                    "popup": {
                        "background": "{{ $setting->background_color }}",
                        "text": "{{ $setting->text_color }} !important",
                        "border": "{{ $setting->border_color }}"
                    },
                    "button": {
                        "background": "{{ $setting->btn_bg_color }}",
                        "text": "{{ $setting->btn_text_color }}"
                    }
                },
                "content": {
                    "href": "{{ route('website.privacy.policy') }}",
                    "message": "{{ $setting->message }}",
                    "link": "{{ $setting->link_text }}",
                    "button": "{{ $setting->btn_text }}"
                }
            })
        });
    </script>
@endif

@if (isset($customCode) && $customCode?->footer_javascript && filled($customCode->footer_javascript))
    <script>
        "use strict";

        {!! $customCode->footer_javascript !!}
    </script>
@endif
