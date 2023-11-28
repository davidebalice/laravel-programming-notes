!(function (e) {
    "function" == typeof define && define.amd
        ? define([], e)
        : "object" == typeof exports
        ? (module.exports = e())
        : (window.wNumb = e());
})(function () {
    "use strict";
    var o = [
        "decimals",
        "thousand",
        "mark",
        "prefix",
        "suffix",
        "encoder",
        "decoder",
        "negativeBefore",
        "negative",
        "edit",
        "undo",
    ];
    function w(e) {
        return e.split("").reverse().join("");
    }
    function h(e, t) {
        return e.substring(0, t.length) === t;
    }
    function f(e, t, n) {
        if ((e[t] || e[n]) && e[t] === e[n]) throw new Error(t);
    }
    function x(e) {
        return "number" == typeof e && isFinite(e);
    }
    function n(e, t, n, r, i, o, f, u, s, c, a, p) {
        var d,
            l,
            h,
            g = p,
            v = "",
            m = "";
        return (
            o && (p = o(p)),
            !!x(p) &&
                (!1 !== e && 0 === parseFloat(p.toFixed(e)) && (p = 0),
                p < 0 && ((d = !0), (p = Math.abs(p))),
                !1 !== e &&
                    (p = (function (e, t) {
                        return (
                            (e = e.toString().split("e")),
                            (+(
                                (e = (e = Math.round(
                                    +(e[0] + "e" + (e[1] ? +e[1] + t : t))
                                ))
                                    .toString()
                                    .split("e"))[0] +
                                "e" +
                                (e[1] ? e[1] - t : -t)
                            )).toFixed(t)
                        );
                    })(p, e)),
                -1 !== (p = p.toString()).indexOf(".")
                    ? ((h = (l = p.split("."))[0]), n && (v = n + l[1]))
                    : (h = p),
                t && (h = w((h = w(h).match(/.{1,3}/g)).join(w(t)))),
                d && u && (m += u),
                r && (m += r),
                d && s && (m += s),
                (m += h),
                (m += v),
                i && (m += i),
                c && (m = c(m, g)),
                m)
        );
    }
    function r(e, t, n, r, i, o, f, u, s, c, a, p) {
        var d,
            l = "";
        return (
            a && (p = a(p)),
            !(!p || "string" != typeof p) &&
                (u && h(p, u) && ((p = p.replace(u, "")), (d = !0)),
                r && h(p, r) && (p = p.replace(r, "")),
                s && h(p, s) && ((p = p.replace(s, "")), (d = !0)),
                i &&
                    (function (e, t) {
                        return e.slice(-1 * t.length) === t;
                    })(p, i) &&
                    (p = p.slice(0, -1 * i.length)),
                t && (p = p.split(t).join("")),
                n && (p = p.replace(n, ".")),
                d && (l += "-"),
                "" !== (l = (l += p).replace(/[^0-9\.\-.]/g, "")) &&
                    ((l = Number(l)), f && (l = f(l)), !!x(l) && l))
        );
    }
    function i(e, t, n) {
        var r,
            i = [];
        for (r = 0; r < o.length; r += 1) i.push(e[o[r]]);
        return i.push(n), t.apply("", i);
    }
    return function e(t) {
        if (!(this instanceof e)) return new e(t);
        "object" == typeof t &&
            ((t = (function (e) {
                var t,
                    n,
                    r,
                    i = {};
                for (
                    void 0 === e.suffix && (e.suffix = e.postfix), t = 0;
                    t < o.length;
                    t += 1
                )
                    if (void 0 === (r = e[(n = o[t])]))
                        "negative" !== n || i.negativeBefore
                            ? "mark" === n && "." !== i.thousand
                                ? (i[n] = ".")
                                : (i[n] = !1)
                            : (i[n] = "-");
                    else if ("decimals" === n) {
                        if (!(0 <= r && r < 8)) throw new Error(n);
                        i[n] = r;
                    } else if (
                        "encoder" === n ||
                        "decoder" === n ||
                        "edit" === n ||
                        "undo" === n
                    ) {
                        if ("function" != typeof r) throw new Error(n);
                        i[n] = r;
                    } else {
                        if ("string" != typeof r) throw new Error(n);
                        i[n] = r;
                    }
                return (
                    f(i, "mark", "thousand"),
                    f(i, "prefix", "negative"),
                    f(i, "prefix", "negativeBefore"),
                    i
                );
            })(t)),
            (this.to = function (e) {
                return i(t, n, e);
            }),
            (this.from = function (e) {
                return i(t, r, e);
            }));
    };
});

(function ($) {
    ("use strict");
    $(window).on("load", function () {
        $("#preloader-active").delay(250).fadeOut("slow");
        $("body").delay(250).css({
            overflow: "visible",
        });
        $("#onloadModal").modal("show");
    });

    var header = $(".sticky-bar");
    var win = $(window);
    win.on("scroll", function () {
        var scroll = win.scrollTop();
        if (scroll < 200) {
            header.removeClass("stick");
            $(".header-style-2 .categories-dropdown-active-large").removeClass(
                "open"
            );
            $(".header-style-2 .categories-button-active").removeClass("open");
        } else {
            header.addClass("stick");
        }
    });

    $.scrollUp({
        scrollText: '<i class="fi-rs-arrow-small-up"></i>',
        easingType: "linear",
        scrollSpeed: 900,
        animation: "fade",
    });

    new WOW().init();

    if ($(".sticky-sidebar").length) {
        $(".sticky-sidebar").theiaStickySidebar();
    }

    if ($("#slider-range").length) {
        $(".noUi-handle").on("click", function () {
            $(this).width(50);
        });
        var rangeSlider = document.getElementById("slider-range");
        var moneyFormat = wNumb({
            decimals: 0,
            thousand: ",",
            prefix: "$",
        });
        noUiSlider.create(rangeSlider, {
            start: [500, 1000],
            step: 1,
            range: {
                min: [0],
                max: [2000],
            },
            format: moneyFormat,
            connect: true,
        });

        rangeSlider.noUiSlider.on("update", function (values, handle) {
            document.getElementById("slider-range-value1").innerHTML =
                values[0];
            document.getElementById("slider-range-value2").innerHTML =
                values[1];
            document.getElementsByName("min-value").value = moneyFormat.from(
                values[0]
            );
            document.getElementsByName("max-value").value = moneyFormat.from(
                values[1]
            );
        });
    }

    $(".hero-slider-1").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        loop: true,
        dots: true,
        arrows: true,
        prevArrow:
            '<p class="slider-btn slider-prev" style="padding-top:2px !important"><i class="fi-rs-angle-left"></i></p>',
        nextArrow:
            '<p class="slider-btn slider-next" style="padding-top:2px !important"><i class="fi-rs-angle-right"></i></p>',
        appendArrows: ".hero-slider-1-arrow",
        autoplay: true,
    });

    $(".carausel-8-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 8,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
            ],
            prevArrow:
                '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow:
                '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName,
        });
    });

    $(".carausel-10-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: false,
            slidesToShow: 10,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                    },
                },
            ],
            prevArrow:
                '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow:
                '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName,
        });
    });

    $(".carausel-4-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 4,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
            prevArrow:
                '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow:
                '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName,
        });
    });

    $(".carausel-3-columns").each(function (key, item) {
        var id = $(this).attr("id");
        var sliderID = "#" + id;
        var appendArrowsClassName = "#" + id + "-arrows";

        $(sliderID).slick({
            dots: false,
            infinite: true,
            speed: 1000,
            arrows: true,
            autoplay: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            loop: true,
            adaptiveHeight: true,
            responsive: [
                {
                    breakpoint: 1025,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                    },
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    },
                },
            ],
            prevArrow:
                '<span class="slider-btn slider-prev"><i class="fi-rs-arrow-small-left"></i></span>',
            nextArrow:
                '<span class="slider-btn slider-next"><i class="fi-rs-arrow-small-right"></i></span>',
            appendArrows: appendArrowsClassName,
        });
    });

    $('button[data-bs-toggle="tab"]').on("shown.bs.tab", function (e) {
        $(".carausel-4-columns").slick("setPosition");
    });

    $("[data-countdown]").each(function () {
        var $this = $(this),
            finalDate = $(this).data("countdown");
        $this.countdown(finalDate, function (event) {
            $(this).html(
                event.strftime(
                    "" +
                        '<span class="countdown-section"><span class="countdown-amount hover-up">%D</span><span class="countdown-period"> days </span></span>' +
                        '<span class="countdown-section"><span class="countdown-amount hover-up">%H</span><span class="countdown-period"> hours </span></span>' +
                        '<span class="countdown-section"><span class="countdown-amount hover-up">%M</span><span class="countdown-period"> mins </span></span>' +
                        '<span class="countdown-section"><span class="countdown-amount hover-up">%S</span><span class="countdown-period"> sec </span></span>'
                )
            );
        });
    });

    $(".product-slider-active-1").slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        fade: false,
        loop: true,
        dots: false,
        arrows: true,
        prevArrow:
            '<span class="pro-icon-1-prev"><i class="fi-rs-angle-small-left"></i></span>',
        nextArrow:
            '<span class="pro-icon-1-next"><i class="fi-rs-angle-small-right"></i></span>',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    $(".testimonial-active-1").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: false,
        arrows: true,
        prevArrow:
            '<span class="pro-icon-1-prev"><i class="fi-rs-angle-small-left"></i></span>',
        nextArrow:
            '<span class="pro-icon-1-next"><i class="fi-rs-angle-small-right"></i></span>',
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    $(".testimonial-active-3").slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 1,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    $(".categories-slider-1").slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        fade: false,
        loop: true,
        dots: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 1199,
                settings: {
                    slidesToShow: 4,
                },
            },
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 2,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 1,
                },
            },
        ],
    });

    var searchToggle = $(".categories-button-active");
    searchToggle.on("click", function (e) {
        e.preventDefault();
        if ($(this).hasClass("open")) {
            $(this).removeClass("open");
            $(this)
                .siblings(".categories-dropdown-active-large")
                .removeClass("open");
        } else {
            $(this).addClass("open");
            $(this)
                .siblings(".categories-dropdown-active-large")
                .addClass("open");
        }
    });

    /*---------------------
        Price range
    --------------------- */
    // if ($("#slider-range").length) {
    //     var sliderrange = $("#slider-range");
    //     var amountprice = $("#amount");
    //     $(function () {
    //         sliderrange.slider({
    //             range: true,
    //             min: 16,
    //             max: 400,
    //             values: [0, 300],
    //             slide: function (event, ui) {
    //                 amountprice.val("$" + ui.values[0] + " - $" + ui.values[1]);
    //             }
    //         });
    //         amountprice.val("$" + sliderrange.slider("values", 0) + " - $" + sliderrange.slider("values", 1));
    //     });
    // }

    if ($(".sort-by-product-area").length) {
        var $body = $("body"),
            $cartWrap = $(".sort-by-product-area"),
            $cartContent = $cartWrap.find(".sort-by-dropdown");
        $cartWrap.on("click", ".sort-by-product-wrap", function (e) {
            e.preventDefault();
            var $this = $(this);
            if (!$this.parent().hasClass("show")) {
                $this
                    .siblings(".sort-by-dropdown")
                    .addClass("show")
                    .parent()
                    .addClass("show");
            } else {
                $this
                    .siblings(".sort-by-dropdown")
                    .removeClass("show")
                    .parent()
                    .removeClass("show");
            }
        });
        /*Close When Click Outside*/
        $body.on("click", function (e) {
            var $target = e.target;
            if (
                !$($target).is(".sort-by-product-area") &&
                !$($target).parents().is(".sort-by-product-area") &&
                $cartWrap.hasClass("show")
            ) {
                $cartWrap.removeClass("show");
                $cartContent.removeClass("show");
            }
        });
    }

    $(".shop-filter-toogle").on("click", function (e) {
        e.preventDefault();
        $(".shop-product-fillter-header").slideToggle();
    });
    var shopFiltericon = $(".shop-filter-toogle");
    shopFiltericon.on("click", function () {
        $(".shop-filter-toogle").toggleClass("active");
    });

    $(".pro-dec-big-img-slider").slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        draggable: false,
        fade: false,
        asNavFor: ".product-dec-slider-small , .product-dec-slider-small-2",
    });

    $(".product-dec-slider-small").slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: ".pro-dec-big-img-slider",
        dots: false,
        focusOnSelect: true,
        fade: false,
        arrows: false,
        responsive: [
            {
                breakpoint: 991,
                settings: {
                    slidesToShow: 3,
                },
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 4,
                },
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                },
            },
        ],
    });

    $(".img-popup").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });

    $(".select-active").select2();

    $(".checkout-click1").on("click", function (e) {
        e.preventDefault();
        $(".checkout-login-info").slideToggle(900);
    });

    $(".checkout-click3").on("click", function (e) {
        e.preventDefault();
        $(".checkout-login-info3").slideToggle(1000);
    });

    $(".checkout-toggle2").on("click", function () {
        $(".open-toggle2").slideToggle(1000);
    });

    $(".checkout-toggle").on("click", function () {
        $(".open-toggle").slideToggle(1000);
    });

    paymentMethodChanged();
    function paymentMethodChanged() {
        var $order_review = $(".payment-method");

        $order_review.on("click", 'input[name="payment_method"]', function () {
            var selectedClass = "payment-selected";
            var parent = $(this).parents(".sin-payment").first();
            parent
                .addClass(selectedClass)
                .siblings()
                .removeClass(selectedClass);
        });
    }

    $(".count").counterUp({
        delay: 10,
        time: 2000,
    });

    $(".grid").imagesLoaded(function () {
        // init Isotope
        var $grid = $(".grid").isotope({
            itemSelector: ".grid-item",
            percentPosition: true,
            layoutMode: "masonry",
            masonry: {
                // use outer width of grid-sizer for columnWidth
                columnWidth: ".grid-item",
            },
        });
    });

    function sidebarSearch() {
        var searchTrigger = $(".search-active"),
            endTriggersearch = $(".search-close"),
            container = $(".main-search-active");

        searchTrigger.on("click", function (e) {
            e.preventDefault();
            container.addClass("search-visible");
        });

        endTriggersearch.on("click", function () {
            container.removeClass("search-visible");
        });
    }
    sidebarSearch();

    function mobileHeaderActive() {
        var navbarTrigger = $(".burger-icon"),
            endTrigger = $(".mobile-menu-close"),
            container = $(".mobile-header-active"),
            wrapper4 = $("body");

        wrapper4.prepend('<div class="body-overlay-1"></div>');

        navbarTrigger.on("click", function (e) {
            e.preventDefault();
            container.addClass("sidebar-visible");
            wrapper4.addClass("mobile-menu-active");
        });

        endTrigger.on("click", function () {
            container.removeClass("sidebar-visible");
            wrapper4.removeClass("mobile-menu-active");
        });

        $(".body-overlay-1").on("click", function () {
            container.removeClass("sidebar-visible");
            wrapper4.removeClass("mobile-menu-active");
        });
    }
    mobileHeaderActive();

    var $offCanvasNav = $(".mobile-menu"),
        $offCanvasNavSubMenu = $offCanvasNav.find(".dropdown");

    $offCanvasNavSubMenu
        .parent()
        .prepend(
            '<span class="menu-expand"><i class="fi-rs-angle-small-down"></i></span>'
        );

    $offCanvasNavSubMenu.slideUp();

    $offCanvasNav.on("click", "li a, li .menu-expand", function (e) {
        var $this = $(this);
        if (
            $this
                .parent()
                .attr("class")
                .match(
                    /\b(menu-item-has-children|has-children|has-sub-menu)\b/
                ) &&
            ($this.attr("href") === "#" || $this.hasClass("menu-expand"))
        ) {
            e.preventDefault();
            if ($this.siblings("ul:visible").length) {
                $this.parent("li").removeClass("active");
                $this.siblings("ul").slideUp();
            } else {
                $this.parent("li").addClass("active");
                $this
                    .closest("li")
                    .siblings("li")
                    .removeClass("active")
                    .find("li")
                    .removeClass("active");
                $this.closest("li").siblings("li").find("ul:visible").slideUp();
                $this.siblings("ul").slideDown();
            }
        }
    });

    $(".mobile-language-active").on("click", function (e) {
        e.preventDefault();
        $(".lang-dropdown-active").slideToggle(900);
    });

    $(".categories-button-active-2").on("click", function (e) {
        e.preventDefault();
        $(".categori-dropdown-active-small").slideToggle(900);
    });

    var demo = $(".tm-demo-options-wrapper");
    $(".view-demo-btn-active").on("click", function (e) {
        e.preventDefault();
        demo.toggleClass("demo-open");
    });

    $(".more_slide_open").slideUp();
    $(".more_categories").on("click", function () {
        $(this).toggleClass("show");
        $(".more_slide_open").slideToggle();
    });

    $(".modal").on("shown.bs.modal", function (e) {
        $(".product-image-slider").slick("setPosition");
        $(".slider-nav-thumbnails").slick("setPosition");

        $(".product-image-slider .slick-active img").elevateZoom({
            zoomType: "inner",
            cursor: "crosshair",
            zoomWindowFadeIn: 500,
            zoomWindowFadeOut: 750,
        });
    });

    $("#news-flash").vTicker({
        speed: 500,
        pause: 3000,
        animation: "fade",
        mousePause: false,
        showItems: 1,
    });
})(jQuery);
