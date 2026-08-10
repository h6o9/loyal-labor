@props(['id' => 'deleteModal'])
<div id="{{ $id }}" role="dialog" tabindex="-1" {{ $attributes->merge(['class' => 'modal fade']) }}>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Item Delete Confirmation') }}</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
            </div>
            <div class="modal-body">
                <p id="deleteModalText">{{ __('Are You sure want to delete this item ?') }}</p>
            </div>
            <div class="modal-footer bg-whitesmoke br">
                <form id="deleteForm" action="" method="POST">
                    @csrf
                    @method('DELETE')
                    <x-admin.button data-bs-dismiss="modal" variant="danger" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                </form>
            </div>
        </div>
    </div>
</div>
