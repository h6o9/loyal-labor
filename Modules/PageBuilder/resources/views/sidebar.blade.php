@if (Module::isEnabled('PageBuilder') && Route::has('admin.custom-pages.index'))
    <li class="{{ isRoute('admin.custom-pages.*', 'active') }}">
        <a class="nav-link" href="{{ route('admin.custom-pages.index') }}">
            <i class="fas fa-pager"></i> <span>{{ __('Custom Page') }}</span>
        </a>
    </li>
@endif
