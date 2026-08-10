@extends('website.layouts.app')

@section('title')
    {{ __('Home') }} - {{ $setting->app_name }}
@endsection

@section('content')
    @include('website.homepages.' . config('services.theme'))

    @include('components::preloader')
    @include('components::product-modal')
@endsection

@push('scripts')
    <script>
        "use strict";

        $(window).on('load', function() {
            $('.flash-deal-countdown').each(function() {
                var endDateStr = $(this).data('endDate');
                var parts = endDateStr.split('T')[0].split('-');
                var year = parseInt(parts[0], 10);
                var month = parseInt(parts[1], 10);
                var day = parseInt(parts[2], 10);

                simplyCountdown(this, {
                    year: year,
                    month: month,
                    day: day,
                    enableUtc: true
                });
            });
        });
    </script>
@endpush

@if (getSettingStatus('googel_tag_status'))
    @push('gtm-data')
        <script>
            window.dataLayer.push({
                event: 'page_view',
                page_type: 'homepage',
                user_id: '{{ auth()->id() ?? 0 }}',
                user_role: '{{ auth()->check() ? auth()?->user()?->name : 'guest' }}',
                language: '{{ getSessionLanguage() }}'
            });
        </script>
    @endpush
@endif
