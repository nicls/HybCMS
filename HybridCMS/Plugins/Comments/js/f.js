/**
* class Comment
*/
function Comments() 
{

    /**
     * Helper functions
     * @type globFunctions
     */
    var mObjGlobFunc = new globFunctions();
    
    /**
    * check form is ready to submit
    */
    var readyToSubmit = true;
    
    /**
     * Current Url with comments
     * @type String
     */
    var mUrl;
    
    /**
     * Current Hostname
     * @type Element|@call;$|@call;$
     */
    var mHost = "http://"+window.location.hostname;
    
    /**
     * Collection of Default images for the Gravatar-Image
     * @type Array
     */
    var mArrDefaultImgs = new Array();
    
    /**
     * UI-Elements
     * @type Element
     */
    var mElem_currCommentContainer;
    var mElemClass_ButtonRemoveComments;
    var mElemClass_ButtonListComments;
    
    
    var mButtonHtmlListComments;
    
    
    this.registerClickEventRemoveComments = function(elemClassButtons)
    {
        mElemClass_ButtonRemoveComments = elemClassButtons;
        //$('.' + elemClassButtons).on('click', function(){
        $(document).on( "click", '.' + elemClassButtons, function() {        
            
            //get the comments url
            url = $(this).attr('name');
                                   
            //get fragment
            var fragment = url.split('#')[1];                        

            //get current comments-container
            mElem_currCommentContainer = 
                    $('#' + fragment + '-commentsContainer');
            
            $(mElem_currCommentContainer).children(
                    '.hyb_comment_commentContainer').remove();
            
            $(this).toggleClass(mElemClass_ButtonListComments);
            $(this).toggleClass(mElemClass_ButtonRemoveComments);  
            $(this).html(mButtonHtmlListComments);  
        });
    }
    
    /**
     * Registeres click envent on all buttons to load the comments from db
     * @param {element}[] arrElemButtons
     * @returns {null}
     */
    this.registerClickEventListComments = function(elemClassButtons)
    {
        mElemClass_ButtonListComments = elemClassButtons;
        $(document).on( "click", '.' + elemClassButtons, function() {
        //$('.' + elemClassButtons).on('click', function(){
            
            //get the comments url
            url = $(this).attr('name');
            
            //validate url
            readyToSubmit = setUrl(url);
            
            //get fragment
            var fragment = url.split('#')[1];            
            if(fragment) readyToSubmit ? true : false;
            
            //get current comments-container
            mElem_currCommentContainer = 
                    $('#' + fragment + '-commentsContainer');
            
            if(mElem_currCommentContainer) readyToSubmit ? true : false;
            
            //submit ajax request to get all comments for the given url
            requestComments();
            
            $(this).toggleClass(mElemClass_ButtonListComments);
            $(this).toggleClass(mElemClass_ButtonRemoveComments);
            mButtonHtmlListComments = $(this).html();
            $(this).html('<i class="fa fa-comments"></i> | Alle Kommetare ausblenden.');        
        });
    }

    /**
     * submitComment
     */
    function requestComments() 
    {
        //check if form is ready to submit
        if(readyToSubmit == true) {
            
            var objData = {
                plugin: 'comments',
                action: 'requestComments',
                url: mUrl
                }
                
            mObjGlobFunc.jsonRequest(
                    objData, 
                    showComments, 
                    mElem_currCommentContainer);
            
        }
    }
    
    /**
     * 
     * @param {type} arrObjComments
     * @param {type} elemUserResponseAjax
     * @returns {undefined}
     */
    function showComments(arrObjComments, elemUserResponseAjax)
    {
        
        console.log(arrObjComments[0]);
        
        for(var i=0; i<arrObjComments.length; i++)
        {
            var htmlComment = buildHtmlComment(arrObjComments[i]);
            
            $(elemUserResponseAjax).append(htmlComment);
        }
    }
    
    /**
     * Wrapps a comemnt in html
     * @param {json} objComment
     * @returns {String}
     */
    function buildHtmlComment(objComment)
    {
         
        //add date
        var objDateData = mObjGlobFunc.getTimeObjects(objComment.timeCreated);
        var date = objDateData.date;
        var monthAsString = objDateData.monthAsString;
        var year = objDateData.fullYear;
        var minutes = objDateData.minutes;
        var hours = objDateData.hours;
        
        var op = '';
        
        op += '<article class="hyb_comment_commentContainer" '
                + 'itemscope itemtype="http://schema.org/Comment">';
        
        //add comment header
        op += '<header class="hyb_comment_commentHeader">';
                   
                
        //add username
        op += '<p><i class="fa fa-comment"></i> '
                + '<span class="hyb_comment_username">'
                + objComment.username + '</span> ' 
                + 'am <span class="hyb_comment_timeCreated">'+ date + '. ' 
                + monthAsString + ' ' + year + ' (' + hours + ':' + minutes 
                + ' Uhr)</span>' + '</p>';
                
        op += '</header>';
        
        var defaultImg = '';
        if(mArrDefaultImgs.length > 0) 
        {
            //get a random image
            var item = mArrDefaultImgs[Math.floor(
                        Math.random()*mArrDefaultImgs.length)];
            
            //build iamge path
            defaultImg = "&d=" + mHost + item;
        }
        
        //add avatar
        op += '<img class="avatar" height="80" width="80" src="'
                + objComment.avatarUrl 
                + defaultImg
                +'" />';
        op += '<p class="hyb_comment_comment">' + objComment.comment + '</p>';
        
        op += '</article>';
        
        return op;
    }
    
    /**
     * setUrl - public setter for url
     */
    function setUrl(url) 
    {
        var regex = new RegExp("^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\.\,_\?\'/\\\+&%\$#\=~;])*$");
        
        if(regex.test(url)) 
        {
            mUrl = url;
            return true;
        }
        else
        {
            return false;
        }
    }   
    
    /**
     * Add a default img for Gravatar
     * @param {String} pathToImg
     * @returns {void}
     */
    this.addGravatarImg = function(pathToImg)
    {
        if(true === mObjGlobFunc.filePathIsAbsolute(pathToImg))
        {
            console.log('Img-Path has to be relative.');
        }
        else if(false === mObjGlobFunc.fileNameIsImage(pathToImg)) 
        {
            console.log('Img-Path does not point to an img.');
        }
        else
        {
            mArrDefaultImgs.push(pathToImg);
        }
    }
}

/**
 * Init Plugin
 * #########################################
 */
$(document).ready(function(){
    //create new Comemnts instance
    var objComments = new Comments();
    
    objComments.addGravatarImg("/images/gravatarImgs/1-80x80.jpg");
    objComments.addGravatarImg("/images/gravatarImgs/2-80x80.jpg");
    objComments.addGravatarImg("/images/gravatarImgs/3-80x80.jpg");
    objComments.addGravatarImg("/images/gravatarImgs/4-80x80.jpg");
    objComments.addGravatarImg("/images/gravatarImgs/5-80x80.jpg");
    
    objComments.registerClickEventListComments('hyb_comments_buttonShowAll');
    objComments.registerClickEventRemoveComments('hyb_comments_buttonRemoveAll');
});