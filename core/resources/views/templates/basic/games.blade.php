@extends($activeTemplate . 'layouts.frontend')
@section('content')

<section class="pt-120 pb-120 section--bg">
    <div class="container">
        <div class="row justify-content-center mb-none-30">
            @foreach ($games as $game)
                        <div class="col-xl-3 col-lg-4 col-sm-6 mb-30 wow fadeInUp" data-wow-duration="0.5s" data-wow-delay="0.3s">
                            <div class="game-card">
                                <div class="game-card__thumb">
                                    <img src="{{ getImage(getFilePath('game') . '/' . $game->image, getFileSize('game')) }}"
                                        alt="image">
                                </div>
                                <div class="game-card__content">
                                    <h4 class="game-name">{{ __($game->name) }}</h4>
                                    <a class="cmn-btn d-block btn-sm btn--capsule mt-3 text-center"
                                        href="{{ route('user.play.game', $game->alias) }}">@lang('Play Now')</a>
                                    <p class="mt-1">
                                    <img src="/assets/images/live.gif" alt="online" width="20px" height="20px">
                                        <strong>
                                            @php
                                                echo mt_rand(0, 1000);
                                            @endphp
                                        </strong> playing</p>
                                </div>
                            </div>
                        </div>
            @endforeach
        </div>
    </div>
</section>

@if ($sections->secs != null)
    @foreach (json_decode($sections->secs) as $sec)
        @include($activeTemplate . 'sections.' . $sec)
    @endforeach
@endif
@endsection