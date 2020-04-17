/*
 * Javascript file that handles events on comments
 */

/**
 * callback-function to handle response of
 * the ajax-request to delete comment
 */
function commentDeleteCallback(response, element) {

    //check if request was successful
    if(response.substr(0, 4) == 'true') {

        //get element with the commentId
        var elem = $('#' + response.substr(5));

        //get the table Element and fade it out
        var table = $(elem).parent().parent().fadeOut('slow');
    }
}

/**
 * callback-function to handle response of
 * the ajax-request to publish a comment
 */
function commentPublishCallback(response, element) {
    console.log('response: ' + response);
    console.log(element);

    //toogle event-classes
    $(element).removeClass('event-comment-publish');
    $(element).addClass('event-comment-block');

    //alert text
    $(element).children('span').text('sperren');

    //alter icon
    $(element).children('i').removeClass('fa-check-square');
    $(element).children('i').addClass('fa-square-o');

    //remove Eventlistener
    $(element).unbind('click');

    //register events
    registerBlockCommentEvent(element);
}

/**
 * callback-function to handle response of
 * the ajax-request to block a comment
 */
function commentBlockCallback(response, element) {
    console.log('response: ' + response);
    console.log(element);

    //toogle event-classes
    $(element).removeClass('event-comment-block');
    $(element).addClass('event-comment-publish');

    //alert text
    $(element).children('span').text('veröffentlichen');

    //alter icon
    $(element).children('i').removeClass('fa-square-o');
    $(element).children('i').addClass('fa-check-square');

    //remove Eventlistener
    $(element).unbind('click');

    //register events
    registerPublishCommentEvent(element);

}

/**
 * registerBlockCommentEvent
 */
function registerBlockCommentEvent(elem) {

    /** block Comment **/
    $(elem).one('click', function() {

            //get commentId
            var commentId = $(this).parent().attr('id');

            //data to submit per ajax
            var objData = {
                admin: "true",
                object: "comments",
                action: "block",
                commentId: commentId
            }

            //call ajax-request to publish comment
            ajaxRequest(objData, commentBlockCallback, this);
    });
}

/**
 * registerPublishCommentEvent
 */
function registerPublishCommentEvent(elem) {

    /** publish Comment **/
    $(elem).one('click', function() {

            //get commentId
            var commentId = $(this).parent().attr('id');

            //data to submit per ajax
            var objData = {
                admin: "true",
                object: "comments",
                action: "publish",
                commentId: commentId
            }

            //call ajax-request to publish comment
            ajaxRequest(objData, commentPublishCallback, this);
    });
}

/**
 * registerDeleteCommentEvent
 */
function registerDeleteCommentEvent() {

    /** delete Comment */
    $('.event-comment-delete').one('click', function() {

        //ask user to confirm
        var confirmed = confirm("Möchtest Du diesen Kommentar wirklich löschen?");

        //check if user confirmed
        if(confirmed) {

            //get commentId
            var commentId = $(this).parent().attr('id');

            //data to submit per ajax
            var objData = {
                admin: "true",
                object: "comments",
                action: "delete",
                commentId: commentId
            }

            //call ajax-request to delete comment
            ajaxRequest(objData, commentDeleteCallback, this)

        }
    });
}


/**
 * register Mouse-Events
 */
$(document).ready(function() {

    registerBlockCommentEvent('.event-comment-block');
    registerPublishCommentEvent('.event-comment-publish');
    registerDeleteCommentEvent();

}); //end document.ready