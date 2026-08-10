@if ($paginator->hasPages())
    <div class="wsus__pagination mt_50 wow fadeInUp">
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:void(0)" aria-label="@lang('pagination.previous')">
                            <i class="far fa-arrow-left"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" aria-label="@lang('pagination.previous')">
                            <i class="far fa-arrow-left"></i>
                        </a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li class="page-item disabled"><a class="page-link"
                                href="javascript:void(0)">{{ $element }}</a></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li class="page-item active"><a class="page-link"
                                        href="javascript:void(0)">{{ $page }}</a></li>
                            @else
                                <li class="page-item"><a class="page-link"
                                        href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" aria-label="@lang('pagination.next')">
                            <i class="far fa-arrow-right"></i>
                        </a>
                    </li>
                @else
                    <li class="page-item disabled">
                        <a class="page-link" href="javascript:void(0)" aria-label="@lang('pagination.next')">
                            <i class="far fa-arrow-right"></i>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
@endif
