@php
$breadcrumb = getContent('breadcrumb.content', true);
@endphp
<section class="inner-hero bg_img"
    style="background-image: url( {{ getImage('assets/images/frontend/breadcrumb/' . @$breadcrumb->data_values->image, '1920x1080') }} );">
    <div class="container">
        <div class="row">
            <ul class="page-list justify-content-center">
                <li><a href="{{ route('games') }}">@lang('Games')</a></li>
                <li><a href="{{ route('home') }}">@lang('Home')</a></li>
                <li>{{ __($pageTitle) }}</li>
            </ul>
            <div class="col-lg-12 position-relative text-center">
                <h2 class="page-title">{{ __($pageTitle) }}</h2>
            </div>
        </div>
    </div>
</section>
<div class="custom_back_button_in_mobile d-none">
    <div class="container">
        <a href="{{ route('games') }}"><i class="las la-arrow-left"></i> @lang('Back')</a>
    </div>
</div>