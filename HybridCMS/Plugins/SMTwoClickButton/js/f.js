/**
 * callbackSmTwoClickButtonClicked
 * @param String smName
 * @return void
 */
var callbackSmTwoClickButtonClicked = function(action, smName) {

    var objGlonFunc = new globFunctions();

    if (action === 'open' && objGlonFunc) 
    {
        var url = window.location;
        try 
        {
            //trk event
            objGlonFunc.gaTrkEvent(
                    'UI',
                    "SM 2-Click Button: " + smName,
                    url.href,
                    0,
                    true
                    );
            
        } 
        catch (e) {}
    }
}

$(document).ready(function() {

    var currentURL = window.location;
    var headline = $('h1').first().text();

    var fb_code = '<iframe src="https://www.facebook.com/plugins/like.php?locale=' + 'de_DE' + '&amp;href=' + currentURL + '&amp;send=false&amp;layout=button_count&amp;width=120&amp;show_faces=false&amp;action=' + 'recommend' + '&amp;colorscheme=light&amp;font&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:145px; height:21px;" allowTransparency="true"></iframe>';
    var twitter_code = '<iframe allowtransparency="true" frameborder="0" scrolling="no" src="https://platform.twitter.com/widgets/tweet_button.html?url=' + currentURL + '&amp;counturl=' + currentURL + '&amp;text=' + headline + '&amp;count=horizontal&amp;lang=' + 'en' + '" style="width:130px; height:25px;"></iframe>';
    var gplus_code = '<div class="g-plusone" data-size="medium" data-href="' + currentURL + '"></div><script type="text/javascript">window.___gcfg = {lang: "' + 'de' + '"}; (function() { var po = document.createElement("script"); po.type = "text/javascript"; po.async = true; po.src = "https://apis.google.com/js/plusone.js"; var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s); })(); </script>';

    $("[class^='tcb-']").click(function() {

        var code = '';
        var smName = '';
        var actionOpenButton = false;
        var actionCloseButton = false;


        if ($(this).attr('class') == 'tcb-twitter') {
            code = twitter_code;
            smName = 'Twitter';
        } else if ($(this).attr('class') == 'tcb-facebook') {
            code = fb_code;
            smName = 'Facebook';
        } else if ($(this).attr('class') == 'tcb-googleplus') {
            code = gplus_code;
            smName = 'Google Plus';
        }

        if ($(this).children().hasClass("tcb-info")) {

            actionCloseButton = true;

            //reset current button
            resetButton();

        } else {

            //reset all buttons
            resetButton();

            //prepare classes for the button
            var cssClass = $(this).attr('class');
            var newClass = cssClass.replace('tcb-', '');

            //add Button
            $(this).append('<span class="tcb-info ' + newClass + '">' + code + '</span>');
            $(this).css('width', '170px');
            $(this).css('padding-left', '14px');
            $(this).css('text-align', 'left');

            actionOpenButton = true;
        }

        action = 'open';
        if (actionCloseButton) {
            action = 'close';
        }

        if (action && smName) {
            callbackSmTwoClickButtonClicked(action, smName);
        }

    });

});

function resetButton() {
    $('.tcb-info').remove();
    $('.tcb-twitter').css('width', '60px');
    $('.tcb-twitter').css('padding-left', '6px');
    $('.tcb-twitter').css('text-align', 'center');

    $('.tcb-facebook').css('width', '60px');
    $('.tcb-facebook').css('padding-left', '6px');
    $('.tcb-facebook').css('text-align', 'center');

    $('.tcb-googleplus').css('width', '60px');
    $('.tcb-googleplus').css('padding-left', '6px');
    $('.tcb-googleplus').css('text-align', 'center');
}