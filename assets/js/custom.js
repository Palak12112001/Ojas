$(document).ready(function () {
    jQuery(window).scroll(function(){
        var sticky = jQuery('header'),
          scroll = jQuery(window).scrollTop();

        if (scroll >= 112) sticky.addClass('fixed');
        else sticky.removeClass('fixed');
    });



    $('.btn-menu').on('click', function () {
        $('.center-menu').toggleClass('active');
        $('.btn-menu').toggleClass('active');
    });

    $('.btn-account').on('click', function () {
        $('.sidebar').toggleClass('active');
        $('body').toggleClass('bg');
    });

    $('.btn-close').on('click', function () {
        $('.sidebar').toggleClass('active');
        $('body').toggleClass('bg');
    });
    $('.openpopup').on('click', function () {
        $('.custom-popup').toggleClass('open');
    });
    $('.close-popup').on('click', function () {
        $('.custom-popup').toggleClass('open');
    });


});
