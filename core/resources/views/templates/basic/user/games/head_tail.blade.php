@extends($activeTemplate . 'layouts.master')
@section('content')
<section class="pt-120 pb-120 common_game_wrapper head_tail_wrapper">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="card-body h-100 middle-el">
                    <div class="alt"></div>
                    <div class="game-details-left">
                        <div class="game-details-left__body">
                            <div class="flp">
                                <div id="coin-flip-cont">
                                    <div class="flipcoin" id="coin">
                                        <div class="flpng coins-wrapper">
                                            <div class="front"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/head.png') }}"
                                                    alt=""></div>
                                            <div class="back"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/tail.png') }}"
                                                    alt=""></div>
                                        </div>
                                        <div class="headCoin d-none">
                                            <div class="front"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/head.png') }}"
                                                    alt=""></div>
                                            <div class="back"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/tail.png') }}"
                                                    alt=""></div>
                                        </div>
                                        <div class="tailCoin d-none">
                                            <div class="front"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/tail.png') }}"
                                                    alt=""></div>
                                            <div class="back"><img
                                                    src="{{ asset($activeTemplateTrue . 'images/play/head.png') }}"
                                                    alt=""></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="cd-ft"></div>
                    <h4 class="mobile_only">@lang('Your Current Balance ') <span
                            class="bal user-balance">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span>
                        {{ __(gs('cur_text')) }}</h4>
                </div>
            </div>
            <div class="col-md-6 mt-md-0 mt-5">
                <div class="game-details-right">
                    <form id="game" method="post">
                        @csrf
                        <h3 class="f-size--28 mb-4 text-center">@lang('Current Balance :') <span class="base--color">
                                <span
                                    class="bal">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span>
                                {{ __(gs('cur_text')) }}</span>
                        </h3>

                        <div class="d-flex d-md-block flex-wrap">
                            <div class="form-group">
                                <div class="input-group mb-3">
                                    <input class="form-control amount-field" name="invest" type="text"
                                        value="{{ old('invest') }}" placeholder="@lang('Enter amount')"
                                        autocomplete="off">
                                    <span class="input-group-text" id="basic-addon2">{{ __(gs('cur_text')) }}</span>
                                </div>
                                <small class="form-text text-muted"><i
                                        class="fas fa-info-circle mr-2"></i>@lang('Minimum')
                                    : {{ showAmount($game->min_limit) }} | @lang('Maximum')
                                    : {{ showAmount($game->max_limit) }} |
                                    <span class="text--warning">@lang('Win Amount')
                                        @if ($game->invest_back == 1)
                                            {{ getAmount($game->win + 100) }}
                                        @else
                                            {{ getAmount($game->win) }}
                                        @endif %
                                    </span>
                                </small>
                            </div>
                            <div class="form-group justify-content-center d-flex mt-5 game_part">
                                <div class="single-select head gmimg">
                                    <img src="{{ asset($activeTemplateTrue . '/images/play/head.png') }}"
                                        alt="game-image">
                                </div>
                                <div class="single-select tail gmimg">
                                    <img src="{{ asset($activeTemplateTrue . '/images/play/tail.png') }}"
                                        alt="game-image">
                                </div>
                                <input name="choose" type="hidden">
                            </div>


                            <div class="mt-5 text-center">
                                <button class="cmn-btn w-100 game text-center" id="flip"
                                    type="submit">@lang('Play Now')</button>
                                <a class="game-instruction mt-2" data-bs-toggle="modal"
                                    data-bs-target="#exampleModalCenter">@lang('Game Instruction') <i
                                        class="las la-info-circle"></i></a>
                            </div>
                            <div>
                    </form>
                </div>
            </div>
        </div>
        </div>
        <x-recent-game-log :mylogs=$mylogs :alllogs=$alllogs />
    </div>
</section>

<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle"
    aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content section--bg">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">@lang('Game Rule')</h5>
                <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php echo $game->instruction @endphp
            </div>
        </div>
    </div>
</div>
@endsection

@push('style-lib')
    <link href="{{ asset('assets/global/css/game/coinflipping.min.css') }}" rel="stylesheet">
@endpush

@push('script-lib')
    <script type="text/javascript" src="{{ asset('assets/global/js/game/coin.js') }}"></script>
@endpush

@push('script')
    <script>
        "use strict";
        let audio;
        $('#game').on('submit', function (e) {
            e.preventDefault();
            audio = new Audio(`{{ asset('assets/audio/coin.mp3') }}`);
            audio.play()
            $('.flipcoin').removeClass('animateClick');
            $('.flpng').removeClass('d-none');
            $('#coin .headCoin').addClass('d-none');
            $('#coin .tailCoin').addClass('d-none');
            $('#game .cmn-btn').html('<i class="la la-gear fa-spin"></i> Processing...');
            $('#game .cmn-btn').attr('disabled', true);
            var data = $(this).serialize();
            var url = "{{ route('user.play.game.invest', 'head_tail') }}";
            game(data, url);
        });

        function endGame(data) {
            var url = "{{ route('user.play.game.end', 'head_tail') }}";
            audio.pause()
            complete(data, url);
        }

        function playAudio(filename) {
            var audio = new Audio(`{{ asset('assets/audio') }}/${filename}`);
            audio.play();
        }
    </script>
@endpush