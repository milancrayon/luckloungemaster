@extends($activeTemplate . 'layouts.master')
@section('content')
<section class="pt-120 pb-120 common_game_wrapper head_tail_wrapper">
    <div class="container">
        <div class="load-txt">
            <div class="loading-game-1">
                <div class="center-loading text-white text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">
                        <g fill="#E50539" fill-rule="nonzero">
                            <path
                                d="M67.785 67.77a10.882 10.882 0 0 0 2.995-5.502l18.37-6.36c.47-.163.876-.471 1.16-.88l29.263-42.18a2.343 2.343 0 0 0-.268-2.993L110.153.704a2.344 2.344 0 0 0-3.314 0L95.73 11.813C71.965-5.861 38.683-3.514 17.58 17.588a60.26 60.26 0 0 0-8.829 11.21 2.343 2.343 0 0 0 4.001 2.441 55.575 55.575 0 0 1 8.142-10.336C40.184 1.613 70.512-.68 92.378 15.165l-5.72 5.72c-8.742-5.967-19.302-8.837-29.947-8.1a47.31 47.31 0 0 0-30.183 13.751 47.722 47.722 0 0 0-5.92 7.207 2.344 2.344 0 0 0 3.897 2.605 42.996 42.996 0 0 1 5.337-6.497c14.233-14.234 36.774-16.445 53.436-5.586l-6.818 6.818a33.418 33.418 0 0 0-19.773-4.186A33.338 33.338 0 0 0 36.47 36.48a2.344 2.344 0 0 0 3.314 3.314c8.787-8.786 22.336-10.795 33.215-5.248L58.38 49.163a10.969 10.969 0 0 0-6.164 3.084 10.882 10.882 0 0 0-2.996 5.504l-18.37 6.36c-.47.163-.876.47-1.159.879L.427 107.17a2.343 2.343 0 0 0 .268 2.992l9.152 9.151a2.337 2.337 0 0 0 1.657.687c.6 0 1.2-.23 1.657-.687l11.109-11.109A59.835 59.835 0 0 0 59.99 120a59.873 59.873 0 0 0 42.43-17.571 60.476 60.476 0 0 0 7.162-8.63 2.343 2.343 0 1 0-3.87-2.643 55.793 55.793 0 0 1-6.606 7.959c-19.321 19.32-49.61 21.598-71.487 5.74l5.722-5.723a47.325 47.325 0 0 0 30.058 8.092A47.318 47.318 0 0 0 93.472 93.48a47.82 47.82 0 0 0 5.15-6.09 2.343 2.343 0 0 0-3.82-2.715 43.106 43.106 0 0 1-4.644 5.49c-14.21 14.211-36.783 16.436-53.436 5.587l6.82-6.82a33.416 33.416 0 0 0 19.825 4.182A33.343 33.343 0 0 0 83.53 83.54a2.344 2.344 0 0 0-3.314-3.315c-8.777 8.778-22.34 10.792-33.215 5.25L61.62 70.855a10.97 10.97 0 0 0 6.165-3.084zm40.711-62.095l6.11 6.11-27.712 39.944-16.207 5.61a10.892 10.892 0 0 0-2.903-5.092 10.953 10.953 0 0 0-3.512-2.348l44.224-44.224zM11.504 114.342l-6.11-6.11 27.712-39.944 16.207-5.61a10.892 10.892 0 0 0 2.903 5.092 10.953 10.953 0 0 0 3.512 2.348l-44.224 44.224zm44.018-49.894a6.223 6.223 0 0 1-1.85-4.44l.003-.094c.036-.19.047-.383.035-.579a6.22 6.22 0 0 1 1.812-3.766A6.33 6.33 0 0 1 60 53.726a6.33 6.33 0 0 1 4.478 1.843 6.223 6.223 0 0 1 1.85 4.44l-.003.094a2.325 2.325 0 0 0-.035.579 6.22 6.22 0 0 1-1.812 3.766c-2.47 2.458-6.487 2.457-8.956 0z" />
                            <path
                                d="M113.341 82.064a2.344 2.344 0 0 0-3.115 1.131l-.026.057a2.343 2.343 0 1 0 4.26 1.955l.013-.028a2.344 2.344 0 0 0-1.132-3.115zM7.65 35.765a2.343 2.343 0 0 0-3.072 1.241l-.021.05a2.338 2.338 0 0 0 2.165 3.228c.922 0 1.8-.55 2.173-1.454.5-1.19-.056-2.56-1.245-3.065z" />
                        </g>
                    </svg>
                    <div class="secondary-font f-40 mt-2 waiting-text"> WAITING FOR NEXT ROUND</div>
                    <div class="line-loader mt-2">
                        <div class="fill-line"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="main-container">
            <div class="left-sidebar">
                <div class="left-sidebar_text">
                    <div class="tabs-navs">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item active allbets" role="presentation">
                                <button class="nav-link active" id="pills-allbets-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-allbets" type="button" role="tab"
                                    aria-controls="pills-allbets" aria-selected="true">All Bets</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="pills-mybets-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-mybets" type="button" role="tab" aria-controls="pills-mybets"
                                    aria-selected="false">My Bets</button>
                            </li>
                            <span class="active-line"></span>
                        </ul>
                    </div>
                    <div class="contents-blocks">
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-allbets" role="tabpanel"
                                aria-labelledby="pills-allbets-tab">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="bets-count secondary-font f-14">TOTAL BETS : <span class="text-success"
                                            id="total_bets">0</span></div>
                                    <div class="custom-badge mx-auto hide" id="prev_win_multi">0.00x</div>
                                </div>
                                <div class="list-data-tbl mt-2">
                                    <div class="list-header">
                                        <div class="column-1">
                                            User
                                        </div>
                                        <div class="column-2">
                                            Bet
                                        </div>
                                        <div class="column-3">
                                            Mult.
                                        </div>
                                        <div class="column-4">
                                            Cash out
                                        </div>
                                    </div>
                                    <div class="list-body scroll-div list-body0" id="all_bets"> </div> 
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-mybets" role="tabpanel"
                                aria-labelledby="pills-mybets-tab">
                                <div class="list-data-tbl mt-2">
                                    <div class="list-header">
                                        <div class="column-1">
                                            Date
                                        </div>
                                        <div class="column-2">
                                            Bet
                                        </div>
                                        <div class="column-3">
                                            Mult.
                                        </div>
                                        <div class="column-4">
                                            Cash out
                                        </div>
                                        <div class="ps-2"></div>
                                    </div>
                                    <div class="list-body scroll-div list-body1" id="my_bet_list">
                                        @foreach ($mybets as $item)
                                            <div class="list-items">
                                                <div class="column-1 users fw-normal">
                                                    {{ showDateTime($item->created_at, 'h:i') }}
                                                </div>
                                                <div class="column-2">
                                                    <button
                                                        class="btn btn-transparent previous-history d-flex align-items-center mx-auto fw-normal">
                                                        {{ number_format($item->amount, 2) }}₹
                                                    </button>
                                                </div>
                                                <div class="column-3">

                                                    <div class="bg3 custom-badge mx-auto">
                                                        {{ number_format($item->cashout_multiplier, 2) }}x
                                                    </div>

                                                </div>
                                                <div class="column-4 fw-normal">
                                                    {{ number_format($item->amount * $item->cashout_multiplier, 2) }}₹
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="right-sidebar">
                <h3 class="f-size--28 mb-4 text-center">@lang('Current Balance :') <span class="base--color"> <span
                            class="bal">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span>
                        {{ __(gs('cur_text')) }}</span></h3>
                <div class="game-play">
                    <div class="history-top">
                        <div class="stats">
                            <div class="payouts-wrapper">
                                <div class="payouts-block">
                                    @foreach ($allresults as $item)
                                        @if ($item->result != 'pending' && $item->result != '')
                                            <div class="bg1 custom-badge">{{ number_format($item->result, 2) }}x</div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="shadow">
                            </div>

                        </div>
                    </div>

                    <div class="stage-board">
                        <div class="counter-num text-center" id="auto_increment_number_div" style="display: none;">
                            <div class="secondary-font f-40 flew_away_section" style="display: none;">FLEW AWAY!
                            </div>
                            <div id="auto_increment_number">1.00<span>X</span></div>
                        </div>
                        <div class="loading-game">
                            <div class="center-loading text-white text-center game-centeral-loading">
                                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120">
                                    <g fill="#E50539" fill-rule="nonzero">
                                        <path
                                            d="M67.785 67.77a10.882 10.882 0 0 0 2.995-5.502l18.37-6.36c.47-.163.876-.471 1.16-.88l29.263-42.18a2.343 2.343 0 0 0-.268-2.993L110.153.704a2.344 2.344 0 0 0-3.314 0L95.73 11.813C71.965-5.861 38.683-3.514 17.58 17.588a60.26 60.26 0 0 0-8.829 11.21 2.343 2.343 0 0 0 4.001 2.441 55.575 55.575 0 0 1 8.142-10.336C40.184 1.613 70.512-.68 92.378 15.165l-5.72 5.72c-8.742-5.967-19.302-8.837-29.947-8.1a47.31 47.31 0 0 0-30.183 13.751 47.722 47.722 0 0 0-5.92 7.207 2.344 2.344 0 0 0 3.897 2.605 42.996 42.996 0 0 1 5.337-6.497c14.233-14.234 36.774-16.445 53.436-5.586l-6.818 6.818a33.418 33.418 0 0 0-19.773-4.186A33.338 33.338 0 0 0 36.47 36.48a2.344 2.344 0 0 0 3.314 3.314c8.787-8.786 22.336-10.795 33.215-5.248L58.38 49.163a10.969 10.969 0 0 0-6.164 3.084 10.882 10.882 0 0 0-2.996 5.504l-18.37 6.36c-.47.163-.876.47-1.159.879L.427 107.17a2.343 2.343 0 0 0 .268 2.992l9.152 9.151a2.337 2.337 0 0 0 1.657.687c.6 0 1.2-.23 1.657-.687l11.109-11.109A59.835 59.835 0 0 0 59.99 120a59.873 59.873 0 0 0 42.43-17.571 60.476 60.476 0 0 0 7.162-8.63 2.343 2.343 0 1 0-3.87-2.643 55.793 55.793 0 0 1-6.606 7.959c-19.321 19.32-49.61 21.598-71.487 5.74l5.722-5.723a47.325 47.325 0 0 0 30.058 8.092A47.318 47.318 0 0 0 93.472 93.48a47.82 47.82 0 0 0 5.15-6.09 2.343 2.343 0 0 0-3.82-2.715 43.106 43.106 0 0 1-4.644 5.49c-14.21 14.211-36.783 16.436-53.436 5.587l6.82-6.82a33.416 33.416 0 0 0 19.825 4.182A33.343 33.343 0 0 0 83.53 83.54a2.344 2.344 0 0 0-3.314-3.315c-8.777 8.778-22.34 10.792-33.215 5.25L61.62 70.855a10.97 10.97 0 0 0 6.165-3.084zm40.711-62.095l6.11 6.11-27.712 39.944-16.207 5.61a10.892 10.892 0 0 0-2.903-5.092 10.953 10.953 0 0 0-3.512-2.348l44.224-44.224zM11.504 114.342l-6.11-6.11 27.712-39.944 16.207-5.61a10.892 10.892 0 0 0 2.903 5.092 10.953 10.953 0 0 0 3.512 2.348l-44.224 44.224zm44.018-49.894a6.223 6.223 0 0 1-1.85-4.44l.003-.094c.036-.19.047-.383.035-.579a6.22 6.22 0 0 1 1.812-3.766A6.33 6.33 0 0 1 60 53.726a6.33 6.33 0 0 1 4.478 1.843 6.223 6.223 0 0 1 1.85 4.44l-.003.094a2.325 2.325 0 0 0-.035.579 6.22 6.22 0 0 1-1.812 3.766c-2.47 2.458-6.487 2.457-8.956 0z" />
                                        <path
                                            d="M113.341 82.064a2.344 2.344 0 0 0-3.115 1.131l-.026.057a2.343 2.343 0 1 0 4.26 1.955l.013-.028a2.344 2.344 0 0 0-1.132-3.115zM7.65 35.765a2.343 2.343 0 0 0-3.072 1.241l-.021.05a2.338 2.338 0 0 0 2.165 3.228c.922 0 1.8-.55 2.173-1.454.5-1.19-.056-2.56-1.245-3.065z" />
                                    </g>
                                </svg>
                                <div class="secondary-font f-40 mt-2 waiting-text"> WAITING FOR NEXT ROUND</div>
                                <div class="line-loader mt-2">
                                    <div class="fill-line"></div>
                                </div>
                            </div>
                            <div class="bottom-left-plane">
                                <img class="plane-static" src="/assets/images/aviator/p.png" />
                            </div>
                        </div>
                        <img src="/assets/images/aviator/bg-rotate-old.svg" class="rotateimage rotatebg" />
                        <canvas id="myCanvas" height=400 width="1900"></canvas>
                    </div>

                    <div class="bet-controls">
                        <div class="bet-control double-bet text-center" id="main_bet_section">
                            <div class="controls">
                                <div class="navigation">
                                    <input id="bet_type" type="hidden" value="0">
                                </div>
                                <div class="first-row auto-game-feature">
                                    <div class="bet-block">
                                        <div class="spinner">
                                            <div class="input">
                                                <input id="bet_amount" value="{{ showAmount($game->min_limit, currencyFormat: false) }}" type="text" class="main_bet_amount"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" />
                                            </div>
                                            <div class="qty-buttons">
                                                <button class="minus " id="main_minus_btn"
                                                    onclick="bet_amount_decremental(this);"> -
                                                </button>
                                                <button class="plus" id="main_plus_btn"
                                                    onclick="bet_amount_incremental(this);">
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                        <div class="bets-opt-list">
                                            <button class="btn btn-secondary btn-sm bet-opt main_amount_btn"
                                                onclick="select_direct_bet_amount(this);"><span
                                                    class="amt">100</span><span class="currency">₹</span></button>
                                            <button class="btn btn-secondary btn-sm bet-opt main_amount_btn"
                                                onclick="select_direct_bet_amount(this);"><span
                                                    class="amt">200</span><span class="currency">₹</span></button>
                                            <button class="btn btn-secondary btn-sm bet-opt main_amount_btn"
                                                onclick="select_direct_bet_amount(this);"><span
                                                    class="amt">500</span><span class="currency">₹</span></button>
                                            <button class="btn btn-secondary btn-sm bet-opt main_amount_btn"
                                                onclick="select_direct_bet_amount(this);"><span
                                                    class="amt">1000</span><span class="currency">₹</span></button>
                                        </div>
                                    </div>
                                    <div class="buttons-block" id="bet_button">
                                        <button
                                            class="btn btn-success bet font-family-title ng-star-inserted main_bet_btn"
                                            onclick="bet_now(this, 0);" id="main_bet_now"><label
                                                class="font-family-title label">BET</label></button>
                                    </div>
                                    <div class="buttons-block" id="cancle_button" style="display: none;">
                                        <div class="btn-tooltip f-14 mb-1" id="waiting" style="display: none;">
                                            Waiting
                                            for next round</div>
                                        <button
                                            class="btn btn-danger bet font-family-title height-70 ng-star-inserted main_bet_btn"
                                            onclick="cancle_now(this, 0);" id="main_cancel_now"><label
                                                class="font-family-title label">CANCEL</label></button>
                                    </div>
                                    <div class="buttons-block" id="cashout_button" style="display: none;">
                                        <input type="hidden" name="main_bet_id" id="main_bet_id">
                                        <button class="btn btn-warning bet font-family-title ng-star-inserted"
                                            onclick="cash_out_now(0);">
                                            <label class="font-family-title label">CASH OUT</label>
                                            <span class="font-family-title label" id="cash_out_amount"></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="second-row">
                                    <div class="cashout-block m-0">
                                        <div class="cash-out-switcher">
                                            <div
                                                class="form-check form-switch lg-switch d-flex align-items-center pe-5">
                                                <label class="form-check-label f-12 me-1" for="bet">Auto Bet</label>
                                                <input class="form-check-input m-0" type="checkbox" role="bet"
                                                    id="main_auto_bet">
                                            </div>
                                            <div class="form-check form-switch lg-switch d-flex align-items-center">
                                                <label class="form-check-label f-12 me-1" for="cashout">Auto Cash
                                                    Out</label>
                                                <input class="form-check-input m-0" type="checkbox" role="cashout"
                                                    id="main_checkout">
                                            </div>
                                        </div>
                                        <div class="cashout-spinner-wrapper">
                                            <div class="cashout-spinner disabled">
                                                <div class="spinner small">
                                                    <div class="input full-width">
                                                        <input class="f-16 font-weight-bold" disabled type="text"
                                                            value="1.01" id="main_incrementor"
                                                            onchange="main_incrementor_change(this.value);">
                                                    </div>
                                                    <div class="text text-x">
                                                        x
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="game-instruction" data-bs-toggle="modal"
                                data-bs-target="#exampleModalCenter">@lang('Game Instruction') <i
                                    class="las la-info-circle"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
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
    <link rel="stylesheet" href="{{ asset('assets/global/css/game/aviator.css') }} " />
@endpush

@push('script')
    <script>
        let audio;
        var hash_id = '{{ csrf_token() }}'; 
        var currency_symbol = '{{gs('cur_sym')}}';
        var wallet_balance = '{{ showAmount(auth()->user()->balance, currencyFormat: false) }}';
        var min_bet_amount = parseFloat('{{$game->min_limit}}');
        var max_bet_amount = parseFloat('{{$game->max_limit}}');
        var current_game_data = {{aviatorId()}}; 
        let game_id = 0;
        var bet_array = [];
        let currentbet;
        var main_cash_out = 0;
        var extra_cash_out = 0;
        var main_incrementor;
        var extra_incrementor;
        let stage_time_out = 0;
        var is_game_generated = 0;
        function playAudio(filename) {
            var audio = new Audio(`{{ asset('assets/audio') }}/${filename}`);
            audio.play();
        }
    </script>
@endpush


@push('script-lib')
    <script src="{{ asset('assets/global/js/game/aviator-canvas.js') }}"></script>
    <script src="{{ asset('assets/global/js/game/aviator.js') }}"></script>
@endpush

@push('script')
    <script>
        $(".load-txt").hide();
        getAllbets();
    </script>
@endpush