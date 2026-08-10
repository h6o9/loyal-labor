<div class="wsus__blog_single_comment {{ $isReply ?? false ? 'single_comment_reply' : '' }}">
    <div class="img">
        <img class="img-fluid"
            src="{{ optional($comment->user)->image ? asset($comment->user->image) : asset($setting->default_user_image) }}"
            alt="{{ __('User Image') }}">
    </div>
    <div class="text">
        <h4>{{ $comment->name ?? 'Anonymous' }}</h4>
        <h6>{{ formattedDateTime($comment->created_at) }}</h6>
        <p>{{ $comment->comment }}</p>
    </div>
</div>

@if (!empty($comment->children) && count($comment->children))
    @foreach ($comment->children as $child)
        @include('website.blog.comment', ['comment' => $child, 'isReply' => true])
    @endforeach
@endif
