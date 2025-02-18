$(".info-btn").click(function () {
    if ($(".info").hasClass("hide")) {
        $(".info").removeClass("hide");
        $(".info").addClass("show");
    } else {
        $(".info").removeClass("show");
        $(".info").addClass("hide");
    }
});
$(".dice1").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(1);
    playAudio("click.mp3");
});
$(".dice2").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(2);
    playAudio("click.mp3");
});
$(".dice3").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(3);
    playAudio("click.mp3");
});
$(".dice4").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(4);
    playAudio("click.mp3");
});
$(".dice5").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(5);
    playAudio("click.mp3");
});
$(".dice6").click(function () {
    $(this).addClass("op");
    $(".op").addClass("gmimg");
    $(this).removeClass("gmimg");
    $(".gmimg").removeClass("op");
    $("input[name=choose]").val(6);
    playAudio("click.mp3");
});

$("input[type=number]").on("keydown", function (e) {
    if (e.keyCode == 189) {
        return false;
    }
});

function issuccess() {
    $(".dices").find("img").removeClass("op");
    $("#game").find("input").not("input[name=type],input[name=_token]").val("");
    $("button[type=submit]").html("Play");
    $("button[type=submit]").removeAttr("disabled");
    $(".single-select").removeClass("active op");
}
function rolling(pos) {
    $("#dice").removeClass("diceRolling");
    $("#dice").addClass("rolling");
    var x = pos.x;
    var y = pos.y;
    var diceFrame = [
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${Math.floor(Math.random() * 360)}deg) rotateY(${Math.floor(Math.random() * 360)}deg) rotateZ(${Math.floor(Math.random() * 360)}deg)`,
        },
        {
            transform: `translateZ(-100px) rotateX(${x}) rotateY(${y}) rotateZ(360deg)`,
        },
    ];
    var diceRef = document.getElementById("dice");
    diceRef.animate(diceFrame, {
        duration: 5000,
        fill: "forwards",
        easing: "linear",
    });
}

function position(data) {
    if (data.result == 1) {
        pos = { x: "360deg", y: "0deg" };
    } else if (data.result == 2) {
        pos = { x: "270deg", y: "0deg" };
    } else if (data.result == 3) {
        pos = { x: "0deg", y: "270deg" };
    } else if (data.result == 4) {
        pos = { x: "0deg", y: "90deg" };
    } else if (data.result == 5) {
        pos = { x: "90deg", y: "0deg" };
    } else if (data.result == 6) {
        pos = { x: "180deg", y: "0deg" };
    }
    return pos;
}

function startGame(data) {
    var pos = position(data);
    rolling(pos);
    $("button[type=submit]").html('<i class="la la-gear fa-spin"></i> Playing...');
    timerA = setInterval(function () {
        issuccess();
        endGame(data);
    }, 5000);
}

function game(data, url) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: url,
        method: "POST",
        data: data,
        success: function (data) {
            if (data.errors) {
                notify("error", data.errors);
                issuccess();
                return false;
            }

            if (data.error) {
                notify("error", data.error);
                issuccess();
                return false;
            }
            $(".bal").text(parseFloat(data.balance).toFixed(2));
            startGame(data);
        },
    });
}

function complete(data, url) {
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: url,
        method: "POST",
        data: { game_id: data.game_id },
        success: function (data) {
            if (data.errors) {
                notify("error", data.errors);
                issuccess();
                return false;
            }

            if (data.error) {
                notify("error", data.error);
                issuccess();
                return false;
            }
            clearInterval(timerA);

            $(".win-loss-popup").addClass("active");
            $(".win-loss-popup__body").find("img").addClass("d-none");
            if (data.type == "success") {
                playAudio("win.wav");
                $(".win-loss-popup__body").find(".win").removeClass("d-none");
            } else {
                playAudio("lose.wav");
                $(".win-loss-popup__body").find(".lose").removeClass("d-none");
            }
            $(".win-loss-popup__footer").find(".data-result").text(data.result);

            var bal = parseFloat(data.bal);
            // $(".bal").html(bal.toFixed(2));
            $(".bal").html(data.bal);
            $(".single-select").removeClass("active op");
        },
    });
}
