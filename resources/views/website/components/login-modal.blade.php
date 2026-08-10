<div class="wsus__product_modal popup_login chcekout_login_modal">
    <div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" aria-hidden="true"
        tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button class="btn-close" data-bs-dismiss="modal" type="button"
                        aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body p-0">
                    @include('components::login-form')
                </div>
            </div>
        </div>
    </div>
</div>
