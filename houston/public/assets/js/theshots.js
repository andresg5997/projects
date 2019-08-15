
$(function() {

    "use strict";

    $('.header-shot-slider').lightSlider({
        item: 1,
        auto: true,
        loop: true,
        slideMargin: 10,
        controls: false,
        pauseOnHover: true
    });

    // Back to top
    $('#scroll-up').on( 'click', function() {
        $('html, body').animate({scrollTop : 0}, 600);
        return false;
    });

    // Image gallery
    // initGallery();

    // Smoothscroll to anchor
    $('a[href^="#"]:not([href="#"])').on('click', function(){
        var id = $(this).attr('href');
        if ($(id).size() > 0) {
            $('html, body').animate({scrollTop: $(id).offset().top}, 600);
        }
        return false;
    });

    // Smoothscroll to anchor in page load
    var hash = location.hash.replace('#','');
    if (hash != '' && $("#"+hash).size() > 0) {
        $('html, body').animate({scrollTop: $("#"+hash).offset().top-100}, 600);
    }

    // Switchery plugin
    if ($('.js-switch').length) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html, { size: 'small' });
        });
    }

    if ($('.js-switch-big').length) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch-big'));
        elems.forEach(function(html) {
            var switchery = new Switchery(html);
        });
    }

    // Search
    $('.search-opener').on('click', function(){
        $('body').css('overflow', 'hidden');
        $('.search-screen').toggleClass('closed opened');
        $('.search-form input[type="text"]').focus();
    });

    $('.search-closer').on('click', function(){
        $('body').css('overflow', 'auto');
        $('.search-screen').toggleClass('closed opened');
    });

    // Toggle header form visibility
    $('.toggle-form-visibility').on('click', function() {
        $(this).parents('.header-form').parent('div').children('.header-form').toggleClass('visible');
    });

    // Equal height for grid view
    // if there are not
    const height = $('.col8 > div').height();
    if (height) {
        $('.equal-cols > div').matchHeight({
            target: $('.col8 > div')
        });
        // matchHeight sets a floored height, e.g. 348.25px would set everything to 348px
        $('.col8 > div').height(height);
    }
    else {
        $('.equal-cols > div').matchHeight({
            target: $('.col1 > div')
        });
        // matchHeight sets a floored height, e.g. 348.25px would set everything to 348px
        const height = $('.col1 > div').height();
        $('.col1 > div').height(height);
    }

    $('.equal-blocks .card-block').matchHeight();


    // Add a .body-scrolled to body, when page scrolled
    $(window).on('scroll', function() {
        if ($(document).scrollTop() > 40) {
            $('body.sticky-nav').addClass('body-scrolled');
        }
        else {
            $('body.sticky-nav').removeClass('body-scrolled');
        }
    });

    //
    // Offcanvas
    //
    $('[data-toggle="offcanvas"]').on('click', function (e) {

        e.preventDefault();

        $('body').toggleClass('offcanvas-show');
        $(this).children().toggleClass('ti-menu ti-close');

        if ($('body').hasClass('offcanvas-show')) {
            $('html').css('overflow', 'hidden');
        }
        else {
            $('html').css('overflow', 'visible');
        }

    });

    $(window).on('resize', function(){
        if ($(window).width() > 991) {
            $('body').removeClass('offcanvas-show');
            $('html').css('overflow', 'visible');
        }
    });

});
