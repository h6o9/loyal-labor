@props(['id' => '', 'modal_id' => '#deleteModal', 'onclick' => null, 'text' => null])
<a data-bs-toggle="modal" data-bs-target="{{ $modal_id }}" href="javascript:;"
    {{ $attributes->merge(['class' => 'btn btn-danger btn-sm']) }}
    @if ($onclick) onclick="{{ $onclick }}({{ $id }})" @endif>
    <i class="fa fa-trash" aria-hidden="true"></i> {{ $text ?? '' }}
</a>
