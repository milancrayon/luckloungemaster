@extends($activeTemplate . 'layouts.master')
@section('content')
@push('style-lib')
    
@endpush
@push('script-lib') 
    <script src="{{ asset('assets/global/js/game/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/game/createjs.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/game/howler.min.js') }}"></script>
    <script src="{{ asset('assets/global/js/game/roulettee.js') }}"></script>
@endpush

@push('script')
    <script>
        $(document).ready(function () {
            var oMain = new CMain({
                money: {{auth()->user()->balance}},      //STARING CREDIT FOR THE USER
                min_bet: {{$game->min_limit}},     //MINIMUM BET
                max_bet: {{$game->max_limit}},     //MAXIMUM BET
                time_bet: 0,  //TIME TO WAIT FOR A BET IN MILLISECONDS. SET 0 IF YOU DON'T WANT BET LIMIT
                time_winner: 3000, //TIME FOR WINNER SHOWING IN MILLISECONDS    
                win_occurrence: {{$game->probable_win}}, //Win occurrence percentage (100 = always win). 
                //SET THIS VALUE TO -1 IF YOU WANT WIN OCCURRENCE STRICTLY RELATED TO PLAYER BET ( SEE DOCUMENTATION)
                casino_cash: 4000,    //The starting casino cash that is recharged by the money lost by the user
                fullscreen: false,     //SET THIS TO FALSE IF YOU DON'T WANT TO SHOW FULLSCREEN BUTTON
                check_orientation: false, //SET TO FALSE IF YOU DON'T WANT TO SHOW ORIENTATION ALERT ON MOBILE DEVICES
                show_credits: false,           //SET THIS VALUE TO FALSE IF YOU DON'T TO SHOW CREDITS BUTTON
                num_hand_before_ads: 10  //NUMBER OF HANDS TO COMPLETE, BEFORE TRIGGERING SAVE_SCORE EVENT. USEFUL FOR INTER-LEVEL AD EVENTUALLY.
            });


            $(oMain).on("recharge", function (evt) { 
            });

            $(oMain).on("start_session", function (evt) {
                if (getParamValue('ctl-arcade') === "true") {
                    parent.__ctlArcadeStartSession();
                } 
            });

            $(oMain).on("end_session", function (evt) {
                if (getParamValue('ctl-arcade') === "true") {
                    parent.__ctlArcadeEndSession();
                } 
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    }
                });
                var url = '{{ route('user.play.roulettebetupdate') }}';
                var data = {
                    win:0, 
                };
                $.post(url, data, function(response) { 
                    if(response.status){

                    }else{
                        notify("error", response.message);
                        oMain.gotoMenu();  
                    }
                });
            });

            $(oMain).on("before_bet_place", function (evt, iTotBet) {  
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    }
                });
                var url = '{{ route('user.play.roulettebetvalidation') }}';
                var data = {
                    invest:iTotBet, 
                };
                $.post(url, data, function(response) {
                    $(s_oMain).trigger("bet_validation_response", response); 
                    if(response.status){ 
                       
                    }else{
                        notify("error", response.message);
                        oMain.gotoMenu();
                        $(oMain).trigger("end_session");
                        $(oMain).trigger("share_event");
                    }
                });
            });

            $(oMain).on("bet_placed", function (evt, iTotBet) {  
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    }
                });
                var url = '{{ route('user.play.roulettebet') }}';
                var data = {
                    invest:iTotBet, 
                };
                $.post(url, data, function(response) { 
                    if(response.status){ 
                        notify("success", "Bet Placed!!");
                    }else{
                        notify("error", response.message);
                        oMain.gotoMenu();
                        $(oMain).trigger("end_session");
                        $(oMain).trigger("share_event");
                    }
                });
            });

            $(oMain).on("save_score", function (evt, iMoney, iWin) {
                if (getParamValue('ctl-arcade') === "true") {
                    parent.__ctlArcadeSaveScore({ score: iMoney });
                }  
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    }
                });
                var url = '{{ route('user.play.roulettebetupdate') }}';
                var data = {
                    win:iWin, 
                };
                $.post(url, data, function(response) { 
                    if(response.status){
                        if(iWin > 0){
                            $(".win-loss-popup").addClass("active");
                            $(".win-loss-popup__body").find("img").addClass("d-none"); 
                            $(".win-loss-popup__body").find(".win").removeClass("d-none");
                            $(".win-loss-popup__footer").find(".data-result").text("You Win "+iWin);
                        }
                    }else{
                        notify("error", response.message);
                        oMain.gotoMenu();
                        $(oMain).trigger("end_session");
                        $(oMain).trigger("share_event");
                    }
                });

            });

            $(oMain).on("show_interlevel_ad", function (evt) {
                if (getParamValue('ctl-arcade') === "true") {
                    parent.__ctlArcadeShowInterlevelAD();
                } 
            });

            $(oMain).on("share_event", function (evt, iMoney) {
                if (getParamValue('ctl-arcade') === "true") {
                    parent.__ctlArcadeShareEvent({
                        img: "200x200.jpg",
                        title: TEXT_CONGRATULATIONS,
                        msg: TEXT_SHARE_1 + iMoney + TEXT_SHARE_2,
                        msg_share: TEXT_SHARE_3 + iMoney + TEXT_SHARE_4
                    });
                } 
            });

            if (isIOS()) {
                setTimeout(function () { sizeHandler(); }, 200);
            } else {
                sizeHandler();
            }
        });

    </script>
@endpush

<section class="pt-120 pb-120 roulettegame_wrapper">
    <div class="container">
        <canvas id="canvas" class='ani_hack' width="750" height="600"> </canvas>
        <div id="block_game"
            style="position: fixed; background-color: transparent; top: 0px; left: 0px; width: 100%; height: 100%; display:none">
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