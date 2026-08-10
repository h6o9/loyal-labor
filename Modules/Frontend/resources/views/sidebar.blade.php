@if (Module::isEnabled('Frontend') && Route::has('admin.frontend.index'))
    @adminCan('frontend.view')
        <li class="{{ isRoute(['admin.frontend.index', 'admin.frontend.homepage'], 'active') }}">
            <a class="nav-link" href="{{ route('admin.frontend.index') }}">
                <i class="fas fa-paint-brush"></i>
                <span>{{ __('Manage Theme') }}</span>
            </a>
        </li>

        @php $pageSections = pagesSection(); @endphp

        @if ($pageSections->isNotEmpty())
            <li class="nav-item dropdown {{ isRoute('admin.frontend.section.index') ? 'active' : '' }}">
                <a class="nav-link has-dropdown" data-toggle="dropdown" href="#">
                    <i class="fas fa-file-alt"></i>
                    <span>{{ __('Manage Pages') }}</span>
                </a>
                <ul class="dropdown-menu">
                    @foreach ($pageSections as $section)
                        <li
                            class="{{ isRoute(['admin.frontend.section.index']) && request()->route('section') == $section->name ? 'active' : '' }}">
                            <a class="nav-link"
                                href="{{ route('admin.frontend.section.index', ['section' => $section->name]) }}">
                                {{ __($section->name ? str($section->name)->replace('_', ' ')->title()->toString() : 'Unknown Section') }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @endif
    @endadminCan
@endif
