<div class="tab-pane fade" id="search-engine-crawling" role="tabpanel">
    <form action="{{ route('admin.update-general-setting') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-switch name="search_engine_indexing" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$setting?->search_engine_indexing == 'active'" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
