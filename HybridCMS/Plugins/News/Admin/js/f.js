var News = function() 
{
    
    /**
     * globaly needed functions
     * @type globFunctions
     */
    var mObjFunc;

    /**
     * Current News 
     * @type Object
     */
    var mObjNews;

    /**
     * UI-Elements
     */

    //Input Elements
    var mElem_Input_NewsTitle;
    var mElem_Input_NewsUrl;
    var mElem_Textarea_NewsText;
    var mElem_Input_NewsDate;
    
    //Buttons
    var mElem_Button_InsertNewsTeaser;
    var mElemClass_Button_DeleteNewsTeaser;

    //Ajax UI-Elements
    var mElem_AjaxError;
    var mElem_AjaxResponse;
    var mElem_TableOfNewsTeaser;


    /**
     * readyToSubmit indicates if an Ajax-Request should be done or not
     * @type Boolean
     */
    var mReadyToSubmit = true;

    /**
     * public function to init Comptable
     * @returns void
     */
    this.init = function init() {

        //initiate functions-Object
        mObjFunc = new globFunctions();
    };
    
    /**
     * public function registerClickEventInsertNewsTeaser
     * @returns void
     */
    this.registerClickEventInsertNewsTeaser = function() {

        mElem_Button_InsertNewsTeaser.click(function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');
            $(document).find('.errorMsg').remove();

            //get attributes of the newTeaser
            var text = mElem_Textarea_NewsText.val();
            var url = mElem_Input_NewsUrl.val();
            var title = mElem_Input_NewsTitle.val();
            var date = mElem_Input_NewsDate.val();

            //check if user input is korrekt
            validateText(text, mElem_Textarea_NewsText);
            validateUrl(url, mElem_Input_NewsUrl);
            validateTitle(title, mElem_Input_NewsTitle);
            validateDate(date, mElem_Input_NewsDate);

            //data to submit per ajax
            var objData = mObjNews = {
                admin: "true",
                object: "News",
                action: "insertNewsTeaser",
                text: text,
                url: url,
                title: title,
                date: date
            };
            
            console.log(mReadyToSubmit);

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.jsonRequest(
                        objData,
                        newsTeaserInsertCallback,
                        mElem_AjaxResponse);
            }

        });
    };

    /**
     * public function registerClickEventDeleteNewsTeaser
     * @returns void
     */
    this.registerClickEventDeleteNewsTeaser = function() {

        $(document).on('click', '.' + mElemClass_Button_DeleteNewsTeaser, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');

            //get newsId
            var newsId = $(this).attr('data-newsId');

            //check if user input is korrekt
            validateNewsId(newsId);

            //data to submit per ajax
            var objData = mObjNews = {
                admin: "true",
                object: "News",
                action: "deleteNewsTeaser",
                newsId: newsId
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this NewsTeaser?");

            //call ajax-request to delete Table
            if (mReadyToSubmit && confirmed) {
                mObjFunc.jsonRequest(
                        objData,
                        newsTeaserDeleteCallback,
                        this);
            }

        });
    };  
    
    /**
     * callback-function to handle response of
     * the ajax-request to delete a newsTeaser
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function newsTeaserDeleteCallback(response, element) {

        //check if request was successful
        if (response.success === 'true') {

            //remove table from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log(response);
        }
    }
    
    /**
     * callback-function to handle response of
     * the ajax-request to add a NewsTeaser
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function newsTeaserInsertCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //check if request was successful
        if (response.success === 'true') {

            //add comptable to table
            var op = '<tr>';
            op += '<td>' + response.title + '</td>';
            op += '<td>' + response.url + '</td>';
            op += '<td>' + response.text + '</td>';
            op += '<td>' + response.date + '</td>';


            //elem to delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_newsTeaser" '
                    + 'data-newsId="' + (response.newsId)
                    + '"></i>';
            op += '</td>';

            op += '</tr>';           
            
            mElem_TableOfNewsTeaser.find('tr').first().after(op);
            mElem_TableOfNewsTeaser.find('tr').first().next().hide();
            mElem_TableOfNewsTeaser.find('tr').first().next().fadeIn('slow');

        } else {
            $(element).text('NewsTeaser konnte nicht gespeichert werden :o(');
            $(element).fadeIn('fast');
        }
    }    
    
    
    /**
     * validateTitle
     * @param title - String
     * @returns void
     */
    function validateTitle(title, elem)
    {
        valid = (typeof title === 'string' || title instanceof String);
        
        if(true === valid) {
            valid = title.length <= 1000;
        }

        //validate tableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Title ist nicht gültig.</p>');
        }
    }  
    
    /**
     * validateText
     * @param text - String
     * @returns void
     */
    function validateText(text, elem)
    {
        valid = (typeof text === 'string' || text instanceof String);
        
        if(true === valid) {
            valid = text.length <= 1000;
        }

        //validate tableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Text ist nicht gültig.</p>');
        }
    }      
    
    /**
     * validateTableName
     * @param tableName - String
     * @returns void
     */
    function validateDate(date, elem) {
        var regex = /^[\d]{2}\-[\d]{2}-[\d]{4}$/;
        var valid = regex.test(date);

        //validate tableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Date ist nicht gültig.</p>');
        }
    }       

    /**
     * validate url
     * @param url - String
     * @returns void
     */
    function validateUrl(url, elem) {

        //validate tableName
        if (!mObjFunc.validateUrl(url)) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Url ist nicht gültig.</p>');
        }
    }
    
    /**
     * validateNewsId
     * @param newsId - Integer
     * @returns void
     */
    function validateNewsId(newsId, elem) {

        //validate tableName
        if (!mObjFunc.isNumeric(newsId)) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">newsId ist nicht gültig.</p>');
        }
    }    
    
    
    /**
     * public setElem_AjaxResponse
     * @param elem - Element
     * @returns void
     */
    this.setElem_AjaxResponse = function(elem) {
        if (elem) {
            mElem_AjaxResponse = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_AjaxResponse: elem is not valid.");
        }
    };

    /**
     * public setElem_AjaxError
     * @param elem - Element
     * @returns void
     */
    this.setElem_AjaxError = function(elem) {
        if (elem) {
            mElem_AjaxError = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_AjaxError: elem is not valid.");
        }
    };
    
    this.setElem_Input_NewsTitle = function(elem) {
        if (elem) {
            mElem_Input_NewsTitle = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_NewsTitle: elem is not valid.");
        }
    };    
    this.setElem_Input_NewsUrl = function(elem) {
        if (elem) {
            mElem_Input_NewsUrl = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_NewsUrl: elem is not valid.");
        }
    };   
    this.setElem_Textarea_NewsText = function(elem) {
        if (elem) {
            mElem_Textarea_NewsText = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Textarea_NewsText: elem is not valid.");
        }
    };      
    this.setElem_Input_NewsDate = function(elem) {
        if (elem) {
            mElem_Input_NewsDate = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_NewsDate: elem is not valid.");
        }
    };          
    this.setElem_TableOfNewsTeaser = function(elem) {
        if (elem) {
            mElem_TableOfNewsTeaser = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfNewsTeaser: elem is not valid.");
        }
    };
    
    this.setElem_Button_InsertNewsTeaser = function(elem) {
        if (elem) {
            mElem_Button_InsertNewsTeaser = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertNewsTeaser: elemClass is not valid.");
        }
    };        
    this.setElemClass_Button_DeleteNewsTeaser = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeleteNewsTeaser = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeleteNewsTeaser: elemClass is not valid.");
        }
    };    
};

/**
 * register Mouse-Events to add a new News to DB
 */
$(document).ready(function() {

    var objNews = new News();
    objNews.init();
    
    //set inputFields
    objNews.setElem_Input_NewsTitle($("[name='newsTitle']"));
    objNews.setElem_Input_NewsUrl($("[name='newsUrl']"));
    objNews.setElem_Textarea_NewsText($("[name='newsText']"));
    objNews.setElem_Input_NewsDate($("[name='newsDate']"));
    
    objNews.setElem_Button_InsertNewsTeaser($("[name='insert']"));
    objNews.setElemClass_Button_DeleteNewsTeaser('btn_delete_newsTeaser'); 
    
    objNews.setElem_AjaxError($('.errorMsg'));
    objNews.setElem_AjaxResponse($('.userResponse'));
    
    //set tables to include added data per Ajax
    objNews.setElem_TableOfNewsTeaser($('#hyb_tableOfNewsTeaser'));       

    //register ClickEvents
    objNews.registerClickEventInsertNewsTeaser();
    objNews.registerClickEventDeleteNewsTeaser();
    

}); //end document.ready