@props([
    'code' => 'en',
])
<script>
    var isTranslatingInputs = true;

    function translateOneByOne(inputs, index = 0) {
        if (index >= inputs.length) {
            if (isTranslatingInputs) {
                isTranslatingInputs = false;
                translateAllTextarea();
            }
            $('#translate-btn').prop('disabled', false);
            $('#update-btn').prop('disabled', false);
            return;
        }

        var $input = $(inputs[index]);
        var inputValue = $input.val();

        if (inputValue) {
            $.ajax({
                url: "{{ route('admin.languages.update.single') }}",
                type: "POST",
                data: {
                    lang: '{{ $code }}',
                    text: inputValue,
                    _token: '{{ csrf_token() }}'
                },
                dataType: 'json',
                beforeSend: function() {
                    $input.prop('disabled', true);
                    iziToast.show({
                        timeout: false,
                        close: true,
                        theme: 'dark',
                        icon: 'loader',
                        iconUrl: 'https://hub.izmirnic.com/Files/Images/loading.gif',
                        title: "{{ __('Translation Processing, please wait...') }}",
                        position: 'center',
                    });
                },
                success: function(response) {
                    $input.val(response);
                    // check input is tinymce and set content
                    if ($input.hasClass('summernote')) {
                        console.log($input);
                        var inputId = $input.attr('id');
                        tinymce.get(inputId).setContent(response);
                    }
                    $input.prop('disabled', false);
                    iziToast.destroy();
                    toastr.success("{{ __('Translated Successfully!') }}");
                    translateOneByOne(inputs, index + 1);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus, errorThrown);
                    iziToast.destroy();
                    toastr.error('Error', 'Error');
                }
            });
        } else {
            translateOneByOne(inputs, index + 1);
        }
    }

    function translateAll() {
        iziToast.question({
            timeout: 20000,
            close: false,
            overlay: true,
            displayMode: 'once',
            id: 'question',
            zindex: 999,
            title: "{{ __('This will take a while!') }}",
            message: "{{ __('Are you sure?') }}",
            position: 'center',
            buttons: [
                ["<button title='{{ __('Yes') }}'><b>{{ __('YES') }}</b></button>", function(
                    instance, toast) {
                    var isDemo = "{{ env('PROJECT_MODE') ?? 1 }}";

                    if (isDemo == 0) {
                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');
                        toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                        return;
                    }

                    $('#translate-btn').prop('disabled', true);
                    $('#update-btn').prop('disabled', true);

                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');

                    var inputs = $('input[data-translate="true"]').toArray();
                    translateOneByOne(inputs);

                }, true],
                ["<button title='{{ __('No') }}'>{{ __('NO') }}</button>", function(instance,
                    toast) {

                    instance.hide({
                        transitionOut: 'fadeOut'
                    }, toast, 'button');

                }],
            ],
            onClosing: function(instance, toast, closedBy) {},
            onClosed: function(instance, toast, closedBy) {}
        });
    };

    function translateAllTextarea() {
        var inputs = $('textarea[data-translate="true"]').toArray();
        if (inputs.length === 0) {
            return;
        }
        translateOneByOne(inputs);
    }

    $(document).ready(function() {
        var selectedTranslation = $('#selected-language').text();
        var btnText = "{{ __('Translate to') }} " + selectedTranslation;
        $('#translate-btn').text(btnText);
        $('#translate-btn').on('click', function() {
            translateAll()
        });
    });
</script>
