{{-- =================================================== --}}
{{-- ========== Top menu items (ordered left) ========== --}}
{{-- =================================================== --}}
<ul class="nav navbar-nav d-md-down-none">

    @if (Auth::check())
        {{-- Topbar. Contains the left part --}}
        @include('inc.topbar_left_content')
    @endif

</ul>
{{-- ========== End of top menu left items ========== --}}



{{-- ========================================================= --}}
{{-- ========= Top menu right items (ordered right) ========== --}}
{{-- ========================================================= --}}
<ul class="nav navbar-nav ml-auto @if(config('backpack.base.html_direction') == 'rtl') mr-0 @endif">
    @if (Auth::guest())
        <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.login') }}">{{ trans('backpack::base.login') }}</a>
        </li>
        @if (config('backpack.base.registration_open'))
            <li class="nav-item"><a class="nav-link" href="{{ route('backpack.auth.register') }}">{{ trans('backpack::base.register') }}</a></li>
        @endif
    @else
        {{-- Topbar. Contains the right part --}}
        @include('inc.topbar_right_content')
        @include('inc.menu_user_dropdown')
    @endif
</ul>
{{-- ========== End of top menu right items ========== --}}
