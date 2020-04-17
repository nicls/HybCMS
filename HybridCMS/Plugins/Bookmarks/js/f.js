//initially load bookmarks from local storeage
var objBookmarks = new Bookmarks();
objBookmarks.loadBookmarks();

//check if current page is bookmarkAble
if (!$('.bookmarkAble h1').text()) {
    $('.addBookmark').hide();

    //alter bookmarkLabel
    $('#bookmarkLabel').text('Gemerkte Artikel: ');

} else {
    $('.addBookmark').first().show();
}

//check if current article is bookmarked
if (objBookmarks.isCurrentArticleBookmarked()) {
    //alter add button to delete button
    var element = $('.addBookmark');
    objBookmarks.toggleButton(element);
}


/**
 * callbackDeleteBookmark
 * @param String headline
 * @returns {undefined}
 */
var callbackDeleteBookmark = function(headline) {
    console.log('bookmark deleted: ' + headline);

    //check if bookmark was the current Article
    if ($('.bookmarkAble h1').text() == headline) {
        callbackDeleteCurrentBookmark(headline);
    }
};

/**
 * callbackDeleteCurrentBookmark
 * @param String headline 
 * @returns void
 */
var callbackDeleteCurrentBookmark = function(headline) {
    console.log('current bookmark deleted: ' + headline);

    //alter delete button to add button
    var element = $('.deleteCurrentBookmark');
    objBookmarks.toggleButton(element);
};

/**
 * callbackAddBookmark
 * @param String headline  
 * @returns void
 */
var callbackAddBookmark = function(headline) {
    console.log('bookmark added: ' + headline);

    //alter add button to delete button
    var element = $('.addBookmark');
    objBookmarks.toggleButton(element);

    //trk event
    try{
    gaTrkEvent(
            'UI',
            "Bookmarked",
            headline,
            0,
            true
            );
    } catch(e) {}
};

/**
 * callbackListBookmarks
 * @returns void
 */
var callbackListBookmarks = function() {
    $('#bookmarks').fadeIn('fast');

    //get width of the layout
    var layoutWidth = $('footer').width();

    //set width of the list
    $('#bookmarks').css('width', layoutWidth * 0.8);

    //set horizontal position of the list
    var documentWidth = $(document).width();
    var bookmarksWidth = $('#bookmarks').width();
    var posLeft = (documentWidth - bookmarksWidth) / 2;
    $('#bookmarks').css('left', posLeft);

    //set the vertical position of the list
    var documentHeight = $(window).height();
    var bookmarkHeight = $('#bookmarks').height();
    var posTop = ((documentHeight - bookmarkHeight) / 3);
    $('#bookmarks').css('top', posTop);
};

/**
 * callbackCloseBookmarks
 * @returns void
 */
var callbackCloseBookmarks = function() {
    $('#overlay').remove();
    $('#bookmarks').remove();
};

//register list events for all bookmarks now and in the future
objBookmarks.registerEventListBookmarks(callbackListBookmarks);

//register close events for the bookmarklist now and in the future
objBookmarks.registerEventCloseBookmarks(callbackCloseBookmarks);

//register delete events for all bookmarks now and in the future
objBookmarks.registerEventDeleteBookmark(callbackDeleteBookmark);

//register event to delete current bookmark
objBookmarks.registerEventDeleteCurrentBookmark(callbackDeleteCurrentBookmark);

//register evet to add bookmark
objBookmarks.registerEventAddBookmark(callbackAddBookmark);

/**
 * class Bookmarks
 */
function Bookmarks() {

    /**
     * headlines - headlnes from local storeage in the form
     * headline1|headline2|...
     * @type String
     */
    var mHeadlines = '';

    /**
     * width of the thumbnail
     * @type Integer
     */
    var mWidthThumb = 60;

    /**
     * height of the thumbnail
     * @type Integer
     */
    var mHeightThumb = 60;

    /**
     * indicates whether json-request is ready to submit or not
     * @type Boolean
     */
    var isReadyToSubmit = true;

    /**
     * loadBookmarks from the local storeage
     * 
     * @returns void
     */
    this.loadBookmarks = function() {

        //load bookmarks from local storeage
        mHeadlines = localStorage.getItem("bookmarks");

        //refresh number of bookmarks
        refreshNumberOfBookmarks();


    };

    /**
     * isCurrentArticleBookmarked
     * @returns {Boolean}
     */
    this.isCurrentArticleBookmarked = function() {

        if (mHeadlines) {

            //get current headline
            var headline = $('.bookmarkAble h1').text();

            if (mHeadlines.search(headline) != -1) {

                return true;
            }

            return false;
        }
    };

    /**
     * toggleButton
     * @param Element element
     * @returns void
     */
    this.toggleButton = function(element) {
        $(element).toggleClass('addBookmark');
        $(element).toggleClass('deleteCurrentBookmark');
        $(element).children('i').toggleClass('fa-plus');
        $(element).children('i').toggleClass('fa-times');
    };

    /**
     * refreshNumberOfBookmarks
     * @returns void
     */
    function refreshNumberOfBookmarks() {

        number = 0;

        //check if there are any headlines
        if (mHeadlines) {
            //exlode headlines
            arrHeadlines = mHeadlines.split('|');
            number = arrHeadlines.length;
        }

        //fadeout
        $('.listBookmarks span').fadeOut('fast');

        //return number of headlines
        $('.listBookmarks span').text('(' + number + ')');

        //fade in
        $('.listBookmarks span').fadeIn('fast');
    }

    /**
     * addBookmark - public function to add a bookmark to the local storeage
     * and the list of stored bookmarks
     * 
     * @param String headline
     * @returns void
     */
    this.addBookmark = function(headline) {

        //check if headline is valid
        if (headline.length < 160) {

            if (!mHeadlines) {

                //assign headline 
                mHeadlines = headline;

                //store headlines in local storeage
                localStorage.setItem("bookmarks", mHeadlines);

                //check if headline is not still saved
            } else if (mHeadlines.search(headline) === -1) {

                //add headline to mHeadlines
                mHeadlines += '|' + headline;

                //store headlines in local storeage
                localStorage.setItem("bookmarks", mHeadlines);

            } else {

                //give error message to the client
                //article is already stored
                console.log('article is already stored')
            }

            //refresh counter
            refreshNumberOfBookmarks();
        } else {

            //give error message to the client
            //length of headline is not valid
            console.log('length of headline is not valid');
        }

    } //end addBookmark-function


    /**
     * listBookmarks - public function to list all saved bookmarks
     * 
     * @returns void
     */
    this.listBookmarks = function() {

        //check if there are any boookmarks 
        if (mHeadlines.length > 0) {

            //parse mHeadlines
            var arrHeadlines = mHeadlines.split('|');

            //empty mArrObjBookmarks
            mArrObjBookmarks = [];

            //append overlay to body
            $('body').append('<div id="overlay" class="closeBookmarks"></div>');

            //append list to body
            $('body').append('<div id="bookmarks"></div>');

            for (var i = 0; i < arrHeadlines.length; i++) {
                //select Bookmarks from database and assign each 
                //bookmark to mArrObjBookmarks
                requestBookmark(arrHeadlines[i]);
            }

            //add close-icon to the list 
            $('#bookmarks').append('<i class="closeBookmarks icon-remove-circle"></i>');

            //add close-icon to the list 
            $('#bookmarks').append('<h1>Meine gemerkten Artikel</h1>');

        } else {

            //show message to the user that there are not bookmarks
            //append overlay to body
            $('body').append('<div id="overlay" class="closeBookmarks"></div>');

            //append list to body
            $('body').append('<div id="bookmarks"><p><i class="icon-info-sign"></i> Keine gemerkten Artikel vorhanden.</p></div>');

            //add close-icon to the list 
            $('#bookmarks').append('<i class="closeBookmarks icon-remove-circle"></i>');
        }

    };

    /**
     * requestBookmark - private function to request a bookmark per CORS from server
     * @returns JSON-Object
     */
    function requestBookmark(bookmark) {

        if (isReadyToSubmit) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "/HybridCMS/Ajax/api/ajax.php",
                data: {
                    plugin: 'bookmarks',
                    headline: bookmark,
                    heightThumb: mHeightThumb,
                    widthThumb: mWidthThumb
                },
                success: printBookmark
            });
        }
    }

    /**
     * printBookmark - handle successfull JSON-Request
     * and add the Bookmark to the list of Bookmarks
     * 
     * @param JSON-Object res
     * @returns void
     */
    function printBookmark(res) {

        //check if Article exists
        if (res.firstHeadline) {
            //build html
            var output = "<article class='bookmark'>";

            //check if artice has a primaryImage
            if (res.thumbOfPrimaryImage) {
                output += '<a href="' + res.url + '" title="' + res.firstHeadline + '">';
                output += '<img src="' + res.thumbOfPrimaryImage + '" height="' + mHeightThumb + '" width="' + mWidthThumb + '"/>';
                output += '</a>';
            }

            //add headline
            output += '<h2>';
            output += '<a href="' + res.url + '" title="' + res.firstHeadline + '">' + res.firstHeadline + '</a>';
            output += '</h2>';

            //add delete icon
            output += '<div class="icon-remove deleteBookmark"> Artikel löschen</div>';

            //close Article
            output += '</article>';

            //include bookmark into DOM
            $('#bookmarks').append(output);

        }

    }

    /**
     * deleteBookmark - private function that deletes an Bookmark from the
     * local storeage and from the list of saved bookmarks
     * 
     * @param String headline
     * @returns void
     */
    function deleteBookmark(headline) {

        var arrHeadlines = mHeadlines.split('|');

        //delete old headlines
        mHeadlines = '';

        //remove bookmark from mHeadlines and the local storeage
        for (var i = 0; i < arrHeadlines.length; i++) {

            //check if headline is not the headline that should be deleted
            if (arrHeadlines[i] !== headline) {

                //check if headline is empty
                if (mHeadlines.length == 0) {
                    //add first headline
                    mHeadlines += arrHeadlines[i];

                    console.log('readed: ' + arrHeadlines[i])
                } else {
                    //add next headline
                    mHeadlines += '|' + arrHeadlines[i];
                }
            }
        }

        //overwrite localstoreage
        localStorage.setItem("bookmarks", mHeadlines);

        //update number of Bookmarks
        refreshNumberOfBookmarks();

    }

    /**
     * registerEventListBookmarks
     * @param function callbackListBookmarks
     * @returns void
     */
    this.registerEventListBookmarks = function(callbackListBookmarks) {
        //register event to show bookmarks
        $('.listBookmarks').click(function() {

            //list bookmarks
            objBookmarks.listBookmarks();

            //call callbackfunctiona
            callbackListBookmarks();

        });
    };

    /**
     * registerEventCloseBookmarks
     * @param function callbackCloseBookmarks
     * @returns void
     */
    this.registerEventCloseBookmarks = function(callbackCloseBookmarks) {

        //resgister event to close the list of bookmarks
        $('body').delegate(".closeBookmarks", "click", function() {

            //call callbackfunction
            callbackCloseBookmarks();
        });
    };

    /**
     * registerEventDeleteBookmark
     * 
     * @returns void
     */
    this.registerEventDeleteBookmark = function(callbackDeleteBookmark) {
        $("body").delegate("#bookmarks .bookmark .deleteBookmark", "click", function() {

            //remove Bookmark from the local storeage
            var headline = $(this).parent().children('h2').text();
            deleteBookmark(headline);

            //remove bookmark from list in DOM
            $(this).parent().fadeOut('fast');
            $(this).parent().remove();

            //check if there are still any bookmarks
            var arrBookmarks = $('#bookmarks').children('.bookmark');
            if (arrBookmarks.length == 0) {
                $('#bookmarks').append('<p class="message">Keine weiteren Einträge.</p>');
            }

            //call callback function
            callbackDeleteBookmark(headline);
        });
    };

    /**
     * registerEventDeleteCurrentBookmark
     * 
     * @returns void
     */
    this.registerEventDeleteCurrentBookmark = function(callbackDeleteCurrentBookmark) {
        $("body").delegate(".deleteCurrentBookmark", "click", function() {

            //remove Bookmark from the local storeage
            var headline = $('.bookmarkAble h1').text();
            deleteBookmark(headline);

            //call callback function
            callbackDeleteCurrentBookmark(headline);

        });
    };

    /**
     * registerEventAddBookmark
     * @returns void
     */
    this.registerEventAddBookmark = function(callbackAddBookmark) {
        $("body").delegate(".addBookmark", "click", function() {

            var headline = $('.bookmarkAble h1').text();
            objBookmarks.addBookmark(headline);

            //call callback function
            callbackAddBookmark(headline);
        });
    };

    /**
     * setWidthThumb - public setter
     * 
     * @param Integer widthThumb
     * @returns void
     */
    this.setWidthThumb = function(widthThumb) {

        //check if widthThumb is valid
        if (widthThumb > 1 && widthThumb < 3000) {
            mWidthThumb = widthThumb;
        }
    };

    /**
     * setHeightThumb - public setter
     * 
     * @param Integer heightThumb
     * @returns void
     */
    this.setHeightThumb = function(heightThumb) {

        //check if heightThumb is valid
        if (heightThumb > 1 && heightThumb < 3000) {
            mHeightThumb = heightThumb;
        }
    };

}