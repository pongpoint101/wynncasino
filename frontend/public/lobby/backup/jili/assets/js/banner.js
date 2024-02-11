$(function () {

    jssor_1_slider_init = function (jssor_1, jssor_1_options) {
        var jssor_1_slider = new $JssorSlider$(jssor_1, jssor_1_options);
    };

    jssor_slider_ads_starter = function (containerId) {
        var _SlideshowTransitions = [
            { $Duration: 1200, x: -0.3, $During: { $Left: [0.3, 0.7] }, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 }
            , { $Duration: 1200, x: 0.3, $SlideOut: true, $Easing: { $Left: $JssorEasing$.$EaseInCubic, $Opacity: $JssorEasing$.$EaseLinear }, $Opacity: 2 }
        ];

        var options = {
            $AutoPlay: true,
            $Idle: 4000,
            $PauseOnHover: 1,

            $SlideDuration: 500,
            $MinDragOffsetToSlide: 20,
            $SlideSpacing: 0,
            $Cols: 1,
            $ParkingPosition: 0,
            $UISearchMode: 1,
            $PlayOrientation: 1,

            $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: _SlideshowTransitions,
                $TransitionsOrder: 1
            }
        };

        var jssor_slider1 = new $JssorSlider$(containerId, options);

        function ScaleSlider() {
            var refSize = jssor_slider1.$Elmt.parentNode.clientWidth;
            if (refSize) {
                refSize = Math.min(refSize, 1021);
                jssor_slider1.$ScaleWidth(refSize);
            }
            else {
                window.setTimeout(ScaleSlider, 30);
            }
        }
        ScaleSlider();
        $Jssor$.$AddEvent(window, "load", ScaleSlider);
        $Jssor$.$AddEvent(window, "resize", ScaleSlider);
        $Jssor$.$AddEvent(window, "orientationchange", ScaleSlider);
    };

    //Home Banner
    if ($("#jssor_1").length > 0) {
        var jssor_1_SlideshowTransitions = [
            { $Duration: 1200, y: 0.3, $During: { $Top: [0.3, 0.7] }, $Easing: { $Top: $Jease$.$InCubic, $Opacity: $Jease$.$Linear }, $Opacity: 2, $Outside: true }
        ];
        //Add atrtibute "data-t" for each element "data-u='caption'";
        if ($('*[data-u="caption"]').length) {
            $('*[data-u="caption"]').each(function (i, e) {
                $(this).attr("data-t", i);
            });
        }
        var jssor_1_bannerEvent = [];
        var eventTheme = $("#jssor_1").data("event-theme");



        if (eventTheme && eventTheme == "new-year") {
            /*** Lunar New Year Bonus - 13/01/2020***/
            jssor_1_bannerEvent.push(
                [{ b: -1, d: 1, o: -1 }, { b: 840, d: 500, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 300, d: 500, y: 67, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 300, d: 500, y: 80, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1300, d: 500, x: 115, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1500, d: 500, x: -200, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 2000, d: 400, y: -115, o: 1 }]
            );
        }
        else if (eventTheme && eventTheme == "western-new-year") {
            jssor_1_bannerEvent.push(
                [{ b: -1, d: 1, o: -1 }, { b: 600, d: 600, y: 80, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 960, d: 600, y: 80, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 2600, d: 500, x: -126, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1600, d: 600, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 2100, d: 600, y: -115, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 2800, d: 500, x: -145, o: 1 }]
            );
        }
        else if (eventTheme && eventTheme == "christmas") {
            /*** ChirstMas 2019 - 16/12/2019 ***/
            jssor_1_bannerEvent.push(
                [{ b: -1, d: 1, o: -1 }, { b: 1700, d: 400, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1660, d: 500, y: -95, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1200, d: 500, x: 105, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 0, d: 1100, y: -145, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1880, d: 440, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1700, d: 400, o: 1, rY: 360, e: { rY: 1 } }],
                [{ b: -1, d: 1, o: -1 }, { b: 1800, d: 400, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1300, d: 500, x: -115, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 900, d: 400, y: 120, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1700, d: 500, y: -100, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 1980, d: 460, o: 1 }]
            );
        }
        else if (eventTheme && eventTheme == "mid-autumn") {
            /*** Mid Autumn 19/9/2018 ****/
            jssor_1_bannerEvent.push(
                [{ b: -1, d: 1, o: -1 }, { b: 900, d: 1100, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 700, d: 600, x: 58, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 0, d: 660, x: 1226, y: 4 }, { b: 660, d: 780, x: -170, o: 1, e: { x: 2 } }],
                [{ b: -1, d: 1, o: -1 }, { b: 0, d: 500, y: 81, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 500, d: 400, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 400, d: 400, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 0, d: 720, y: -114, o: 1 }],
                [{ b: -1, d: 1, o: -1 }, { b: 0, d: 600, y: -91, o: 1 }]
            );
        }

        /*** Banner Wild Protectors 29/5/2020 ***/
        jssor_1_bannerEvent.push(
            [{ b: -1, d: 1, o: -1 },{ b: 800, d: 700, x: -99, o: 1 }],
            [{ b: -1, d: 1, o: -1 },{ b: 0, d: 800, o: 1 }],
            [{ b: -1, d: 1, o: -1 },{ b: 1000, d: 500, o: 1 }],
            [{ b: -1, d: 1, o: -1 },{ b: 1520, d: 360, y: -80, o: 1 }]
        );

        /*** Banner Wild Fairies 29/5/2020 ***/
        jssor_1_bannerEvent.push(
            [{ b: -1, d: 1, o: -1 }, { b: 0, d: 900, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 900, d: 500, x: -195, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 900, d: 500, x: 205, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 1400, d: 400, y: -100, o: 1 }]
        );

        /*** Banner Big Gaming 26/2/2020 ***/
        jssor_1_bannerEvent.push(
            [{ b: -1, d: 1, o: -1 }, { b: 300, d: 500, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 820, d: 500, y: 82, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 820, d: 500, y: -85, o: 1 }]
        );

        jssor_1_SlideoTransitions = jssor_1_bannerEvent;

        var jssor_1_options = {
            $AutoPlay: true,
            $SlideDuration: 800,
            $DragOrientation: 0,
            $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions
            },
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };

        jssor_1_slider_init("jssor_1", jssor_1_options);
    }

    //Game Banner
    if ($("#jssor_game_banner").length > 0) {
        var jssor_1_SlideoTransitions = [
            [{ b: -1, d: 1, c: { t: -281 } }, { b: 1000, d: 1000, y: 1, c: { t: 281 } }],
            [{ b: -1, d: 1, o: -1 }, { b: 1800, d: 1200, o: 1 }],
            [{ b: -1, d: 1, o: -1 }, { b: 1800, d: 1200, o: 1 }]
        ];

        var jssor_1_options = {
            $AutoPlay: true,
            $SlideDuration: 800,
            $SlideEasing: $Jease$.$OutQuint,
            $CaptionSliderOptions: {
                $Class: $JssorCaptionSlideo$,
                $Transitions: jssor_1_SlideoTransitions
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };

        jssor_1_slider_init("jssor_game_banner", jssor_1_options);
    }

    //ads banner
    if ($("#jssor_ads").length > 0) {
        jssor_slider_ads_starter('jssor_ads');
        $('#jssor_ads .item').show();
    }

    //Live Casino
    if ($("#jssor_live_casino").length > 0) {
        var jssor_1_SlideshowTransitions = [
            { $Duration: 1200, $Opacity: 2 }
        ];

        var jssor_1_options = {
            $AutoPlay: true,
            $DragOrientation: 0,
            $SlideshowOptions: {
                $Class: $JssorSlideshowRunner$,
                $Transitions: jssor_1_SlideshowTransitions,
                $TransitionsOrder: 1
            },
            $ArrowNavigatorOptions: {
                $Class: $JssorArrowNavigator$
            },
            $BulletNavigatorOptions: {
                $Class: $JssorBulletNavigator$
            }
        };

        var jssor_1_slider = new $JssorSlider$("jssor_live_casino", jssor_1_options);

        $.each($("#jssor_live_casino").find('.btn-banner-play'), function (index, element) {
            var $target = $(element);
            $target.mouseover(function () {
                $target.find('.first').hide();
                $target.find('.second').show();
            }).mouseout(function () {
                $target.find('.first').show();
                $target.find('.second').hide();
            })
        });
    }

})