@extends($activeTemplate . 'layouts.app')
@section('app') 
    
    @include($activeTemplate . 'partials.header')
    @include($activeTemplate . 'partials.breadcrumb')
    <main class="home-page">
        @include($activeTemplate . 'partials.category')
        <div class="sports-body">
            <div class="container-fluid">
                <div class="row g-3">
                    @yield('bet')

                </div>
            </div>
        </div>
        @include($activeTemplate . 'partials.bet_slip')
        @include($activeTemplate . 'partials.mobile_menu')
    </main>
        
    @include($activeTemplate . 'partials.footer')

@endsection
