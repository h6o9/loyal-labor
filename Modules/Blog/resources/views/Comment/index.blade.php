@extends('admin.master_layout')
@section('title')
    <title>{{ __('Blog Comments') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Blog Comments') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog Comments') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    @adminCan('blog.comment.update')
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body">
                                    <form onchange="$(this).trigger('submit')"
                                        action="{{ route('admin.update-general-setting') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <label class="d-flex align-items-center mb-0">
                                            <input class="custom-switch-input" name="comments_auto_approved" type="hidden"
                                                value="inactive">
                                            <input class="custom-switch-input" name="comments_auto_approved" type="checkbox"
                                                value="active"
                                                {{ $setting->comments_auto_approved == 'active' ? 'checked' : '' }}>
                                            <span class="custom-switch-indicator"></span>
                                            <span class="custom-switch-description">{{ __('Comments Auto Approved') }}</span>
                                        </label>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endadminCan
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Blog Comments')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped" id="blogCommentTable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Comment') }}</th>
                                                <th>{{ __('Post') }}</th>
                                                <th>{{ __('Author') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                @adminCan('blog.comment.update')
                                                    <th width="10%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('blog.comment.view') ||
                                                        checkAdminHasPermission('blog.comment.delete') ||
                                                        checkAdminHasPermission('blog.comment.replay'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('blog.comment.replay')
        <div class="modal fade" id="post-reply" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.blog-comment.reply') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Reply to Comment') }}</h5>
                            <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <input id="comment_id" name="comment_id" type="hidden">
                            <div class="form-group">
                                <x-admin.form-textarea id="reply" name="reply" value="{{ old('reply') }}"
                                    label="{{ __('Reply') }}" placeholder="{{ __('Enter Reply') }}" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-danger" data-bs-dismiss="modal" type="button">{{ __('Close') }}</button>
                            <button class="btn btn-primary" type="submit">{{ __('Reply') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endadminCan

    @adminCan('blog.comment.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        'use strict';

        $(document).ready(function() {
            var commentsTable = initServerDataTable('#blogCommentTable', "{{ route('admin.blog-comment.index') }}", [
                { data: 'comment_excerpt', name: 'comment' },
                { data: 'post_title', name: 'post.title', orderable: false, searchable: false },
                { data: 'author_name', name: 'name' },
                { data: 'email', name: 'email' },
                @adminCan('blog.comment.update')
                { data: 'status_toggle', name: 'status', orderable: false, searchable: false },
                @endadminCan
                @if (checkAdminHasPermission('blog.comment.view') ||
                        checkAdminHasPermission('blog.comment.delete') ||
                        checkAdminHasPermission('blog.comment.replay'))
                { data: 'action', name: 'action', orderable: false, searchable: false },
                @endif
            ], {
                drawCallback: function() {
                    // Toggles initialized by initSimpleAjaxTable via initBootstrapToggles
                }
            });

            $(document).on('click', '.post-reply', function() {
                var id = $(this).data('id');
                $('#comment_id').val(id);
            });
        });

        @adminCan('blog.comment.update')

        function changeStatus(id) {
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/blog-comment/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    if (err.responseJSON && err.responseJSON.message) {
                        toastr.error(err.responseJSON.message);
                    } else {
                        toastr.error("{{ __('Something went wrong, please try again') }}");
                    }
                }
            });
        }
        @endadminCan
    </script>
@endpush
