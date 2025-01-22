@extends($activeTemplate . 'layouts.master')
@section('content')
    <section class="pt-120 pb-120 common_game_wrapper keno_wrapper">
        <div class="container">
            <div class="row gy-4">
                <div class="col-md-6">
                    <h4 class="mobile_only">@lang('Your Current Balance ') <span class="bal user-balance">{{ showAmount(auth()->user()->balance, currencyFormat: false) }}</span> {{ __(gs('cur_text')) }}</h4>
                    <div class="card custom--card">
                        <div class="card-body">
                            <div class="keno-number-plate" id="grid">
                                @for ($i = 1; $i <= 80; $i++)
                                    <button class="slot" data-slot="{{ $i }}" number="{{ $i }}">
                                        {{ getAmount($i) }}
                                    </button>
                                @endfor
                            </div>

                            <form class="mt-5" id="kenoForm" action="" method="POST">
                                <div class="d-flex d-md-block flex-wrap">
                                    <div class="d-flex justify-content-between mb-3 flex-wrap gap-3 game_part">
                                        <a class="game-instruction mt-2" data-bs-toggle="modal" data-bs-target="#exampleModalCenter" href="javascript:void(0)">@lang('Game Instruction')
                                            <i class="las la-info-circle"></i>
                                        </a>
                                        <div class="action-area">
                                            <button class="random-btn btn-sm" type="button"><i class="las la-random"></i> @lang('Random')</button>
                                            <button class="refresh-btn btn-sm" type="button"><i class="las la-undo-alt"></i> @lang('Refresh')</button>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <input class="form-control amount-field" name="invest" type="text" placeholder="Enter amount" autocomplete="off" required>
                                            <span class="input-group-text" id="basic-addon2">{{ __(gs('cur_text')) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center flex-wrap">
                                            <small class="form-text text-muted">
                                                <i class="fas fa-info-circle mr-2"></i> @lang('Minimum :') {{ showAmount($game->min_limit) }}
                                                | @lang('Maximum :') {{ showAmount($game->max_limit) }}
                                            </small>
                                        </div>
                                    </div>
                                    <button class="cmn-btn w-100 text-center" type="submit">@lang('Play Now')</button>
                                </div>
                            </form>
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card custom--card">
                        <div class="card-body">
                            <h3 class="f-size--28 text-center">@lang('How To Win')?</h3>
                            <p class="my-3">@lang('Click the') <span class="text--base">{{ $game->level->max_select_number }}</span> @lang('number that are on your scratch off, and then click ') <strong class="text--base">@lang('"play Now"')</strong> @lang('button to see if you are a winner!')</p>
                            <ul class="list-group list-group-flush payment-list">
                                @foreach ($game->level->levels as $item)
                                    <li class="list-group-item d-flex justify-content-between flex-wrap px-0">
                                        <span>@lang('If match '){{ getAmount($item->level) }} @lang('number')</span>
                                        <span class="text--base">{{ showAmount($item->percent) }}%</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <x-recent-game-log :mylogs=$mylogs :alllogs=$alllogs /> 
        </div>
    </section>

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
    <link href="{{ asset('assets/global/css/game/keno.css') }}" rel="stylesheet">
@endpush

@push('script')
    <script>
        "use strict";
        let audio;
        let slotNumbers = [];
        let maxSelectNumber = "{{ $game->level->max_select_number }}";
        $('.slot').on('click', function(e) {

            if ($('.slot').hasClass('win') || $('.slot').hasClass('loss')) {
                notify('error', 'You have to refresh number slot');
                return;
            }

            if ($(this).hasClass('active')) {
                notify('error', 'Already select this number');
                return;
            }

            let activeSlotNumber = $('.slot.active').length;
            if (activeSlotNumber >= maxSelectNumber) {
                notify('error', `You have to select max ${maxSelectNumber} number`);
                return;
            }

            var slotNumber = Number($(this).data('slot'));
            if (slotNumbers.includes(slotNumber)) {
                notify('error', `You already selected this number`);
                return;
            }
            playAudio('keno.wav')
            slotNumbers.push(slotNumber);
            $(this).addClass('active');
            $(this).attr('disabled', true)
        });

        $('.refresh-btn').on('click', function(e) {
            refreshSlot();
            playAudio('keno.wav')
        });

        $('.random-btn').on('click', function(e) {
            playAudio('keno.wav')
            randomSlot(3)
        });

        let totalSetTimeOut = 0;

        function randomSlot(maxLimit) {
            for (let j = 0; j < maxLimit; j++) {
                totalSetTimeOut = totalSetTimeOut + j * 100;
                setTimeout(function() {
                    refreshSlot();
                    slotNumbers = [];
                    while (slotNumbers.length < maxSelectNumber) {
                        var randomValue = Math.floor(Math.random() * 80) + 1;
                        if (!slotNumbers.includes(randomValue)) {
                            slotNumbers.push(randomValue);
                        }
                    }
                    for (let k = 0; k < maxSelectNumber; k++) {
                        var slot = $(`.slot[number="${slotNumbers[k]}"]`);
                        slot.addClass('active');
                        slot.attr('disabled', true);
                    }
                }, j * 100);
            }
        }

        function refreshSlot() {
            $('.slot').removeClass('active').removeAttr('disabled');
            $('.slot').removeClass('win');
            $('.slot').removeClass('loss');
            $('#game .cmn-btn').removeAttr('disabled')
            slotNumbers = [];
        }

        $("#kenoForm").on('submit', function(e) {
            e.preventDefault();
            totalSetTimeOut = 0;

            if ($('.slot').hasClass('win') || $('.slot').hasClass('loss')) {
                notify('error', 'You have to refresh number slot');
                return;
            }

            let data = {
                _token: "{{ csrf_token() }}",
                invest: $('[name=invest]').val(),
                choose: slotNumbers
            };
            if (data.invest < 1) {
                notify('error', 'Invest value is required');
                return;
            }
            if (slotNumbers.length != maxSelectNumber) {
                notify('error', `You have to select minimum ${maxSelectNumber} number`);
                return;
            }


            $('#game .cmn-btn').attr('disabled', true);
            playAudio('keno_start.wav');

            $.ajax({
                type: "POST",
                url: "{{ route('user.play.keno.submit') }}",
                data: data,
                success: function(response) {
                    if (response.error) {
                        audio.pause()
                        notify('error', response.error);
                        $('#game .cmn-btn').removeAttr('disabled');
                        return;
                    } else {
                        randomSlot(5);
                        $('[name=invest]').val(0);
                        setTimeout(() => {
                            refreshSlot();
                            endGame(response);
                        }, totalSetTimeOut);
                    }
                }
            });

        });

        function endGame(response) {
            for (let i = 0; i < response.user_select.length; i++) {
                var slot = $(`.slot[number="${response.user_select[i]}"]`);
                if (response.match_number.length == 0) {
                    slot.addClass('loss')
                } else {
                    slot.addClass('active');
                }
                slot.attr('disabled', true);
            }
            for (let j = 0; j < response.match_number.length; j++) {
                var slot = $(`.slot[number="${response.match_number[j]}"]`);
                slot.addClass('win');
            }
            var totalMatched = response.match_number.length;
            let data = {
                _token: "{{ csrf_token() }}",
                gameLog_id: response.game_log_id,
            }
            $.ajax({
                type: "POST",
                url: "{{ route('user.play.keno.update') }}",
                data: data,
                success: function(response) {
                    audio.pause();
                    totalSetTimeOut = 0;
                    $(".win-loss-popup").addClass("active");
                    $(".win-loss-popup__body").find("img").addClass("d-none");
                    if (response.win === 1) {
                        playAudio('win.wav');
                        $(".win-loss-popup__body").find(".win").removeClass("d-none");
                    } else {
                        playAudio('lose.wav');
                        $(".win-loss-popup__body").find(".lose").removeClass("d-none");
                    }
                    $(".win-loss-popup__footer").find(".data-result").text(response.result);
                    $(".win-loss-popup__footer").find('h5').text(`@lang('Total ${totalMatched} number matched')`)
                }
            });
        }

        function playAudio(filename) {
            audio = new Audio(`{{ asset('assets/audio') }}/${filename}`);
            audio.play();
        }
    </script>
@endpush
