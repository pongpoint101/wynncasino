$(function () {
    var turnOnInterval = true;
    var snowingEffect = $('body').data('snowing-effect');
    if (snowingEffect == true) {
        $('body').wpSuperSnow({
            flakes: [window.baseContentUrl + 'Themes/Joker/Images/christmas/snow1.png', window.baseContentUrl + 'Themes/Joker/Images/christmas/snow2.png', window.baseContentUrl + 'Themes/Joker/Images/christmas/snow3.png'],
            totalFlakes: 50,
            maxSize: 28,
            maxDuration: 50,
            useFlakeTrans: false
        });
    }

    if ($(".background-star").length && turnOnInterval == true) {
        //star
        $(".background-star").animateSprite({
            fps: 24,
            animations: {
                walkLeft: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
            },
            loop: true,
            duration: 1500,
            complete: function () {
            }
        });
    }


    function displayEvent() {
        if ($('#event_new_year').length > 0) {
            if ($(window).width() < 1417) {
                $('#event_new_year').hide();
            }
            else {
                $('#event_new_year').show();
            }
        }
    }
    displayEvent();

    $(window).resize(function () {
        displayEvent();

        if ($(window).width() < 1024) {
            $('.line-banner').hide();
        }
        else {
            $('.line-banner').show();
        }
    });
})