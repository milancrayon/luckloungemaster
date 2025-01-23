@php
$sidenav = json_decode($sidenav);

$settings = file_get_contents(resource_path('views/admin/setting/settings.json'));
$settings = json_decode($settings);

$routesData = [];
foreach (\Illuminate\Support\Facades\Route::getRoutes() as $route) {
$name = $route->getName();
if (strpos($name, 'master') !== false) {
$routeData = [
$name => url($route->uri()),
];

$routesData[] = $routeData;
}
}
@endphp

<!-- navbar-wrapper start -->
<nav class="navbar-wrapper bg--dark  d-flex flex-wrap">
    <div class="navbar__left">
        <button type="button" class="res-sidebar-open-btn me-3"><i class="las la-bars"></i></button>
        <form class="navbar-search">
            <input type="search" name="#0" class="navbar-search-field" id="searchInput" autocomplete="off"
                placeholder="@lang('Search here...')">
            <i class="las la-search"></i>
            <ul class="search-list"></ul>
        </form>
    </div>
    <div class="navbar__right">
        <ul class="navbar__action-list">
            <li>
                <button type="button" class="primary--layer" data-bs-toggle="tooltip" data-bs-placement="bottom" title="@lang('Visit Website')">
                    <a href="{{ route('home') }}" target="_blank"><i class="las la-globe"></i></a>
                </button>
            </li>

        </ul>
    </div>
</nav>
<!-- navbar-wrapper end -->

@push('script')
<script>
    "use strict";
    var routes = @json($routesData);
    var settingsData = Object.assign({}, @json($settings), @json($sidenav));

    $('.navbar__action-list .dropdown-menu').on('click', function(event) {
        event.stopPropagation();
    });
</script>
<script src="{{ asset('assets/master/js/search.js') }}"></script>
<script>
    "use strict";

    function getEmptyMessage() {
        return `<li class="text-muted">
                <div class="empty-search text-center">
                    <img src="{{ getImage('assets/images/empty_list.png') }}" alt="empty">
                    <p class="text-muted">No search result found</p>
                </div>
            </li>`
    }
</script>
@endpush