@props([
    'id' => '',
    'name' => '',
    'label' => null,
    'type' => 'text',
    'value' => '',
    'required' => false,
    'title' => null,
])

@if ($label)
    <label for="{{ $id }}">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
        @if ($title)
            <span data-bs-toggle="tooltip" title="{{ $title }}">
                <i class="fas fa-info-circle text-info"></i>
            </span>
        @endif
    </label>
@endif

@if ($type === 'password')
    <div class="password-input-wrap position-relative">
        <input id="{{ $id }}" name="{{ $name }}" type="password" value="{{ $value }}"
            {{ $attributes->merge(['class' => 'form-control password-field-input']) }}>
        <button type="button" class="password-toggle-btn" tabindex="-1" aria-label="{{ __('Show password') }}">
            <i class="fas fa-eye-slash"></i>
        </button>
    </div>
@else
    <input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ $value }}"
        {{ $attributes->merge(['class' => 'form-control']) }}>
@endif

@error($name)
    <span class="text-danger">{{ $message }}</span>
@enderror
