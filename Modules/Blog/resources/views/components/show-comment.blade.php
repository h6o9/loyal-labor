<li class="media">
    <div class="media-body">
        <div class="media-right">
            @if ($comment->status == 1)
                <div class="text-primary">{{ __('Approved') }}</div>
            @else
                <div class="text-warning">{{ __('Pending') }}</div>
            @endif
        </div>
        <div class="mb-1 media-title">{{ $comment->name }} @if ($comment?->is_admin)
                <small class="badge badge-info py-1">{{ __('Admin') }}</small>
            @endif
        </div>
        <div class="text-time">{{ $comment?->created_at?->diffForHumans() }}</div>
        <div class="media-description text-muted">
            {!! $comment->comment !!}
        </div>
        <div class="media-links">
            @adminCan('blog.comment.replay')
                <a href="javascript:;" title="Reply" data-id="{{ $comment->id }}" data-bs-toggle="modal"
                    data-bs-target="#post-reply" class="post-reply"><i class="fas fa-reply"></i></a>
            @endadminCan
            @adminCan('blog.comment.delete')
                <div class="bullet"></div>
                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal"
                    onclick="deleteData({{ $comment->id }})" class="text-danger">{{ __('Trash') }}</a>
            @endadminCan
        </div>
    </div>

    @if ($comment->children->isNotEmpty())
        <ul class="list-unstyled list-unstyled-border list-unstyled-noborder ps-3 mt-3">
            @foreach ($comment->children as $child)
                <x-blog::show-comment :comment="$child" />
            @endforeach
        </ul>
    @endif
</li>
