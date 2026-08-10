@if ($setting->google_analytic_status == 'active')
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->google_analytic_id }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());
        gtag('config', '{{ $setting->google_analytic_id }}');
    </script>
@endif

@if (getSettingStatus('googel_tag_status'))
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', '{{ $setting?->googel_tag_id }}');
    </script>
    <!-- End Google Tag Manager -->
@endif

@if (getSettingStatus('pixel_status'))
    <script>
        ! function(f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function() {
                n.callMethod ?
                    n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
        }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '{{ $setting->pixel_app_id }}');
        fbq('track', 'PageView');
    </script>

    @if (session('pixel_payload') && getSettingStatus('marketing_status', 'int'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                if (typeof fbq === 'function') {
                    fbq('track', '{{ session('pixel_payload.event') }}',
                        {!! json_encode(session('pixel_payload.data')) !!});
                }
            });
        </script>
    @endif

    <noscript>
        <img src="https://www.facebook.com/tr?id={{ $setting->pixel_app_id }}&ev=PageView&noscript=1" style="display:none"
            height="1" width="1" />
    </noscript>
@endif

@if ($setting->tawk_status == 'active')
    <script type="text/javascript">
        var Tawk_API = Tawk_API || {},
            Tawk_LoadStart = new Date();
        (function() {
            var s1 = document.createElement("script"),
                s0 = document.getElementsByTagName("script")[0];
            s1.async = true;
            s1.src = '{{ $setting->tawk_chat_link }}';
            s1.charset = 'UTF-8';
            s1.setAttribute('crossorigin', '*');
            s0.parentNode.insertBefore(s1, s0);
        })();
    </script>
@endif
