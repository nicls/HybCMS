globFunc = new globFunctions();

var callbackAfterToTop = function() {

    var url = window.location;
    try {
        //trk event
        gaTrkEvent(
                'UI',
                "ToTop",
                url.href,
                0,
                true
                );
    } catch (e) {
    }
};

$(document).ready(function() {

    //add name element to h1
    $('#logo').first().after('<a name="top"></a>');

    //bind click event
    $('#toTop').click(function() {

        var $target = $('a[name="top"]');

        if ($target.length) {
            var targetOffset = $target.offset().top;
            $('html,body').animate({
                scrollTop: targetOffset
            }, 1000);

            //callback
            callbackAfterToTop();

            return false;
        }

    });


    //bind events to fade in and out a pagescroll
    $(window).bind("scroll", function(event) {

        var widthContent = $('.container').width();
        var widthDocument = $(document).width();
        var arrScrollPosition = globFunc.getPageScroll();
        var showAtPx = 600;

        //show toTop-Slider at the bottom of the page
        if (!(widthDocument - widthContent > 100)) {
            $('#toTop').css('bottom', '15%');
            $('#toTop').css('right', '5%');
            showAtPx = 500;
        } else {
            $('#toTop').css('right', ((widthDocument - widthContent) / 2) - 50 + 'px');
        }

        //set toTop-Slider position on document width 600px
        if (widthDocument > 600) {

            //did the user scrolled down?
            if (arrScrollPosition[1] > showAtPx) {


                //show the toTop-Slider
                if ($('#toTop').css('display') == 'none') {
                    $('#toTop').fadeIn('slow');
                }

            } else if (arrScrollPosition[0] <= showAtPx) {
                //hide the toTop-Slider
                if ($('#toTop').css('display') == 'block') {
                    $('#toTop').fadeOut('slow');
                }
            }
        }
    });
});


