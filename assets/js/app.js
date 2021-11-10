$(function () {
    // fullpage
    const myFullpage = new fullpage('#fullpage', {
        controlArrows: false,
        scrollOverflow: true,
        fixedElements: '#privacy_modal',
        normalScrollElements: '.more-moto-info, .modal, .modal-content, #contact_form',
        scrollOverflowOptions: {
            // bounce: false,
            // momentum: false,
            useTransition: false,
            deceleration: 0.001,
            preventDefault: false,
            preventDefaultException: {
                className: /(^|\s)form-title|form-group|form-wrap|vs__selected|vs__dropdown-menu|vs-select|vs__dropdown-option|vs__actions|vs__open-indicator|vs__clear|vs__dropdown-toggle(\s|$)/,
                tagName: /^(INPUT|TEXTAREA|BUTTON|SELECT|LABEL|SVG|PATH)$/,
            },
        },
        paddingTop: '10vh',
        // paddingBottom: '10vh',
    });

    $('.go-down-histoire').click(function (e) {
        fullpage_api.moveSectionDown();
        e.preventDefault();
    });

    $('.go-up').click(function (e) {
        fullpage_api.moveTo(1);
        e.preventDefault();
    });

    var windowWidth = $(window).innerWidth();
    if (windowWidth < 768) {
        fullpage_api.destroy('all');
        $('#emtest').css('position', 'relative');
    }

    $('.btn-hide-info').click(function (e) {
        $(this).parent().toggleClass('info-hide');
        e.preventDefault();
    });

    $('.btn-open-menu').click(function (e) {
        $('.menu-mobile').toggleClass('menu-mobile-actif');
        e.preventDefault();
    });

    $('.switcher').click(function () {
        if ($('.switcher-panel').is(':visible')) {
            $('.switcher-btn').css('opacity', 1);
            $(this).find('.switcher-panel').addClass('switcher-panel-close');
        } else {
            $('.switcher-btn').css('opacity', 0.3);
            $(this).find('.switcher-panel').removeClass('switcher-panel-close');
        }
    });

});