@php
    $content = getContent('referral.content', true);
@endphp
<section class="pt-120">
    <div class="container">
        <div class="row justify-content-between align-items-center">
            <div class="col-lg-5 wow fadeInLeft" data-wow-duration="0.5s" data-wow-delay="0.3s">
                <div class="referral-thumb">
                    <img src="{{ asset(getImage('assets/images/frontend/referral/' . @$content->data_values->image, '490x390')) }}"
                        alt="image">
                </div>
            </div>
            <div class="col-lg-6 mt-lg-0 mt-5 wow fadeInRight" data-wow-duration="0.5s" data-wow-delay="0.5s">
                <div class="referral-content">
                    <h2 class="mb-3">{{ __(@$content->data_values->heading) }}</h2>
                    <p>
                        @php echo @$content->data_values->description @endphp
                    </p>
                    <div class="btn-group justify-content-lg-start justify-content-center wow fadeInUp mt-4"
                        data-wow-duration="0.5s" data-wow-delay="0.9s">
                        <a class="cmn-btn"
                            href="{{ __(@$content->data_values->button_url_one) }}">{{ __(@$content->data_values->button_one) }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>