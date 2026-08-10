@props([
    'id' => '',
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'maxlength' => null,
    'value' => '',
    'required' => false,
])
<label for="{{ $id }}">{{ $label }}  @if($required)<span class="text-danger">*</span>@endif</label>
<textarea @if($maxlength) maxlength="{{ $maxlength }}" @endif {{ $attributes->merge(['class' => 'form-control text-area-5']) }} name="{{ $name }}" id="{{ $id }}" placeholder="{{ $placeholder }}">{{ $value }}</textarea>
@error($name)
    <span class="text-danger">{{ $message }}</span>
@enderror