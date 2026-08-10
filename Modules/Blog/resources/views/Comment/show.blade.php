@extends('admin.master_layout')
@section('title')
    <title>{{ __('Blog Comments') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('All Comments') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog Comments') => route('admin.blog-comment.index'),
                __('All Comments') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Blog Comments')" />
                                <div>
                                    <x-admin.back-button :href="route('admin.blog-comment.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder">
                                        @foreach ($comments as $comment)
                                            <x-blog::show-comment :comment="$comment" />
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="float-right">
                                    {{ $comments->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('blog.comment.replay')
        {{-- post-reply modal --}}
        <div class="modal fade" tabindex="-1" role="dialog" id="post-reply">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="{{ route('admin.blog-comment.reply') }}" method="post">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Reply to Comment') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="comment_id" id="comment_id">
                            <div class="form-group">
                                <x-admin.form-textarea id="reply" name="reply" label="{{ __('Reply') }}"
                                    placeholder="{{ __('Enter Reply') }}" value="{{ old('reply') }}" required />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <button type="submit" class="btn btn-primary">{{ __('Reply') }}</button>
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
        @adminCan('blog.comment.replay')
        $(document).ready(function() {
            $('.post-reply').on('click', function() {
                var id = $(this).data('id');
                $('#comment_id').val(id);
            });
        });
        @endadminCan
        @adminCan('blog.comment.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/blog-comment/') }}' + "/" + id)
        }
        @endadminCan
    </script>
@endpush
