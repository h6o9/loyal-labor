@props(['id' => 'update-btn'])
<button type="submit" id="{{$id}}" {{ $attributes->merge(['class' => 'btn btn-success']) }}><i class="fa fa-sync"></i> {{ $text }}</button>
