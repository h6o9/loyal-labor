<div class="tab-pane fade" id="custom_pagination_tab" role="tabpanel">
    <form action="{{ route('admin.update-custom-pagination') }}" method="POST">
        @csrf
        @method('PUT')

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th width="50%">{{ __('Section Name') }}</th>
                    <th width="50%">{{ __('Quantity') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($custom_paginations as $custom_pagination)
                    <tr>
                        <td>{{ str($custom_pagination->section_name ?? 'Custom Pagination')->replace('_', ' ')->title()->toString() }}
                        </td>
                        <td>
                            <input class="form-control" name="quantities[]" type="number"
                                value="{{ $custom_pagination->item_qty }}">
                            <input name="ids[]" type="hidden" value="{{ $custom_pagination->id }}">
                        </td>
                    </tr>
                @endforeach
            </tbody>

        </table>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
