@php
$pages = App\Models\Page::where('tempname', $activeTemplate)
->where('is_default', Status::NO)
->get();

@endphp
<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl align-items-center p-0">
                <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ siteLogo() }}"
                        alt="site-logo"></a>
                <button class="navbar-toggler ml-auto" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="navbar-collapse collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav main-menu m-auto">
                        <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                        @foreach ($pages as $k => $data)
                        @if($k == 2)
                        <li><a href="{{ route('games') }}">@lang('Games')</a></li>
                        @endif
                        <li><a href="{{ route('pages', [$data->slug]) }}">{{ __($data->name) }}</a></li>
                        @endforeach
                        <li><a href="{{ route('blog') }}">@lang('Blog')</a></li>
                        <li><a href="{{ route('contact') }}">@lang('Contact')</a></li>

                    </ul>
                    <div class="nav-right">
                        @include($activeTemplate . 'partials.language')
                        @auth
                        <a href="{{ route('user.home') }}">
                            <i class="las la-tachometer-alt" data-bs-toggle="tooltip"
                                title="@lang('Dashboard')"></i>
                            <span>@lang('Dashboard')</span>
                        </a>
                        <a href="{{ route('user.logout') }}">
                            <i class="las la-sign-out-alt" data-bs-toggle="tooltip"
                                title="@lang('Logout')"></i>
                            <span>@lang('Logout')</span>
                        </a>
                        @else
                        <a href="{{ route('user.login') }}">
                            <i class="las la-sign-in-alt" data-bs-toggle="tooltip"
                                title="@lang('Login')"></i>
                            <span>@lang('Login')</span>
                        </a>
                        @endauth

                        @include($activeTemplate . 'partials.search')

                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>