<script>
    var base_url = "{{ url('/') }}";
    var isDemo = "{{ strtolower(config('app.app_mode')) }}";
    var demo_mode_error = "{{ __('In Demo Mode You Can Not Perform This Action') }}";
    var translation_success = "{{ __('Translated Successfully!') }}";
    var translation_processing = "{{ __('Translation Processing, please wait...') }}";
    var errorThrown = "{{ __('Error') }}";
    var translate_to = "{{ __('Translate to') }}";
    var basic_error_message = "{{ __('Something went wrong') }}";
</script>
