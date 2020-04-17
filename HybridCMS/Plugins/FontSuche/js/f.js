$(document).ready(function() {

    $('#fontSucheInput').keyup(function() {
        var base = 'http://www.tkqlhce.com/click-4144791-10979324?sid=Schrift+Suche&url=http://www.linotype.com/de/search/';
        var link = $('#fontSucheInput').val();
        $('#fontSuchen').removeAttr('href');
        $('#fontSuchen').attr('href', base + link);
    });

    $('#fontSuchen').click(function() {
        if (!$('#fontSuchen[href]')) {
            return false;
        } else if($('#fontSuchen').attr('href') === '') {
            return false;
        }
        //trk search
        trk_fontSuche();
    });

    /* Analytics Ereignis */
    var trk_fontSuche = function() {

        var title = "Font Suche: " + window.location;
        var target = $('#fontSucheInput').val();

        //register click events
        if (title && target && _gaq) {

            _gaq.push(
                    [
                        '_trackEvent',
                        'Linotype',
                        title,
                        target,
                        0,
                        true
                    ]);
        }
    }

});