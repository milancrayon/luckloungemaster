"use strict";

// menu options custom affix
var fixed_top = $(".header");
$(window).on("scroll", function () {
  if ($(window).scrollTop() > 50) {
    fixed_top.addClass("animated fadeInDown menu-fixed");
  } else {
    fixed_top.removeClass("animated fadeInDown menu-fixed");
  }
});

// Show or hide the sticky footer button
$(window).on("scroll", function () {
  if ($(this).scrollTop() > 200) {
    $(".scroll-to-top").fadeIn(200);
  } else {
    $(".scroll-to-top").fadeOut(200);
  }
});

// Animate the scroll to top
$(".scroll-to-top").on("click", function (event) {
  event.preventDefault();
  $("html, body").animate({ scrollTop: 0 }, 300);
});

$(".navbar-toggler").on("click", function () {
  if (!$(".header").hasClass("active")) {
    $(".header").addClass("active");
  } else if ($(".header").hasClass("active")) {
    $(".header").removeClass("active");
  }
});

// mobile menu js
$(".navbar-collapse>ul>li>a, .navbar-collapse ul.sub-menu>li>a").on(
  "click",
  function () {
    const element = $(this).parent("li");
    if (element.hasClass("open")) {
      element.removeClass("open");
      element.find("li").removeClass("open");
    } else {
      element.addClass("open");
      element.siblings("li").removeClass("open");
      element.siblings("li").find("li").removeClass("open");
    }
  }
);

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

new WOW().init();

// lightcase plugin init
$("a[data-rel^=lightcase]").lightcase();

$(".testimonial-slider").slick({
  slidesToShow: 2,
  slidesToScroll: 1,
  speed: 700,
  autoplay: true,
  dots: false,
  arrows: false,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 2,
      },
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 1,
      },
    },
  ],
});

// payment-slider
$(".payment-slider").slick({
  slidesToShow: 8,
  slidesToScroll: 1,
  autoplay: true,
  speed: 700,
  dots: false,
  arrows: false,
  responsive: [
    {
      breakpoint: 992,
      settings: {
        slidesToShow: 6,
      },
    },
    {
      breakpoint: 768,
      settings: {
        slidesToShow: 4,
      },
    },
    {
      breakpoint: 400,
      settings: {
        slidesToShow: 2,
      },
    },
  ],
});

// winner-slider js
$(".winner-slider").slick({
  autoplay: true,
  autoplaySpeed: 2000,
  dots: false,
  infinite: true,
  speed: 300,
  slidesToShow: 5,
  arrows: false,
  slidesToScroll: 1,
  cssEase: "cubic-bezier(0.645, 0.045, 0.355, 1.000)",
  vertical: true,
  speed: 1000,
  autoplaySpeed: 1000,
});

//preloader js code
$(".preloader")
  .delay(300)
  .animate(
    {
      opacity: "0",
    },
    300,
    function () {
      $(".preloader").css("display", "none");
    }
  );

$(".single-select").each(function () {
  $(this).on("click", function () {
    var element = $(this);
    if (element.hasClass("active")) {
      element.find(".single-select").removeClass("active");
    } else {
      element.addClass("active");
      element.siblings(".single-select").removeClass("active");
      element
        .siblings(".single-select")
        .find(".single-select")
        .removeClass("active");
    }
  });
});

$(".showFilterBtn").on("click", function () {
  $(".responsive-filter-card").slideToggle();
});

const HEADER = document.querySelector("header");
if (HEADER) {
  window.addEventListener("click", function (e) {
    if (!HEADER.contains(e.target) && HEADER.classList.contains("active")) {
      document.querySelector(".navbar-toggler").click();
    }
  });
}

const mine_box = document.getElementsByClassName("mine-box");

[...mine_box].forEach((item) => {
  item.addEventListener("click", () => {
    item.classList.add("active-hidden");
  });
});



function initOddsSlider(element = $(".option-odd-list")) {
  element.slick({
    dots: false,
    infinite: false,
    speed: 300,
    arrows: true,
    slidesToShow: 1,
    variableWidth: true,
    slidesToScroll: 1,
    prevArrow:
      '<button type="button" class="sports-category__arrow sports-category__arrow-prev"><i class="fas fa-angle-left"></i></button>',
    nextArrow:
      '<button type="button" class="sports-category__arrow sports-category__arrow-next slick-next"><i class="fas fa-angle-right"></i></button>',
  });

  $(element).each(function (index, element) {
    // let parentWidth = $(element).parents('.sports-card')[0].clientWidth;
    let arrowWidth = $(element).find(".slick-arrow")[0].clientWidth;
    let slickListWidth =
      $(element).find(".slick-list")[0].clientWidth - arrowWidth - 14;

    let width = 0;

    $($(element).find(".option-odd-list__item")).each(function (
      index,
      oddElement
    ) {
      width += oddElement.clientWidth + 6;
    });

    if (slickListWidth - width > 12) {
      $(element).find(".slick-arrow").hide();
    } else {
      $(element).find(".slick-arrow").show();
    }
  });
}

initOddsSlider();