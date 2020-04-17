/*
 * Javascript file that handles events on articles
 */

/**
 * callback-function to handle response of
 * the ajax-request to add an article
 * 
 * @param String response
 * @param Element element
 * @returns void
 */
function articleInsertCallback(response, element) {

    //hide previous mnessages
    $(element).hide();

    //trim response
    response = $.trim(response);

    //check if request was successful
    if (response.substring(0, 4) === 'true') {

        $(element).text('Article wurde gespeichert :o)');

    } else {
        console.log('Article konnte nicht gespeichert werden :o(');
        console.log(response);
        
        $(element).text('Article konnte nicht gespeichert werden :o(');
    }

    //shoe mwssage
    $(element).fadeIn('fast');
}

/**
 * callback-function to handle response of
 * the ajax-request to update an article
 * 
 * @param String response
 * @param Element element
 * @returns void
 */
function articleUpdateCallback(response, element) {

    //hide previous mnessages
    $(element).hide();

    //trim response
    response = $.trim(response);
    
    //check if request was successful
    if (response.substring(0, 4) === 'true') {

        $(element).text('Article wurde geändert :o)');

    } else {

        $(element).text('Article konnte nicht geändert werden :o(');
    }

    //shoe mwssage
    $(element).fadeIn('fast');
}

/**
 * register Mouse-Events
 */
$(document).ready(function() {

    /**
     * register click event on insert
     */
    $("[name='insert']").click(function(e) {

        //precent formsubission
        e.preventDefault();

        //clear errormessages
        $('.errorMsg').text('');
        
        var readyToSubmit = validateUserInput();

        //get cssId
        var cssId = fetchCssId();

        //get Article-Url
        var articleUrl = fetchArticleUrl();

        //get element for user messages
        var elemUserResponse = $('.userResponse');
        
        //data to submit per ajax
        var objData = {
            admin: "true",
            object: "article",
            action: "insert",
            cssId: cssId,
            articleUrl: articleUrl
        };

        //call ajax-request to insert article
        if (readyToSubmit)
            ajaxRequest(objData, articleInsertCallback, elemUserResponse);

    });

    /*
     * register click event on update
     */
    $("[name='update']").click(function(e) {
        
        //precent formsubission
        e.preventDefault();

        //clear errormessages
        $('.errorMsg').text('');
        
        var readyToSubmit = validateUserInput();

        //get cssId
        var cssId = fetchCssId();

        //get Article-Url
        var articleUrl = fetchArticleUrl();

        //get element for user messages
        var elemUserResponse = $('.userResponse');
        
        //data to submit per ajax
        var objData = {
            admin: "true",
            object: "article",
            action: "update",
            cssId: cssId,
            articleUrl: articleUrl
        };

        //call ajax-request to insert article
        if (readyToSubmit)
            ajaxRequest(objData, articleUpdateCallback, elemUserResponse);
        
    });

}); //end document.ready

/**
 * fetchArticleUrl - get the url of an Article that was typed in by the user
 * 
 * @returns String
 */
function fetchArticleUrl() {
    return $("[name='articleUrl']").val();
}

/**
 * fetchCssId - get the cssId of the Article typed in by the user
 * @returns String
 */
function fetchCssId() {
    return $("[name='cssId']").val();
}

/**
 * validateUserInput - check if url and cssId is valid
 * @returns {Boolean}
 */
function validateUserInput() {
    
        //validate if submission is ready
        var readyToSubmit = true;
        
        //get cssId
        var cssId = fetchCssId();

        //validate cssId
        if (!validateCSSId(cssId)) {
            readyToSubmit = false;
            $("[name='cssId']").next().text('CSS-Id ist nicht gültig.');
        }

        //get Article-Url
        var articleUrl = fetchArticleUrl();

        //validate articleUrl
        if (!validateUrl(articleUrl)) {
            readyToSubmit = false;
            $("[name='articleUrl']").next().text('articleUrl ist nicht gültig.');
        }
        
        return readyToSubmit;
}

