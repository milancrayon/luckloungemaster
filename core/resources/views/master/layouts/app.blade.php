@extends('master.layouts.master')
@section('content')
    @php
        $sidenav = file_get_contents(resource_path('views/admin/partials/sidenav.json'));
    @endphp
    <!-- page-wrapper start -->
    <div class="page-wrapper default-version">
        @include('master.partials.sidenav')
        @include('master.partials.topnav')

        <div class="container-fluid px-3 px-sm-0">
            <div class="body-wrapper">
                <div class="bodywrapper__inner">

                    @stack('topBar')
                    @include('master.partials.breadcrumb')

                    @yield('panel')

                </div><!-- bodywrapper__inner end -->
            </div><!-- body-wrapper end -->
        </div>
    </div>
@endsection
