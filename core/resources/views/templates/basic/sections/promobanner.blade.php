@php
    $banner = getContent('promobanner.content', true);
@endphp
<section class="hero bg_img" style="background-image: url( {{ getImage('assets/images/frontend/promobanner/' . @$banner->data_values->image, '1920x780') }} ); padding-top:30px;">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-6">
                <div class="hero__content text-lg-left">
                    <h2 style="font-size:55px;color:#fff;" class="hero__title wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">{{ __(@$banner->data_values->heading) }}</h2>
                    <p style="margin-top:0px;" class="wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.5s">{{ __(@$banner->data_values->subheading) }}</p>
                    @guest
                        <div style="margin-top:10px !important;" class="btn-group justify-content-lg-start justify-content-center wow fadeInUp mt-4" data-wow-duration="0.5s" data-wow-delay="0.9s">
                            <a class="cmn-btn" href="{{ __(@$banner->data_values->button_url_one) }}">{{ __(@$banner->data_values->button_one) }}</a>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </div>
</section>
