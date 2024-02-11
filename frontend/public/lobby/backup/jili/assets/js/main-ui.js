$(function () {
    var isTest = false;
    var turnOnInterval = true;
    var scrollTopOffset = 220;
    var scrollDuration = 500;

    //Jacpot-UI
    var $jackpotContainer = $('.jackpot-container, .game-jackpot-container');

    //Announcement
    var $marquee =  $('.marquee').marquee({
        duration: 10000,
        gap: 50,
        pauseOnHover: true,
        delayBeforeStart: 0,
        direction: 'left',
        allowCss3Support: true
    });
    $('.marquee').css({ 'margin-left': '-2px' });

    // Download sidebar
    downloadAutoResizable();

    $(window).on('scroll', function () {
        downloadAutoResizable();
    });

    if (screen.height <= 921) {
        $("#slider .inner-box").mCustomScrollbar({
            axis: "yx",
            theme: 'minimal',
            autoHideScrollbar: true,
            advanced: { updateOnContentResize: true }
        });
    }

    $(".custom-scrollbar").mCustomScrollbar({
        axis: "yx",
        theme: 'minimal'
    });
    
    settings();
    
    if (turnOnInterval) {
       

        //light jackpot
        $(".jackpot-light-circle").animateSprite({
            fps: 3,
            animations: {
                walkLeft: [0, 1, 2]
            },
            loop: true,
            duration: 300,
            complete: function () {
            }
        });
    }
    
    //Scroll-to
    if ($("body.scroll-to").length > 0 && $('#pointer').length > 0) {
        var $target = $('html, body');

        var scrollTimeout = setTimeout(function () {
            $target.scrollTop($('#pointer').offset().top);
        }, 1000);

        $target.on("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove", function () {
            $target.stop();
            clearTimeout(scrollTimeout);
        });

        $target.animate({ scrollTop: $('#pointer').offset().top }, 1000, function () {
            $target.off("scroll mousedown wheel DOMMouseScroll mousewheel keyup touchmove");
        });
    }

    //navbar
    var $fnsNavbar = $(".fns-navbar"),
        $navbarToggle = $fnsNavbar.find(".navbar-toggle"),
        $navbar = $fnsNavbar.find(".fns-navbar-collapse"),
        $fnsCarousel = $('#fns-carousel');

    $navbarToggle.on("click", function () {
        if ($fnsNavbar.hasClass('fns-navbar-collapsed')) {
            $fnsNavbar.removeClass('fns-navbar-collapsed');
            $(this).addClass('collapsed');
            $navbar.removeClass('in');
        }
        else {
            $fnsNavbar.addClass('fns-navbar-collapsed');
            $(this).removeClass('collapsed');
            $navbar.addClass('in');
        }
    });

    if (isTest) {
        $.each($('img:not(.captcha-image)'), function (index, img) {
            $(img).attr('src', $(img).attr('src') + '?v=11')
        });
    }

    //Scroll-top
    $(window).scroll(function () {
        if ($(this).scrollTop() > scrollTopOffset) {
            $('.back-to-top').fadeIn(scrollDuration);
        } else {
            $('.back-to-top').fadeOut(scrollDuration);
        }
    });

    $('.back-to-top').click(function (event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, scrollDuration);
        return false;
    })

    //Categories
    var $categoryContent = $("#game_category_with_more");
    if ($categoryContent.length > 0) {

        var $mainCategories = $categoryContent.find(".main-category-item");
        var $moreContainer = $('#more_category_container');
        var $dropCategories = $moreContainer.find(".dropdown-category-item");

        function refreshCategories() {
            var totalWidth = $categoryContent.width();
            var categoryWidth = 0;
            var moreContainerWidth = $moreContainer.outerWidth();

            var showInMore = false;

            $moreContainer.removeClass("open active");

            $mainCategories.each(function (index, item) {
                var $item = $(item);
                var isActiveItem = $item.hasClass("active");
                categoryWidth += $item.outerWidth();
                showInMore = showInMore || categoryWidth >= totalWidth;

                if (showInMore == false && (index < $mainCategories.length - 1) && (categoryWidth + moreContainerWidth) > totalWidth) {
                    showInMore = true;
                    categoryWidth += moreContainerWidth
                }

                $item.toggle(showInMore == false);
                $($dropCategories[index]).toggle(showInMore);

                $moreContainer.toggleClass("show-more", showInMore);

                if (showInMore && isActiveItem) {
                    $moreContainer.addClass("active");
                }
            });
        }

        refreshCategories();
        $categoryContent.css("visibility", "visible");
        $(window).resize(function () {
            clearTimeout(window.resizedFinished);
            window.resizedFinished = setTimeout(function () {
                refreshCategories();
            }, 1);
        });
    }




    function downloadAutoResizable() {
        if ($('body.scroll-to').length > 0) {
            var height = 0;
            var browser = detectBrowser();
            if ($(this).scrollTop() >= 160) {
                $("#slider").addClass('fixed');
                height = screen.height;
            }
            else {
                $("#slider").removeClass('fixed');
                height = Math.min(screen.height, screen.height - 142);
            }
            if (screen.height <= 921) {
                var minusHeight = 165;
                if (browser.length) {
                    switch (browser) {
                        case "Chrome": minusHeight = 175; break;
                        case "Firefox": minusHeight = 180; break;
                        case "IE": minusHeight = 145; break;
                        case "Edge": minusHeight = 145; break;
                    }
                }

                $("#slider .inner-box").height(height - minusHeight);
            }
        }
    }

    function settings() {
        var $slider = $('#slider');
        var $toggle = $('#toggle');
        var slideIn = $slider.data('slide-in'),
            slideOut = $slider.data('slide-out');
        if (browser.isIe() && browser.getVersion() <= 9) {
            slideIn = $slider.data('slide-in-ie9');
            slideOut = $slider.data('slide-out-ie9');
        }

        $toggle.on('click', function () {
            var isOpen = $slider.hasClass(slideIn);

            $slider.removeClass(isOpen ? slideIn : slideOut);
            $slider.addClass(isOpen ? slideOut : slideIn);
        });

        if (screen.width > 1920) {
            $slider.addClass(slideIn);
        }
    }

    function startAnimate() {
        $marquee.marquee("resume");
        $jackpotContainer.find(".star").addClass("paused");
        $(".jackpot-light-circle").animateSprite("restart");
        $('body').removeClass('stop-all-animate');
    }

    function stopAnimate() {
        $marquee.marquee("pause");
        $jackpotContainer.find(".star").addClass("paused");
        $(".jackpot-light-circle").animateSprite("stop");
        $('body').addClass('stop-all-animate');
    }


    window.startAnimate = startAnimate;
    window.stopAnimate = stopAnimate;    
});