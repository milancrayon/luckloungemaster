@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-120 pb-120 common_game_wrapper">
        <div class="container">
            <div class="row number-slot-wrapper">
                <div class="col-md-6 number-slot-box">
                    <div class='machine position-relative'>
                        <div class='slots'>
                            <ul class='slot' id="slot1">
                                <li class='numbers'>0</li>
                                <li class='numbers'>1</li>
                                <li class='numbers'>2</li>
                                <li class='numbers'>3</li>
                                <li class='numbers'>4</li>
                                <li class='numbers'>5</li>
                                <li class='numbers'>6</li>
                                <li class='numbers'>7</li>
                                <li class='numbers'>8</li>
                                <li class='numbers'>9</li>
                            </ul>
                            <ul class='slot' id="slot2">
                                <li class='numbers'>0</li>
                                <li class='numbers'>1</li>
                                <li class='numbers'>2</li>
                                <li class='numbers'>3</li>
                                <li class='numbers'>4</li>
                                <li class='numbers'>5</li>
                                <li class='numbers'>6</li>
                                <li class='numbers'>7</li>
                                <li class='numbers'>8</li>
                                <li class='numbers'>9</li>
                            </ul>
                            <ul class='slot' id="slot3">
                                <li class='numbers'>0</li>
                                <li class='numbers'>1</li>
                                <li class='numbers'>2</li>
                                <li class='numbers'>3</li>
                                <li class='numbers'>4</li>
                                <li class='numbers'>5</li>
                                <li class='numbers'>6</li>
                                <li class='numbers'>7</li>
                                <li class='numbers'>8</li>
                                <li class='numbers'>9</li>
                            </ul>
                        </div>
                    </div>
                    <h4 class="mobile_only">@lang('Your Current Balance ') <span class="bal user-balance">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span> {{ __(gs('cur_text')) }}</h4>
                </div>
                <div class="col-md-6 number-slot-box number-slot-box-right mt-md-0 mt-5">
                    <div class="game-details-right h-100 d-flex align-items-center justify-content-center flex-wrap">
                        <form class="w-100" id="game" method="post">
                            @csrf
                            <h3 class="f-size--28 mb-4 text-center">@lang('Current Balance') : <span class="base--color"><span class="bal">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span> {{ __(gs('cur_text')) }}</span>
                            </h3>
                            <div class="d-flex d-md-block flex-wrap">
                                <div class="form-group">
                                    <div class="input-group mb-md-0  mb-3">
                                        <input class="form-control amount-field custom-amount-input" name="invest" type="text" placeholder="@lang('Enter Amount')" required autocomplete="off">
                                        <span class="input-group-text" id="basic-addon2">{{ __(gs('cur_text')) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                                        <small class="form-text text-muted mb-0"><i class="fas fa-info-circle mr-2"></i> @lang('Minimum')
                                            : {{ showAmount($game->min_limit) }} | @lang('Maximum')
                                            : {{ showAmount($game->max_limit) }} </small>
                                        <small class="form-text text-muted mb-3">@lang('Win Amount') : <span class="text--warning">@lang('Single') ({{ @$game->level[0] }}%)</span> | <span class="text--warning">@lang('Double') ({{ @$game->level[1] }}%)</span> | <span class="text--warning">@lang('Triple') ({{ @$game->level[2] }}%)</span></small>
                                    </div>
                                </div>
                                <div class="form-group game_part">
                                    <div class="input-group my-3">
                                        <input class="form-control amount-field custom-amount-input" name="choose" type="number" min="0" max="9" placeholder="@lang('Enter Number')" required>
                                    </div>
                                </div>
                                <div class="mt-3 text-center">
                                    <button class="cmn-btn w-100 text-center" type="submit">@lang('Play Now')</button>
                                    <a class="game-instruction mt-2" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">@lang('Game Instruction')
                                        <i class="las la-info-circle"></i>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <x-recent-game-log :mylogs=$mylogs :alllogs=$alllogs /> 
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="exampleModalCenter" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" tabindex="-1">
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
    <link href="{{ asset('assets/global/css/game/slot.css') }}" rel="stylesheet">
@endpush

@push('style')
    <style type="text/css">
        .gmimg {
            max-width: 30%;
            cursor: pointer;
            margin-top: 14px;
        }

        .none {
            display: none;
        }

        #game .row {
            margin-top: 18px;
        }

        .show {
            height: 100%;
            width: 100%;
            overflow-y: scroll;
            opacity: 1;
        }

        .hide {
            height: 0%;
            width: 0%;
            overflow-y: hidden;
            overflow-x: hidden;
            opacity: 0;
        }

        @media (max-width: 991px) {
            .game-details-left {
                height: 465px;
            }
        }

        @media (max-width: 800px) {
            .game-details-left {
                height: 400px;
            }
        }

        @media (max-width: 575px) {
            .game-details-left {
                height: 288px;
            }
        }

        @media (max-width: 425px) {
            .game-details-left {
                height: 220px;
            }
        }

        @media (max-width: 375px) {
            .game-details-left {
                height: 178px;
            }
        }
    </style>
@endpush
@push('script-lib')
    <script src="{{ asset('assets/global/js/game/slot.js') }}"></script>
@endpush
@push('script')
    <script>
        "use strict";
        let audio;
        $('input[name=invest]').keypress(function(e) {
            var character = String.fromCharCode(e.keyCode)
            var newValue = this.value + character;
            if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                e.preventDefault();
                return false;
            }
        });

        function hasDecimalPlace(value, x) {
            var pointIndex = value.indexOf('.');
            return pointIndex >= 0 && pointIndex < value.length - x;
        }

        $('#game').on('submit', function(e) {
            e.preventDefault();
            audio = new Audio(`{{ asset('assets/audio/number-slot.mp3') }}`)
            audio.play();
            $('button[type=submit]').html('<i class="la la-gear fa-spin"></i> Processing...');
            $('button[type=submit]').attr('disabled', '');
            $('.alert').remove();
            var data = $(this).serialize();
            var url = "{{ route('user.play.game.invest', 'number_slot') }}";
            game(data, url);
        });

        function endGame(data) {
            var url = "{{ route('user.play.game.end', 'number_slot') }}";
            audio.pause();
            complete(data, url);
        }

        function playAudio(filename) {
            var audio = new Audio(`{{ asset('assets/audio') }}/${filename}`);
            audio.play();
        }
    </script>
@endpush
