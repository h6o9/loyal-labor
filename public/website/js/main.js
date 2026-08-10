const allowedUrls = [VIEW_SINGLE_PRODUCT];

$.ajaxPrefilter(function(options, originalOptions, jqXHR) {
    if (allowedUrls.includes(options.url)) {
        return;
    }

    if (APP_MODE_DEMO && options.type.toUpperCase() === 'POST') {
        toastr.error(DEMO_ERROR_TEXT);
        $('.preloader_area').addClass('d-none');
        jqXHR.abort();
        console.warn('Blocked AJAX POST request in demo mode:', options.url);
    }
});

function pushToDataLayer(eventData) {
    if (typeof window.dataLayer !== 'undefined' && GTM_ON) {
        window.dataLayer.push(eventData);
    }
}

function handleError(error) {
    if (error.status == 500) {
        toastr.error(ERROR_TEXT);
    } else {
        toastr.error(error.responseJSON.message)
    }
}

function formatCurrencyJs(price, showIcon = true) {
    const rate = parseFloat(CURRENCY_RATE) || 1;
    const icon = CURRENCY_ICON || '$';
    const position = CURRENCY_POSITION || 'before_price';

    const convertedAmount = parseFloat(price) * rate;

    const formattedNumber = convertedAmount.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    let formattedPrice = '';
    switch (position) {
        case 'before_price':
            formattedPrice = showIcon ? `${icon}${formattedNumber}` : formattedNumber;
            break;
        case 'before_price_with_space':
            formattedPrice = showIcon ? `${icon} ${formattedNumber}` : formattedNumber;
            break;
        case 'after_price':
            formattedPrice = showIcon ? `${formattedNumber}${icon}` : formattedNumber;
            break;
        case 'after_price_with_space':
            formattedPrice = showIcon ? `${formattedNumber} ${icon}` : formattedNumber;
            break;
        default:
            formattedPrice = showIcon ? `${icon}${formattedNumber}` : formattedNumber;
            break;
    }

    return formattedPrice;
}

function bindVariantSelector(variants) {
    $(document).off('click', ".attr").on('click', ".attr", function () {
        $(this).toggleClass('selectedAttr active');
        $(this).siblings().removeClass('selectedAttr active');

        let selectedIds = [];
        $('.selectedAttr').each(function () {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            $('#is_variant').val(0);
            $("#show_price").html($("#show_price").data("original-price"));
            updateQtyPrice();
        } else {
            $('#is_variant').val(1);
        }

        // Match variant
        let selected_variant = variants.find(function (variant) {
            return variant.attribute_value_ids.sort().toString() === selectedIds.sort().toString();
        });

        // Show image
        let image = $(this).data('image') || (selected_variant?.image ?? null);
        if (image) {
            $('.slick-slide img[src="' + image + '"]').closest('.slick-slide').click();
        }

        // Update UI
        if (selected_variant) {
            const isDiscounted = selected_variant.discount?.is_discounted;

            $("#variant_price").val(isDiscounted
                ? selected_variant.discount.discounted_price
                : selected_variant.price);

            $("#variant_sku").val(selected_variant.sku);

            const priceHtml = isDiscounted
                ? `${selected_variant.discounted_currency_discount_price}<del>${selected_variant.discounted_currency_price}</del>`
                : selected_variant.currency_price;

            $("#show_price").html(priceHtml);
            $(".productPrice").text(selected_variant.currency_price);
            $(".sku").text(selected_variant.sku);
            $('input[name="qty"]').val(1);
            $('#is_variant').val(1);
            updateQtyPrice();
        }
    });
}

function autoSelectDefaultVariant(variants) {
    let selectedIds = [];

    $('.attr.selectedAttr').each(function () {
        selectedIds.push($(this).data('id'));
    });

    if (!selectedIds.length) {
        $('#is_variant').val(0);
        return;
    }

    $('#is_variant').val(1);

    let selected_variant = variants.find(v =>
        v.attribute_value_ids.sort().toString() === selectedIds.sort().toString()
    );

    if (selected_variant) {
        const isDiscounted = selected_variant.discount?.is_discounted;

        $("#variant_price").val(isDiscounted
            ? selected_variant.discount.discounted_price
            : selected_variant.price);

        $("#variant_sku").val(selected_variant.sku);

        const priceHtml = isDiscounted
            ? `${selected_variant.discounted_currency_discount_price}<del>${selected_variant.discounted_currency_price}</del>`
            : selected_variant.currency_price;

        $("#show_price").html(priceHtml);
        $(".productPrice").text(selected_variant.currency_price);
        $(".sku").text(selected_variant.sku);
        $('input[name="qty"]').val(1);
        updateQtyPrice();
    }
}

function updateVarientAttr(variants){
    bindVariantSelector(variants);
    autoSelectDefaultVariant(variants);
}

function removeCurrency(value) {
    return value.replace(/[^0-9.]/g, '');
}

$(function () {
    "use strict";

    // ajax setup
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN
        }
    });

    //=======MENU FIX======
    if ($(window).scrollTop() > 140) {
        if ($('.wsus__main_menu').offset() != undefined) {
            $('.wsus__main_menu').addClass('menu_fix');
        }
    } else {
        if ($('.wsus__main_menu').offset() != undefined) {
            $('.wsus__main_menu').removeClass('menu_fix');
        }
    }

    $(window).scroll(function () {
        if ($(this).scrollTop() > 140) {

            if ($('.wsus__main_menu').offset() != undefined) {
                // check if menu_if class is already added
                if (!$('.wsus__main_menu').hasClass("menu_fix")) {
                    $('.wsus__main_menu').addClass("menu_fix");
                }
            }
        } else {
            if ($('.wsus__main_menu').offset() != undefined) {
                $('.wsus__main_menu').removeClass("menu_fix");
            }
        }
    });

    //=======SELECT JS======
    $('.select_js').niceSelect();

    //=======SELECT_2 JS======
    $(document).ready(function () {
        $('.select_2').select2();
    });

    //=======venobox js=========
    $('.venobox').venobox();

    // counter js
    $('.count').countUp();

    // Range Slider
    $('.basic').alRangeSlider();
    const options = {
        range: {
            min: 10,
            max: 1000,
            step: 1
        },
        initialSelectedValues: {
            from: 200,
            to: 800
        },
        grid: {
            minTicksStep: 1,
            marksStep: 5
        },
        theme: "dark",
    };

    $('.range_slider').alRangeSlider(options);
    const options2 = {
        orientation: "vertical"
    };

    //=======CATEGORY 1 JS======
    $('.category_one').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [{
            breakpoint: 992,
            settings: {
                slidesToShow: 4,
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 3,
                arrows: false,
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 2,
                arrows: false,
            }
        }
        ]
    });

    //=======PRODUCTS 1 JS======
    $('.product_one').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1401,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======PRODUCTS SLIDER======
    $('.slider-forOne').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: false,
        verticalSwiping: true,
        asNavFor: '.slider-navOne',

        responsive: [
            {
                breakpoint: 576,
                settings: {
                    verticalSwiping: false,
                }
            }
        ]
    });

    $('.slider-navOne').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.slider-forOne',
        arrows: false,
        dots: false,
        centerMode: true,
        focusOnSelect: true,
        vertical: true,
        centerPadding: 0,

        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    });

    //=======PRODUCTS COMBO JS======
    $('.product_combo').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======BRANDING JS======
    $('.brand_slide').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: false,

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 6,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 3,
                }
            }
        ]
    });

    //=======FEATURE PRODUCT JS======
    $('.feture_product_slide').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======FLASH DEALS JS======
    $('.flash_deals_slide').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======TESTIMONIAL JS======
    $('.testimonial_slide').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======BANNER 2 SLIDER======
    $('.banner_slider_2').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: true,
        arrows: false,
        fade: true,

        responsive: [{
            breakpoint: 1200,
            settings: {
                slidesToShow: 1,
                // dots: false,
            }
        },
        {
            breakpoint: 992,
            settings: {
                slidesToShow: 1,
                // dots: false,
            }
        },
        {
            breakpoint: 768,
            settings: {
                slidesToShow: 1,
                dots: false,
            }
        },
        {
            breakpoint: 576,
            settings: {
                slidesToShow: 1,
                dots: false,
            }
        }
        ]

    });

    //=======CATEGORY 2 JS======
    $('.categories_2_slider').slick({
        slidesToShow: 5,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            }
        ]
    });

    //=======PRODUCTS 2 JS======
    $('.product_2_slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======COUNTDOWN  ONE======
    var d = new Date(),
        countUpDate = new Date();
    d.setDate(d.getDate() + 5);

    // default example
    simplyCountdown('.simply-countdown-one', {
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate(),
        enableUtc: true
    });

    //=======COUNTDOWN TWO======
    var d = new Date(),
        countUpDate = new Date();
    d.setDate(d.getDate() + 50);

    // default example
    simplyCountdown('.simply-countdown-two', {
        year: d.getFullYear(),
        month: d.getMonth() + 1,
        day: d.getDate(),
        enableUtc: true
    });

    //=======CATEGORY 3 JS======
    $('.category_3_slider').slick({
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [
            {
                breakpoint: 1400,
                settings: {
                    slidesToShow: 5,
                }
            },
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            }
        ]
    });

    //=======LATEST DEALS 3 JS======
    $('.latest_deals_3').slick({
        slidesToShow: 2,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======POPULAR PRODUCT 3 JS======
    $('.popular_product_3').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======TOP PRODUCT 3 JS======
    $('.top_product_3').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        vertical: true,
        nextArrow: '<i class="far fa-angle-up nextArrow"></i>',
        prevArrow: '<i class="far fa-angle-down prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 4,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 4,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 4,
                    arrows: false,
                }
            }
        ]
    });

    //=======FLASH DEALS 4 JS======
    $('.flash_deals_3').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 3,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    //=======BLOG 4 JS======
    $('.blog_4').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        dots: false,
        arrows: true,
        nextArrow: '<i class="far fa-arrow-right nextArrow"></i>',
        prevArrow: '<i class="far fa-arrow-left prevArrow"></i>',

        responsive: [

            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                }
            }
        ]
    });

    $(function () {
        $(".color").hover(
            function () {
                var c = $(this).css("background-color");
                $(".line").css("background-color", c);
            },
            function () {
                $(".line").css("background-color", "#141414");
                $(".dark .line").css("background-color", "#eee");
            }
        );
    });

    //=====SCROLL_BAR========
    var btn = $('#button');

    $(window).scroll(function () {
        if ($(window).scrollTop() > 300) {
            btn.addClass('show');
        } else {
            btn.removeClass('show');
        }
    });

    btn.on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, '300');
    });

    //=====STICKY MENU========
    const mobileMenu = document.querySelectorAll(".mobile_dropdown");

    mobileMenu.forEach((dropdown) => {
        const innerMenu = dropdown.querySelector(".inner_menu");

        if (!innerMenu) return;

        // Set initial state
        innerMenu.style.overflow = "hidden";
        innerMenu.style.transition = "max-height 0.3s ease";
        innerMenu.style.maxHeight = "0px";

        dropdown.addEventListener("click", () => {
            const isOpen = innerMenu.style.maxHeight !== "0px" && innerMenu.style.maxHeight !== "";

            // Close all other dropdowns
            mobileMenu.forEach((item) => {
                const menu = item.querySelector(".inner_menu");
                if (!menu) return;

                if (menu !== innerMenu) {
                    menu.style.maxHeight = "0px";
                    item.classList.remove("active");
                }
            });

            if (isOpen) {
                innerMenu.style.maxHeight = "0px";
                dropdown.classList.remove("active");
            } else {
                innerMenu.style.maxHeight = innerMenu.scrollHeight + "px";
                dropdown.classList.add("active");
            }
        });
    });

    //=====WOW ANIMATION========
    new WOW().init();

    //======SIDEBAR PRODUCT FILTER======
    $(".wsus__sidebar_wizard_filter").on("click", function () {
        $(".wsus__sidebar_wizard_filter").toggleClass("show");
    });
    $(".wsus__sidebar_wizard_filter").on("click", function () {
        $(".wsus__sidebar_wizard_area").toggleClass("show");
    });

    if (isLanguageChangeAvailable == 1) {
        $('.language_change_select').on('change', function () {
            var language = $(this).val();

            if (language) {
                window.location.href = setLanguageRoute + "?code=" + language;
            }
        });
    }

    if (isCurrencyChangeAvailable == 1) {
        $('.currency_change_select').on('change', function () {
            var currency = $(this).val();
            if (currency) {
                window.location.href = setCurrencyRoute + "?currency=" + currency;
            }
        });
    }

    $(document).on('click', '.add_to_wishlist', function (e) {
        e.preventDefault();

        if (AUTH_STATUS == 1) {
            var id = $(this).data('id');

            if (!id) {
                toastr.warning(EMPTY_PRODUCT_TEXT);
                return;
            }

            $.ajax({
                url: ADD_TO_WISHLIST_ROUTE,
                type: 'POST',
                data: JSON.stringify({
                    product_id: id
                }),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                beforeSend: function () {
                    $('.preloader_area').removeClass('d-none')
                },
                success: function (response) {
                    if (response.status == 'success') {
                        $('#wishlist_count').html(response.wishlist);

                        if (response.gtm) {
                            pushToDataLayer(response.gtm);
                        }
                    }
                    toastr.success(response.message);
                },
                error: function (error) {
                    handleError(error);
                },
                complete: function () {
                    $('.preloader_area').addClass('d-none')
                }
            });
        } else {
            $('.modal.show').modal('hide');
            $('#loginModal').modal('show');
        }
    });

    $('.view_single_product_modal').on('click', function () {
        let id = $(this).data('id');

        if (!id) {
            toastr.warning(EMPTY_PRODUCT_TEXT);
            return;
        }

        $.ajax({
            type: 'GET',
            url: VIEW_SINGLE_PRODUCT,
            data: {
                id: id
            },
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            beforeSend: function () {
                $('.preloader_area').removeClass('d-none')
            },
            success: function (response) {
                if (response.status == 'success') {
                    $('#product_modal_body').html(response.body);
                    $('#viewProductDetailsModal').modal('show');
                    updateVarientAttr(response.attributes || []);

                    if (response.gtm) {
                        pushToDataLayer(response.gtm);
                    }

                    if (FB_PIXEL_ON && response.pixel && typeof fbq === 'function') {
                        fbq('track', response.pixel.event, response.pixel.data);
                    }
                } else {
                    toastr.error(ERROR_TEXT);
                }
            },
            error: function (error) {
                handleError(error);
            },
            complete: function () {
                $('.preloader_area').addClass('d-none')
            }
        });
    });

    $('.add_to_cart').on('click', function (e) {
        e.preventDefault();

        if (HAS_APP == 1 && AUTH_STATUS != 1) {
            $('.modal.show').modal('hide');
            $('#loginModal').modal('show');
            return;
        }

        var product_id = $(this).data('product-id');
        var sku = $(this).data('sku');
        var stock_qty = $(this).data('stock-qty');
        var stock_managed = $(this).data('stock-managed');
        var stock_status = $(this).data('stock-status');
        var quantity = $(this).data('quantity');
        var variant_id = $(this).data('variant-id');
        var variant_sku = $(this).data('variant-sku');
        var allow_checkout_when_out_of_stock = $(this).data('allow-checkout-without-stock');

        if (allow_checkout_when_out_of_stock == 0) {
            if (stock_managed) {
                if (stock_status == 'out_of_stock') {
                    toastr.error(NO_STOCK_PRODUCT_TEXT);
                    return;
                }

                if (stock_qty < quantity) {
                    toastr.error(NO_STOCK_PRODUCT_TEXT);
                    return;
                }
            }
        }

        let formData = {
            product_id: product_id,
            sku: sku,
            qty: quantity,
        }

        if (variant_id) {
            formData.variant_id = variant_id;
        }

        if (variant_sku) {
            formData.variant_sku = variant_sku;
        }

        sendAddToCartAjax(formData);
    });

    $(document).on('click', '.add_cart', function (e) {
        e.preventDefault();

        const $button = $(this);
        let productId = $button.data('product-id');

        let isBuyNow = false;

        if ($button.hasClass('details_buy_btn')) {
            isBuyNow = true;
        }

        if (HAS_APP == 1 && AUTH_STATUS != 1) {
            $('.modal.show').modal('hide');
            $('#loginModal').modal('show');
            return;
        }

        let formData = $('#add_to_cart_form_p' + productId).serialize();

        sendAddToCartAjax(formData, isBuyNow);
    });

    $('.newsletter_form').on('submit', function (e) {
        e.preventDefault();

        const form = $(this);
        const email = form.find('input[name="email"]').val().trim();
        const csrf = form.find('input[name="_token"]').val() || CSRF_TOKEN;
        const actionUrl = form.attr('action');
        const termsCheckbox = form.find('input[name="terms"]');

        if (!email) {
            toastr.error(EMAIL_NOT_FOUND);
            return;
        }

        if (termsCheckbox.length && !termsCheckbox.is(':checked')) {
            toastr.error(TERMS_CHECKER);
            return;
        }

        $.ajax({
            url: actionUrl,
            method: 'POST',
            data: {
                _token: csrf,
                email: email
            },
            success: function (response) {
                toastr.success(response.message || SUBSCRIPTION_SUCCESS);
                form[0].reset();
            },
            error: function (xhr) {
                let errorMsg = ERROR_TEXT;
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                toastr.error(errorMsg);
            }
        });
    });
});

function sendAddToCartAjax(formData, isBuyNow = false) {
    $.ajax({
        type: 'POST',
        data: formData,
        url: ADD_TO_CART_ROUTE,
        headers: {
            'Accept': 'application/json'
        },
        beforeSend: function () {
            $('.preloader_area').removeClass('d-none');
        },
        success: function (response) {
            toastr.success(response.message)
            $("#cart_count").text(response.cartCount);
            $("#viewProductDetailsModal").modal('hide');

            if (isBuyNow) {
                window.location.href = CHECKOUT_ROUTE;
            }

            if (response.gtm) {
                pushToDataLayer(response.gtm);
            }

            if (FB_PIXEL_ON && response.pixel && typeof fbq === 'function') {
                fbq('track', response.pixel.event, response.pixel.data);
            }

            $('.preloader_area').addClass('d-none');
        },
        error: function (error) {
            handleError(error);
            $('.preloader_area').addClass('d-none');
        },
        complete: function () {
            $('.preloader_area').addClass('d-none');
        }
    });
}

$(function () {
    $('.top-offer-countdown').each(function () {
        var endDateStr = $(this).data('endDate');
        var parts = endDateStr.split('T')[0].split('-');
        var year = parseInt(parts[0], 10);
        var month = parseInt(parts[1], 10);
        var day = parseInt(parts[2], 10);

        simplyCountdown(this, {
            year: year,
            month: month,
            day: day,
            enableUtc: true
        });
    });

    $(".wsus__filter_btn").click(function () {
        $(".wsus__filter_btn").toggleClass("hide_plus");
    });
    $(".wsus__filter_btn").click(function () {
        $(".sidebar_form").toggleClass("show_sidebar");
    });
});

$(document).ready(function () {
    $('#viewProductDetailsModal').on('hidden.bs.modal', function () {
        $('#product_modal_body').html('');
    });
});

function updateQtyPrice() {
    let qty = parseInt($('input[name="qty"]').val()) || 1;
    let isVarient = $('#is_variant').val();

    let variantPrice = parseFloat($('#variant_price').val());
    let modalPrice = parseFloat($('#modal_price').val());

    let price = !isNaN(variantPrice) && variantPrice && isVarient == 1 > 0 ? variantPrice : modalPrice;

    let total = qty * price;

    $('#qty_price').text(formatCurrencyJs(total));
}

updateQtyPrice();

$(document).on('click', '.increase-btn', function() {
    let input = $('input[name="qty"]');
    let value = parseInt(input.val()) || 0;
    value++;
    input.val(value);
    updateQtyPrice();
});

$(document).on('click', '.decrease-btn', function() {
    let input = $('input[name="qty"]');
    let value = parseInt(input.val()) || 1;
    value--;
    if (value < 1) value = 1;
    input.val(value);
    updateQtyPrice();
});

$(document).on('input', 'input[name="qty"]', function() {
    updateQtyPrice();
});
