$(".auto-btn").click(function () {
    $(this).parent().parent().find("#bet_type").val(1);
});

$(".bet-btn").click(function () {
    $(this).parent().parent().find("#bet_type").val(0);
});

$(".navigation-switcher .slider").click(function () {
    $(this).parent().find(".slider").removeClass('active');
    $(this).addClass('active');

    const type = $(this).text();
    if (type == 'Auto') {
        $(this).parent().parent().parent().find(".second-row").addClass('show');
    } else {
        $(this).parent().parent().parent().find(".second-row").removeClass('show');
    }
});

$(".cash-out-switcher .form-check .form-check-input").change(function () {
    if (this.checked) {
        $(this).parent().parent().parent().find(".cashout-spinner-wrapper input").attr('disabled', false);
        $(this).parent().parent().parent().parent().parent().find(".navigation").addClass('stop-action');
    } else {
        $(this).parent().parent().parent().find(".cashout-spinner-wrapper input").attr('disabled', true);
        $(this).parent().parent().parent().parent().parent().find(".navigation").removeClass('stop-action');
    }
});


function bet_amount_incremental(element) {
    var bet_amount = parseFloat($(element).parent().parent().find(".input #bet_amount").val());
    bet_amount++;
    if (bet_amount <= max_bet_amount) {
        $(element).parent().parent().find(".input #bet_amount").val(bet_amount);
    }
}

function bet_amount_decremental(element) {
    var bet_amount = parseFloat($(element).parent().parent().find(".input #bet_amount").val());
    bet_amount--;
    if (bet_amount >= min_bet_amount) {
        $(element).parent().parent().find(".input #bet_amount").val(bet_amount);
    }
}

function select_direct_bet_amount(element) {
    var current_bet_amount = parseFloat($(element).parent().parent().find(".input #bet_amount").val());
    var adding_bet_amount = parseFloat($(element).find(".amt").text()).toFixed(2);
    if ($(element).hasClass('same')) {
        var new_bet_amount = parseFloat(parseFloat(current_bet_amount) + parseFloat(adding_bet_amount)).toFixed(2);
        if (new_bet_amount <= max_bet_amount) {
            $(element).parent().parent().find(".input #bet_amount").val(new_bet_amount);
        }
    } else {
        $(element).parent().find('.bet-opt').removeClass('same');
        $(element).addClass('same');
        $(element).parent().parent().find(".input #bet_amount").val(adding_bet_amount);
    }
}

var current_game_count;
var multiplier_limit = 0;
var stop_position = 0;
var main_counter = 0;

$('.loading-game').addClass('show');
function cash_out_now(increment = '') {
    cashOutSound();
    let incrementor;
    if (increment != '') {
        incrementor = increment;
    } else {
        incrementor = $("#auto_increment_number").text().slice(0, -1);
    }

    enableDisable('main_bet_section');
    main_cash_out = 0;


    if (bet_array.length == 1) {
        bet_array.splice(0, 1);
    } else if (bet_array.length == 2) {
        bet_array.splice(0, 1);
    }

    let bet_id;
    bet_id = $("#main_bet_id").val();
    var bet_amount = $("#main_bet_section #bet_amount").val();

    game_id = current_game_data.id

    let is_main_auto_bet_checked = $("#main_auto_bet").prop('checked');
    if (is_main_auto_bet_checked) {
        $("#main_bet_section").find("#bet_button").hide();
        $("#main_bet_section").find("#cancle_button").show();
        $("#main_bet_section").find("#cancle_button #waiting").show();
        $("#main_bet_section").find("#cashout_button").hide();
        $("#main_bet_section .controls").removeClass('bet-border-yellow');
        $("#main_bet_section .controls").addClass('bet-border-red');
    } else {
        $("#main_bet_section").find("#bet_button").show();
        $("#main_bet_section").find("#cancle_button").hide();
        $("#main_bet_section").find("#cancle_button #waiting").hide();
        $("#main_bet_section").find("#cashout_button").hide();
        $("#main_bet_section .controls").removeClass('bet-border-red');
        $("#main_bet_section .controls").removeClass('bet-border-yellow');
    }

    $("#main_bet_section").find("#cash_out_amount").text('');

    $.ajax({
        url: '/user/play/aviatorcashout',
        data: {
            _token: hash_id,
            game_id: game_id,
            bet_id: bet_id,
            win_multiplier: incrementor,
        },
        type: "post",
        dataType: "json",
        success: function (result) {
            if (result.isSuccess) {
                $(".bal").html(result.data.bal);
                playAudio("win.wav");
                $(".win-loss-popup").addClass("active");
                $(".win-loss-popup__body").find("img").addClass("d-none");
                $(".win-loss-popup__body").find(".win").removeClass("d-none");
                $(".win-loss-popup__footer").find(".data-result").text(result.message);
                aviatorHistory();
                getAllbets();
                $("#main_bet_section").find("#bet_button").show();
                $("#main_bet_section").find("#cancle_button").hide();
                $("#main_bet_section").find("#cancle_button #waiting").hide();
                $("#main_bet_section").find("#cashout_button").hide();

                $("#my_bet_list #my_bet_section_0").addClass('active');
                $("#my_bet_list #my_bet_section_0 .column-3").html('<div class="' + get_multiplier_badge_class(incrementor) + ' custom-badge mx-auto">' + incrementor + 'x</div>');
                $("#my_bet_list #my_bet_section_0 .column-4").html(result.data.cash_out_amount + currency_symbol);

                $("#my_bet_list #my_bet_section_0").removeAttr('id');
                if ($(`#main_bet_section`).find('.controls').hasClass('dullEffect')) {

                } else {
                    $(`#main_bet_section`).find('.controls').addClass('dullEffect');
                }
                $(".main_bet_amount").prop('disabled', false);
                $("#main_plus_btn").prop('disabled', false);
                $("#main_minus_btn").prop('disabled', false);
                $(".main_amount_btn").prop('disabled', false);
                $("#main_checkout").prop('disabled', false)
                if ($("#main_checkout").prop('checked')) {
                    $("#main_incrementor").prop('disabled', false);
                }
                $("#main_auto_bet").prop('disabled', false);
            }
        }
    });
}

async function crash_plane(inc_no) {
    soundPlay();
    $(".flew_away_section").show();
    $("#auto_increment_number_div").addClass('plancrash');
    $("#auto_increment_number").addClass('text-danger');
    await stopPlane();
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);
    update_round_history(inc_no);
    const number_of_bet = $(".round-history-list").find('.custom-badge').length;
    if (number_of_bet > 50) {
        $(".round-history-list").find('.custom-badge:last').remove();
    }

    let is_main_auto_bet_checked = $("#main_auto_bet").prop('checked');

    const main_bet_id = $("#main_bet_id").val();


    setTimeout(function () {
        const incrementor = $("#auto_increment_number").text().slice(0, -1);
        if (main_cash_out == 2) {
            $("#main_bet_id").val(main_bet_id);
            const main_inc = main_incrementor;
            if (parseFloat(incrementor) >= parseFloat(main_inc)) {
                cash_out_now(main_inc);
            }
            $("#main_bet_id").val('');
        }
        main_cash_out = 0;
    }, 1000);


    if (bet_array[0] && bet_array[0].is_bet != undefined) {
        if (is_main_auto_bet_checked) {
            gamegenerate();
            $("#main_bet_section").find("#bet_button").hide();
            $("#main_bet_section").find("#cancle_button").show();
            $("#main_bet_section").find("#cancle_button #waiting").show();
            $("#main_bet_section").find("#cashout_button").hide();
            $("#main_bet_section .controls").removeClass('bet-border-yellow');
            $("#main_bet_section .controls").addClass('bet-border-red');
        } else {
            $("#main_bet_section").find("#bet_button").show();
            $("#main_bet_section").find("#cancle_button").hide();
            $("#main_bet_section").find("#cancle_button #waiting").hide();
            $("#main_bet_section").find("#cashout_button").hide();
            $("#main_bet_section .controls").removeClass('bet-border-red');
            $("#main_bet_section .controls").removeClass('bet-border-yellow');

            // Main Bet
            $(".main_bet_amount").prop('disabled', false);
            $("#main_plus_btn").prop('disabled', false);
            $("#main_minus_btn").prop('disabled', false);
            $(".main_amount_btn").prop('disabled', false);
            $("#main_checkout").prop('disabled', false);
            if ($("#main_checkout").prop('checked')) {
                $("#main_incrementor").prop('disabled', false);
            }
        }
        $("#main_bet_id").val('');
        $("#main_bet_section").find("#cash_out_amount").text('');
    }
    if (bet_array[1] && bet_array[1].is_bet != undefined) {
        if (is_main_auto_bet_checked) {
            $("#main_bet_section").find("#bet_button").hide();
            $("#main_bet_section").find("#cancle_button").show();
            $("#main_bet_section").find("#cancle_button #waiting").show();
            $("#main_bet_section").find("#cashout_button").hide();
            $("#main_bet_section .controls").removeClass('bet-border-yellow');
            $("#main_bet_section .controls").addClass('bet-border-red');
        } else {
            $("#main_bet_section").find("#bet_button").show();
            $("#main_bet_section").find("#cancle_button").hide();
            $("#main_bet_section").find("#cancle_button #waiting").hide();
            $("#main_bet_section").find("#cashout_button").hide();
            $("#main_bet_section .controls").removeClass('bet-border-red');
            $("#main_bet_section .controls").removeClass('bet-border-yellow');

            // Main Bet
            $(".main_bet_amount").prop('disabled', false);
            $("#main_plus_btn").prop('disabled', false);
            $("#main_minus_btn").prop('disabled', false);
            $(".main_amount_btn").prop('disabled', false);
            $("#main_checkout").prop('disabled', false);
            if ($("#main_checkout").prop('checked')) {
                $("#main_incrementor").prop('disabled', false);
            }
        }

        $("#main_bet_id").val('');
        $("#main_bet_section").find("#cash_out_amount").text('');
    }
    setTimeout(() => {
        if ($(`#main_bet_section .controls`).hasClass('dullEffect')) {
            $(`#main_bet_section .controls`).removeClass('dullEffect');
        }
        // $('.loading-game').addClass('show');
        $("#main_auto_bet").prop('disabled', false);
        location.reload();
    }, 3000);
}

function new_game_generated() {
    is_game_generated = 1;
    $('#my_bet_list .mCSB_container .list-items').removeAttr('id');
    $(".game-centeral-loading").show();

    $("#main_bet_section").find("#cancle_button #waiting").hide();

    if (bet_array.length == 1) {
        if (bet_array[0].section_no == 0) {
            enableDisable('main_bet_section');
        }
    }
    if (bet_array.length == 2) {
        enableDisable('main_bet_section');
    }

    $(".load-txt").hide();

    document.getElementById('auto_increment_number').innerText = '0.00x';

    $('.loading-game').addClass('show');

    $(".flew_away_section").hide();
    $("#auto_increment_number_div").removeClass('plancrash');
    $("#auto_increment_number").removeClass('text-danger');
    $("#running_type").text('bet time');
    $("#auto_increment_number_div").hide();
    current_game_count = 0;

    let is_main_auto_bet_checked = $("#main_auto_bet").prop('checked');
    if (is_main_auto_bet_checked) {
        if (bet_array.length != 2 && (bet_array.length == 0 || (bet_array.length == 1 && bet_array[0].section_no != 0))) {
            var bet_type = $("#main_bet_now").parent().parent().parent().find(".navigation #bet_type").val();
            let bet_amount = $("#main_bet_now").parent().parent().find(".bet-block .spinner #bet_amount").val();

            if (bet_amount < min_bet_amount || bet_amount == '' || bet_amount == NaN) {
                bet_amount = parseFloat(min_bet_amount).toFixed(2);
            } else if (bet_amount > max_bet_amount) {
                bet_amount = parseFloat(max_bet_amount).toFixed(2);
            } else {
                bet_amount = parseFloat(bet_amount).toFixed(2);
            }

            $("#main_bet_now").parent().parent().find(".bet-block .spinner #bet_amount").val(bet_amount);

            if (bet_amount >= min_bet_amount && bet_amount <= max_bet_amount) {
                bet_array.push({ bet_type: bet_type, bet_amount: bet_amount, section_no: 0 });
            }
        }

    }

}

function lets_fly_one() {
    is_game_generated = 0;
    $(".stage-board").addClass('blink_section');
    $(".bet-controls").addClass('blink_section');
}

function lets_fly() {
    $(".stage-board").removeClass('blink_section');
    $(".bet-controls").removeClass('blink_section');
    stage_time_out = 0;
    flyPlaneSound();
    if (bet_array.length == 1 && bet_array[0] && bet_array[0].section_no == 0) {

        enableDisable('main_bet_section');
        $("#main_bet_section").find("#bet_button").hide();
        $("#main_bet_section").find("#cancle_button").hide();
        $("#main_bet_section").find("#cancle_button #waiting").hide();
        $("#main_bet_section").find("#cashout_button").show();
        $("#main_bet_section .controls").removeClass('bet-border-red');
        $("#main_bet_section .controls").addClass('bet-border-yellow');
        $("#main_auto_bet").prop('disabled', true);
        $("#main_checkout").prop('disabled', true);
        $("#main_incrementor").prop('disabled', true);
    }


    if (bet_array.length == 2) {
        if (bet_array[0] && bet_array[0].section_no == 0) {
            enableDisable('main_bet_section');
            $("#main_bet_section").find("#bet_button").hide();
            $("#main_bet_section").find("#cancle_button").hide();
            $("#main_bet_section").find("#cancle_button #waiting").hide();
            $("#main_bet_section").find("#cashout_button").show();
            $("#main_bet_section .controls").removeClass('bet-border-red');
            $("#main_bet_section .controls").addClass('bet-border-yellow');
            $("#main_auto_bet").prop('disabled', true);
            $("#main_checkout").prop('disabled', true);
            $("#main_incrementor").prop('disabled', true);
        }
        if (bet_array[1] && bet_array[1].section_no == 0) {
            enableDisable('main_bet_section');
            $("#main_bet_section").find("#bet_button").hide();
            $("#main_bet_section").find("#cancle_button").hide();
            $("#main_bet_section").find("#cancle_button #waiting").hide();
            $("#main_bet_section").find("#cashout_button").show();
            $("#main_bet_section .controls").removeClass('bet-border-red');
            $("#main_bet_section .controls").addClass('bet-border-yellow');
            $("#main_auto_bet").prop('disabled', true);
            $("#main_checkout").prop('disabled', true);
            $("#main_incrementor").prop('disabled', true);
        }
    }

    $(".load-txt").hide();
    $('.loading-game').removeClass('show');
    $("#auto_increment_number_div").show();
    setVariable(1);

}

function incrementor(inc_no) {
    $('.loading-game').removeClass('show');
    $("#auto_increment_number_div").show();
    $("#running_type").text('cash out time');
    document.getElementById('auto_increment_number').innerText = inc_no + '' + 'x';
    if (bet_array.length > 0) {

        let main_isChecked = $('#main_checkout').prop('checked');
        let incrementor;

        for (let i = 0; i < bet_array.length; i++) {
            if (bet_array[i].section_no == 0) {
                if (bet_array[i].is_bet == 1) {
                    if (main_isChecked == true) {
                        incrementor = $('#main_incrementor').val();
                        main_incrementor = incrementor;
                        if (parseFloat(inc_no) >= parseFloat(incrementor)) {
                            if (main_counter == 0) {
                                cash_out_now(incrementor);
                                main_counter++;
                                main_cash_out = 1;
                            }
                        } else {
                            main_cash_out = 2;
                        }
                    }
                }
            }
        }

    }
    if (bet_array.length > 0) {
        cash_out_multiplier(inc_no);
    }

}

function update_my_new_bet(bet_amount, section_no, target) {
    var html = '';
    html += '<div class="list-items" id="my_bet_section_' + section_no + '">' +
        '<div class="column-1 users fw-normal"> ' + get_current_hour_minute() + ' </div>' +
        '<div class="column-2"> <button class="btn btn-transparent previous-history d-flex align-items-center mx-auto fw-normal">' + parseFloat(bet_amount).toFixed(2) + '' + currency_symbol + '</button> </div>' +
        '<div class="column-3"> - </div>' +
        '<div class="column-4 fw-normal"> - </div>' +
        '</div>';
    $(target).prepend(html);
}

function get_multiplier_badge_class(multiplier) {
    if (parseFloat(multiplier) <= 2) {
        return 'bg3';
    } else if (parseFloat(multiplier) < 10) {
        return 'bg1';
    } else {
        return 'bg2';
    }
}


function cash_out_multiplier(inc_no) {
    if (bet_array.length == 1 && bet_array[0].section_no == 0 && bet_array[0].is_bet != undefined) {
        $("#main_bet_section").find("#cash_out_amount").text(parseFloat(bet_array[0].bet_amount * inc_no).toFixed(2) + '' + currency_symbol);
    }
    if (bet_array.length == 2) {
        $.map(bet_array, function (item, index) {
            if (item.section_no == 0 && item.is_bet != undefined) {
                $("#main_bet_section").find("#cash_out_amount").text(parseFloat(item.bet_amount * inc_no).toFixed(2) + '' + currency_symbol);
            }
        });
    }
}

function show_bet_count(count) {
    $("#total_bets").text(count);
}

async function bet_now(element, section_no) {

    if ($(`#main_bet_section`).find('.controls').hasClass('dullEffect')) {

    } else {

        await gamegenerate();

        if (stage_time_out == 1) {
            if (section_no == 0) {
                enableDisable('main_bet_section');
            }
        } else {
            var bet_type = $(element).parent().parent().parent().find(".navigation #bet_type").val();
            let bet_amount = $(element).parent().parent().find(".bet-block .spinner #bet_amount").val();

            if (section_no == 0) {
                $("#main_bet_section .controls").addClass('bet-border-red');
            }

            if (bet_amount < min_bet_amount || bet_amount == '' || bet_amount == NaN) {
                bet_amount = parseFloat(min_bet_amount).toFixed(2);
            } else if (bet_amount > max_bet_amount) {
                bet_amount = parseFloat(max_bet_amount).toFixed(2);
            } else {
                bet_amount = parseFloat(bet_amount).toFixed(2);
            }

            $(element).parent().parent().find(".bet-block .spinner #bet_amount").val(bet_amount);

            if (bet_amount >= min_bet_amount && bet_amount <= max_bet_amount) {
                $(element).parent().parent().find("#bet_button").hide();
                $(element).parent().parent().find("#cancle_button").show();
                $(element).parent().parent().find("#cancle_button #waiting").show();

                if (is_game_generated == 1) {
                    setTimeout(() => {
                        $(element).parent().parent().find("#cancle_button #waiting").hide();
                    }, 500);
                }

                bet_array.push({ bet_type: bet_type, bet_amount: bet_amount, section_no: section_no });
            }
        }
    }
}

function cancle_now(element, section_no) {
    if (stage_time_out == 1) {

    } else {
        if (section_no == 0) {
            $('#main_auto_bet').prop('checked', false);
            $("#main_bet_section .controls").removeClass('bet-border-red');
        }
        if (bet_array.length == 1) {
            bet_array = [];
        }
        if (bet_array.length == 2 && section_no == 0) {
            if (bet_array[0].section_no == 0) {
                bet_array.splice(0, 1);
            }
        }

        $(element).parent().parent().find("#bet_button").show();
        $(element).parent().parent().find("#cancle_button").hide();
        $(element).parent().parent().find("#cancle_button #waiting").hide();
    }
}

function place_bet_now() {
    for (let i = 0; i < bet_array.length; i++) {
        bet_array[i].game_id = current_game_data.id;
    }

    $.ajax({
        url: '/user/play/aviatorbetadd',
        data: {
            _token: hash_id,
            all_bets: bet_array,
            invest: bet_array[0].bet_amount,
        },
        type: "POST",
        dataType: "json",
        success: async function (result) {
            if (result.isSuccess) {
                aviatorHistory();
                getAllbets();
                $(".bal").html(result.data.bal);
                if (bet_array.length == 1) {
                    update_my_new_bet(bet_array[0].bet_amount, bet_array[0].section_no, '#my_bet_list .mCSB_container');
                } else if (bet_array.length == 2) {
                    if (bet_array[0] != undefined) {
                        update_my_new_bet(bet_array[0].bet_amount, bet_array[0].section_no, '#my_bet_list .mCSB_container');
                    }
                    if (bet_array[1] != undefined) {
                        update_my_new_bet(bet_array[1].bet_amount, bet_array[1].section_no, '#my_bet_list .mCSB_container');
                    }
                }

                if (bet_array.length == 1 && bet_array[0].section_no == 0) {
                    bet_array[0].is_bet = 1;
                    enableDisable('main_bet_section');
                    $("#main_bet_id").val(result.data.return_bets[0].bet_id);
                }


                if (bet_array.length == 2) {

                    if (bet_array[0].section_no == 0) {
                        $("#main_bet_id").val(result.data.return_bets[0].bet_id);
                        bet_array[0].is_bet = 1;
                    }

                    if (bet_array[1].section_no == 0) {
                        $("#main_bet_id").val(result.data.return_bets[0].bet_id);
                        bet_array[1].is_bet = 1;
                    }
                }
            } else {
                crash_plane(0.1);
                gameover(0.1);
                notify("error", result.message);
                $("#main_bet_section").find("#bet_button").show();
                $("#main_bet_section").find("#cancle_button").hide();
                $("#main_bet_section").find("#cancle_button #waiting").hide();
                $("#main_bet_section").find("#cashout_button").hide();
                $("#main_bet_section .controls").removeClass('bet-border-red');
                $("#main_bet_section .controls").removeClass('bet-border-yellow');
                $("#main_bet_section .controls .navigation").removeClass('stop-action');

                $(".main_bet_amount").prop('disabled', false);
                $("#main_plus_btn").prop('disabled', false);
                $("#main_minus_btn").prop('disabled', false);
                $(".main_amount_btn").prop('disabled', false);
                $("#main_checkout").prop('disabled', false)
                if ($("#main_checkout").prop('checked')) {
                    $("#main_incrementor").prop('disabled', false);
                }
                $('#main_auto_bet').prop('checked', false);

                bet_array = [];
            }
        }

    });
}

function get_current_hour_minute() {
    var date = new Date;
    var hour = date.getHours();
    var minutes = date.getMinutes();

    if (hour.toString().length > 1) {
        var retHour = hour;
    } else {
        var retHour = '0' + hour;
    }

    if (minutes.toString().length > 1) {
        var retMinute = minutes;
    } else {
        var retMinute = '0' + minutes;
    }

    return retHour + ':' + retMinute;
}

function update_round_history(inc_no) {
    var html = '<div class="' + get_multiplier_badge_class(inc_no) + ' custom-badge">' + parseFloat(inc_no).toFixed(2) + 'x</div>'
    $(".payouts-wrapper .payouts-block").prepend(html);
    $(".button-block .history-dropdown .round-history-list").prepend(html);
}

function soundPlay() {
    playAudio("plane-crash.mp3");
}

function flyPlaneSound() {
    playAudio("plane-start.mp3");
}

function cashOutSound() {
    playAudio("plane-cashout.mp3");
}


$(".main_bet_btn").on('click', function () {
    if (stage_time_out != 1) {
        let id = $(this).attr('id');
        if (id == 'main_bet_now') {
            $(".main_bet_amount").prop('disabled', true);
            $("#main_plus_btn").prop('disabled', true);
            $("#main_minus_btn").prop('disabled', true);
            $(".main_amount_btn").prop('disabled', true);
            $("#main_checkout").prop('disabled', true);
            $("#main_incrementor").prop('disabled', true);

        } else if (id == 'main_cancel_now') {
            $(".main_bet_amount").prop('disabled', false);
            $("#main_plus_btn").prop('disabled', false);
            $("#main_minus_btn").prop('disabled', false);
            $(".main_amount_btn").prop('disabled', false);
            $("#main_checkout").prop('disabled', false)
            if ($("#main_checkout").prop('checked')) {
                $("#main_incrementor").prop('disabled', false);
            }

        }

    }
});


function gameLoadingTimer() {
    let timer_no = 1;
    var game_loading_timer = setInterval(function () {
        if (timer_no <= 5) {
            if (timer_no == 1) {
                $('.loading-game').addClass('show');
            }
            timer_no++;
        } else {
            $(".loading-game").removeClass('show');
            clearInterval(game_loading_timer);
        }
    }, 1000); // for every second
}

let focus_timer = 0;
let focus_interval;
let visibility_timer = 0;
let visibility_interval;

let window_blur = 0;
$(window).blur(function () {
    // const music = document.getElementById("background_Audio");
    window_blur = 1;
    // music.pause();
    focus_interval = setInterval(function () {
        focus_timer = parseInt(focus_timer + 1);
    }, 1000);
});

function enableDisable(section) {
    $(`#${section}`).find('.controls').addClass('dullEffect');
    setTimeout(function () {
        $(`#${section}`).find('.controls').removeClass('dullEffect');
    }, 200);
}

function main_incrementor_change(new_value) {
    if (new_value < 0.01) {
        $("#main_incrementor").val("0.01");
    } else {
        $("#main_incrementor").val(parseFloat(new_value).toFixed(2));
    }
}

function hide_loading_game() {
    $('.loading-game').removeClass('show');
}
$(window).bind("pageshow", function (event) {
    if (event.originalEvent.persisted) {
        $(".load-txt").show();
    }
});

$(".fill-line").bind('oanimationend animationend webkitAnimationEnd', function () {
    $('bottom-left-plane').show();
});

$(".fill-line").bind('oanimationstart animationstart webkitAnimationStart', function () {

    $(".game-centeral-loading").show();
});
$(".history-top").click(function (e) {
    e.stopPropagation();
    return false;
});

function gameover(lastint) {
    $.ajax({
        url: '/user/play/aviatorgameover',
        type: "POST",
        data: {
            _token: hash_id,
            "last_time": lastint
        },
        dataType: "text",
        success: async function (data) {
            $(".bal").html(data.bal);
            for (let i = 0; i < bet_array.length; i++) {
                if (bet_array[i] && bet_array[i].is_bet) {
                    bet_array.splice(i, 1);
                }
            }
            aviatorHistory();
            getAllbets();
        }
    });
}
function gamegenerate() {
    $("#auto_increment_number_div").hide();
    $('.loading-game').addClass('show');
    hide_loading_game();
    $.ajax({
        url: '/user/play/aviatergenerate',
        type: "POST",
        data: {
            _token: hash_id
        },
        beforeSend: function () {
        },
        dataType: "json",
        success: function (result) {
            if (result.isSuccess) {
                stage_time_out = 1;
                if (bet_array.length > 0) {
                    place_bet_now();
                }
                current_game_data = result;
                hide_loading_game();
                new_game_generated();
                lets_fly_one();
                lets_fly();
                let currentbet = 0;
                let a = 0.0;
                $.ajax({
                    url: '/user/play/aviatorincreamentor',
                    type: "POST",
                    data: {
                        _token: hash_id
                    },
                    dataType: "json",
                    success: function (data) {
                        currentbet = data.result;
                        let increamtsappgame = setInterval(async () => {
                            if (a >= currentbet) {
                                let res = parseFloat(a).toFixed(2);
                                let result = res;
                                crash_plane(result);
                                incrementor(res);
                                gameover(result);
                                clearInterval(increamtsappgame);
                            } else {
                                a = parseFloat(a) + 0.01;
                                incrementor(parseFloat(a).toFixed(2));
                            }
                        }, 30); // plane speed update
                    }
                });
            } else {
                crash_plane(0.1);
                gameover(0.1);
                notify("error", result.message);
                $("#main_bet_section").find("#bet_button").show();
                $("#main_bet_section").find("#cancle_button").hide();
                $("#main_bet_section").find("#cancle_button #waiting").hide();
                $("#main_bet_section").find("#cashout_button").hide();
                $("#main_bet_section .controls").removeClass('bet-border-red');
                $("#main_bet_section .controls").removeClass('bet-border-yellow');
                $("#main_bet_section .controls .navigation").removeClass('stop-action');

                $(".main_bet_amount").prop('disabled', false);
                $("#main_plus_btn").prop('disabled', false);
                $("#main_minus_btn").prop('disabled', false);
                $(".main_amount_btn").prop('disabled', false);
                $("#main_checkout").prop('disabled', false)
                if ($("#main_checkout").prop('checked')) {
                    $("#main_incrementor").prop('disabled', false);
                }
                $('#main_auto_bet').prop('checked', false);

                bet_array = [];
            }
        }
    });
}
function aviatorHistory() {
    $.ajax({
        url: '/user/play/aviatorhistory',
        type: "POST",
        data: {
            _token: hash_id
        },
        dataType: "json",
        success: function (data) {
            $("#my_bet_list").empty();
            for (let $i = 0; $i < data.length; $i++) {
                let date = new Date(data[$i].created_at);
                $("#my_bet_list").append(`
    <div class="list-items">
    <div class="column-1 users fw-normal">
        `+ date.getHours() + `:` + date.getMinutes() + `
    </div>
    <div class="column-2">
        <button
            class="btn btn-transparent previous-history d-flex align-items-center mx-auto fw-normal">
            `+ data[$i].amount + `₹
        </button>
    </div>
    <div class="column-3">

        <div class="bg3 custom-badge mx-auto">
            `+ data[$i].cashout_multiplier + `x</div>
    </div>
    <div class="column-4 fw-normal">
        `+ Math.round(data[$i].cashout_multiplier * data[$i].amount) + `₹
    </div>
</div>
`);
            }
        }
    });
}

function updateBetlists(intialData) {
    current_game_data = intialData.currentGame;
    let bets = intialData.currentGameBet;
    $("#total_bets").text(intialData.currentGameBetCount);
    $("#all_bets").html('');
    var html = '';
    for (i = 0; i < bets.length; i++) {
        var isActive = bets[i].cashout_multiplier > 0 ? "active" : "";
        if (parseFloat(bets[i].cashout_multiplier) <= 2) {
            var badgeColor = 'bg3';
        } else if (parseFloat(bets[i].cashout_multiplier) < 10) {
            var badgeColor = 'bg1';
        } else {
            var badgeColor = 'bg2';
        }
        if (parseFloat(bets[i].cashout_multiplier) > 0) {
            var cashOut = Math.round(bets[i].cashout_multiplier * bets[i].amount) + currency_symbol;
            var multiplication = '<div class="' + badgeColor + ' custom-badge mx-auto">' + bets[i].cashout_multiplier + 'x</div>';
        } else {
            var cashOut = '-';
            var multiplication = '-';
        }
        if (bets[i].class_name != undefined && bets[i].class_name != 'undefined') {
            var sectionNo = 'bet_id_' + '' + bets[i].class_name;
        } else {
            var sectionNo = '';
        }
        html += '<div class="list-items ' + isActive + ' ' + sectionNo + ' ' + '">' +
            '<div class="column-1 users">  <img src="' + bets[i].image + '" class="avatar me-1"> ' + bets[i].userid + ' </div>' +
            '<div class="column-2"> <button class="btn btn-transparent previous-history d-flex align-items-center mx-auto"> ' + bets[i].amount + currency_symbol + ' </button> </div>' +
            '<div class="column-3"> ' + multiplication + ' </div>' +
            '<div class="column-4"> ' + cashOut + ' </div>' +
            '</div>';
    }
    $("#all_bets").html(html);
}

function getAllbets() {
    $.ajax({
        url: '/user/play/aviatorbets',
        type: "POST",
        data: {
            _token: hash_id
        },
        dataType: "json",
        success: function (intialData) {
            updateBetlists(intialData);
        }
    });
}