<div class="modal fade" id="add-label" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <form action="{{ route('admin.label.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Add New Label') }}</h5>
                    <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="name">{{ __('Name') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="name" name="name" type="text"
                                    value="{{ old('name') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="slug">{{ __('Slug') }} <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="slug" name="slug" type="text"
                                    value="{{ old('slug') }}" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control" id="status" name="status" required>
                                    <option value="1">{{ __('Active') }}</option>
                                    <option value="0">{{ __('Inactive') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" data-bs-dismiss="modal"
                            type="button">{{ __('Close') }}</button>
                        <button class="btn btn-success" type="submit">{{ __('Save') }}</button>
                    </div>
                </div>
        </form>
    </div>
</div>
