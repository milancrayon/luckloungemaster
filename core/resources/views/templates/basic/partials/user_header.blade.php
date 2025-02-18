<header class="header">
    <div class="header__bottom">
        <div class="container">
            <nav class="navbar navbar-expand-xl align-items-center p-0">
                <a class="site-logo site-title" href="{{ route('home') }}"><img src="{{ siteLogo() }}"
                        alt="site-logo"><span class="logo-icon"><i class="flaticon-fire"></i></span></a>
                <button class="navbar-toggler ml-auto" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" type="button" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="menu-toggle"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    @auth
                        <ul class="navbar-nav main-menu m-auto"> 
                            <li><a href="{{ route('user.referrals') }}">@lang('Referrals')</a></li>
                            <li class="menu_has_children">
                                <a href="#">@lang('Reports')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('user.game.log') }}">@lang('Game Log')</a></li>
                                    <li><a href="{{ route('user.commission.log') }}">@lang('Commission Log')</a></li>
                                    <li><a href="{{ route('user.transactions') }}">@lang('Transactions')</a></li>
                                </ul>
                            </li>
                            <li class="menu_has_children">
                                <a href="#">@lang('Support')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('ticket.open') }}">@lang('Open New Ticket')</a></li>
                                    <li><a href="{{ route('ticket.index') }}">@lang('My Tickets')</a></li>
                                </ul>
                            </li>
                            <li class="menu_has_children">
                                <a href="#">@lang('Account')</a>
                                <ul class="sub-menu">
                                    <li><a href="{{ route('user.profile.setting') }}">@lang('Profile Setting')</a></li>
                                    <li><a href="{{ route('user.change.password') }}">@lang('Change Password')</a></li>
                                    <li><a href="{{ route('user.twofactor') }}">@lang('2FA Security')</a></li>
                                </ul>
                            </li>
                        </ul>
                    @endauth

                    <div class="nav-right">
                        @include($activeTemplate . 'partials.language')
                        <a href="{{ route('user.home') }}">
                            <i class="las la-tachometer-alt" data-bs-toggle="tooltip" title="@lang('Dashboard')"></i>
                            <span>@lang('Dashboard')</span>
                        </a>
                        <a href="{{ route('user.logout') }}">
                            <i class="las la-sign-out-alt" data-bs-toggle="tooltip" title="@lang('Logout')"></i>
                            <span>@lang('Logout')</span>
                        </a>

                        @include($activeTemplate . 'partials.search')
                    </div>
                </div>
            </nav>
        </div>
    </div>
</header>

@push('style')
    <style>
        .nav-right .langSel {
            padding: 7px 20px;
            height: 37px;
        }
    </style>
@endpush