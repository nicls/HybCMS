
//Class Poll
var Poll = function() {

    /**
     * Poll as Json
     * @type String
     */
    var mObjPoll;

    /**
     * readyToSubmit indicates if an Ajax-Request should be done or not
     * @type Boolean
     */
    var mReadyToSubmit = true;

    /**
     * Id of the selected Radio Question
     * @type Integer 
     */
    var mSelectedQuestionIdRadio = '';

    /**
     * List of QuestionIds of Checkboxes the user has selected
     * @type Integer[]
     */
    var mSelectedQuestionIdsCheckbox = '';

    /**
     * List of custom Ansers of the user
     * @type String[]
     */
    var mSelectedQuestionIdsCustomText = '';

    /**
     * All QuestionIds of the current Poll
     * @type String
     */
    var mAllQuestionIds = '';

    //Buttons
    var mElemClass_Button_RadioSelected;
    var mElemClass_Button_RadioNotSelected;
    var mElemClass_Button_CheckboxChecked;
    var mElemClass_Button_CheckboxUnchecked;
    var mElemClass_Textarea_CustomText;
    var mElem_Button_SavePollAnswers;
    var mElemClass_Button_ClosePoll;
    var mElemClass_Button_OpenPoll;

    /**
     * PollContainer
     * @type elem
     */
    var mElemPollContainer;

    /**
     * Margin of Poll to bottom in px when its open
     * @type Integer
     */
    var mMarginBottom = 20;

    /**
     * Margin of Poll to right in px when its open
     * @type Integer
     */
    var mMarginRight = 20;

    /**
     * Thank you message for answering a poll
     * @type String
     */
    var mThankYouMsg = "Vielen Dank! :)";

    /**
     * Id of the current poll
     * @type Iteger
     */
    var mPollId;

    /**
     * Indicates how many pageloads the poll keeps closed after the user clicked 
     * the close button.
     * 
     * @type Number
     */
    var plKeepClosed = 20;

    /*
     * Indicates if the Poll was opened for the first time at the current pageview
     * @type Boolean
     */
    var firstOpened = true;

    /**
     * Polls the user has answered should not be shown again
     * @type Array
     */
    var mArrAnsweredPolls = new Array();

    /**
     * public function to init Poll
     * @returns void
     */
    this.init = function init() {

        //initiate functions-Object
        mObjFunc = new globFunctions();
    };

    this.initialOpenPoll = function() {

        //get current pollId
        mPollId = $(mElemPollContainer).attr('pollId');
            
        //check if user has answered this poll previously
        if (!pollWasAnsweredPreviously()) {

            //set bottom and right position
            var heightPollContainer = $(mElemPollContainer).height();
            var heightHeader = $(mElemPollContainer).find('header').height();
            $(mElemPollContainer).css('bottom', '-' + (heightPollContainer - heightHeader - mMarginBottom - 5) + 'px');
            $(mElemPollContainer).css('right', mMarginRight + 'px');

            //get pollClose-Value from local storage
            var currValuePollClosed = localStorage.getItem('pollClosed-' + mPollId);
            
            //open poll if allowed
            if (!currValuePollClosed) {
                openPoll($('.' + mElemClass_Button_OpenPoll).first());
            } else {

                if (currValuePollClosed > 1) {
                    //increment number of pageloads
                    localStorage.setItem('pollClosed-' + mPollId, currValuePollClosed - 1);
                } else {
                    //remove item from localStoreage
                    localStorage.removeItem('pollClosed-' + mPollId);
                }
            }
        } else {
            
            //remove poll from DOM
            $(mElemPollContainer).remove();
            console.log('Poll was answered previously.');
        }
    };
    
    /**
     * Checks if current poll was answered previously
     * @returns {Boolean}
     */
    function pollWasAnsweredPreviously() {
    
        //get previously answered polls
        var answeredPolls = localStorage.answeredPolls;
        
        if(answeredPolls) {
            mArrAnsweredPolls = answeredPolls.split('|');
        }
        
        return (-1 !== $.inArray(mPollId, mArrAnsweredPolls));
    }

    /**
     * registerClickEventClosePoll
     * @returns {void}
     */
    this.registerClickEventClosePoll = function() {

        $(document).on('click', '.' + mElemClass_Button_ClosePoll, function(e) {
            closePoll(this);
        });
    };

    /**
     * registerClickEventOpenPoll
     * @returns {void}
     */
    this.registerClickEventOpenPoll = function() {

        $(document).on('click', '.' + mElemClass_Button_OpenPoll, function(e) {
            openPoll(this);
        });
    };

    /**
     * registerClickEventSavePollAnswers
     * @returns {void}
     */
    this.registerClickEventSavePollAnswers = function() {

        $(mElem_Button_SavePollAnswers).click(function() {
            numberOfAnswers = collectAnswers();

            if (numberOfAnswers > 0) {
                submitPollAnswers();
            } else {
                console.log('Nichts ausgewählt!');
            }
        });
    };



    /**
     * close the poll
     * @returns {void}
     */
    function closePoll(elem) {
        var heightPollContainer = $(mElemPollContainer).height();
        var heightHeader = $(mElemPollContainer).find('header').height();

        //hide Poll
        $(mElemPollContainer).animate({
            bottom: "-" + (heightPollContainer - heightHeader - mMarginBottom - 5)
        }, 1000, function() {

            //set pageloads keep closed until now
            localStorage.setItem('pollClosed-' + mPollId, plKeepClosed);
        });

        //change close icon to open icon
        $(elem).removeClass('closePoll');
        $(elem).addClass('openPoll');
        $(elem).removeClass('fa-times-circle');
        $(elem).addClass('fa-plus-circle');
    }

    /**
     * open the poll
     * @returns {void}
     */
    function openPoll(elem) {
        
        console.log(elem);
        
        var heightPollContainer = $(mElemPollContainer).height();

        //open Poll
        $(mElemPollContainer).animate({
            bottom: (mMarginBottom)
        }, 500, function() {

            if (firstOpened === true) {

                //every following opening is not the first at this pageview
                firstOpened = false;

                //collect QuestionIds
                numberOfQuestions = collectQuestions();

                if (numberOfQuestions > 0) {
                    //save view of each question in this poll
                    submitQuestionsViews();
                } else {
                    console.log('Error collecting QuestionIds!');
                }
            }

        });

        //change close icon to open icon
        $(elem).addClass('closePoll');
        $(elem).removeClass('openPoll');
        $(elem).addClass('fa-times-circle');
        $(elem).removeClass('fa-plus-circle');
    }

    function submitQuestionsViews() {


        mReadyToSubmit = true;

        if (mAllQuestionIds !== '') {

            //data to submit per ajax
            var objData = {
                plugin: "poll",
                action: 'submitPollQuestions',
                pollName: 'currPoll',
                allQuestionIds: mAllQuestionIds
            };

        } else {
            mReadyToSubmit = false;
            console.log('Error on submitting Request.');
        }

        //call ajax-request to update 
        if (mReadyToSubmit && !localStorage.hyb_poll_dontTrackMe) {

            mObjFunc.jsonRequest(
                    objData,
                    submitPollQuestionsCallback,
                    mElemPollContainer);
        }
    }

    /**
     * submitPollQuestionsCallback
     * @param {type} jsonResponse
     * @param {type} element
     * @returns {JSON}
     */
    function submitPollQuestionsCallback(jsonResponse, element) {
        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            console.log(jsonResponse);

        } else {
            console.log(jsonResponse);
        }
    }

    /**
     * collectQuestions
     * @returns {Number}
     */
    function collectQuestions() {

        //reset mAllQuestionIds
        mAllQuestionIds = '';

        var numberOfQuestions = 0;

        var arrLiElems = $(mElemPollContainer).find('li');

        $(arrLiElems).each(function() {
            var questionId = $(this).attr('questionId');

            //validate questionId
            if (mObjFunc.isNumeric(questionId)) {
                mAllQuestionIds += questionId + '|';
                numberOfQuestions++;
            }
        });

        return numberOfQuestions;

    }

    /**
     * Collects all questionIds of selected Questions
     * @returns {Integer} Number of selected Questions
     */
    function collectAnswers() {
        var arrLiElems = $(mElem_Button_SavePollAnswers).parent().find('li');
        var numberOfAnswers = 0;

        $(arrLiElems).each(function() {

            //check if Question was selected
            if ($(this).attr('selected') === 'selected') {

                var type = $(this).attr('type');
                var questionId = $(this).attr('questionId');

                //validate questionId
                if (mObjFunc.isNumeric(questionId)) {

                    numberOfAnswers++;

                    //get type of Question
                    if (type === 'radio') {
                        addQuestionIdRadio(questionId);
                    } else if (type === 'checkbox') {
                        addQuestionIdCheckbox(questionId);
                    } else if (type === 'customText') {
                        addQuestionIdCustomText(this, questionId);
                    }
                }
            }
        });

        return numberOfAnswers;
    }

    /**
     * Adds a given QuestionId from a RadioQuestion to mSelectedQuestionIdRadio. 
     * Format is n (Only one Id is allowed)
     * @param {Integer} questionId
     * @returns {void}
     */
    function addQuestionIdRadio(questionId) {
        mSelectedQuestionIdRadio = questionId;
    }

    /**
     * Adds a given QuestionId from a CheckBoxQuestion to mArrSelectedQuestionIdsCheckbox. 
     * Format is n|m|k
     * @param {Integer} questionId
     * @returns {void}
     */
    function addQuestionIdCheckbox(questionId) {
        mSelectedQuestionIdsCheckbox += questionId + '|';
    }

    /**
     * Adds a given QuestionId from a customTextQuestion and its custom answer
     * to mArrSelectedQuestionIdsCustomText. 
     * Format is n|CustomTextOfN|m|customTextOfM|k|customTextOfK
     * @param {element} elemLi      
     * @param {Integer} questionId
     * @returns {void}
     */
    function addQuestionIdCustomText(elemLi, questionId) {

        //get customText
        var customText = $(elemLi).children('textarea').first().val();

        if (customText && customText !== '') {

            mSelectedQuestionIdsCustomText += questionId + '|';
            mSelectedQuestionIdsCustomText += customText + '|';
        }
    }

    /**
     * registerClickEventRadioSelect
     * @returns {void}
     */
    this.registerClickEventRadioSelect = function() {
        $(document).on('click', '.' + mElemClass_Button_RadioNotSelected, function(e) {

            //deselect all selected RadioButtons
            $('.' + mElemClass_Button_RadioSelected).parent().removeAttr('selected');
            $('.' + mElemClass_Button_RadioSelected).addClass(mElemClass_Button_RadioNotSelected);
            $('.' + mElemClass_Button_RadioSelected).removeClass(mElemClass_Button_RadioSelected);

            //select this
            $(this).addClass(mElemClass_Button_RadioSelected);

            //mark as selected
            $(this).parent().attr('selected', 'selected');

            console.log('selected');
        });
    };

    /**
     * registerClickEventCheckboxCheck
     * @returns {void}
     */
    this.registerClickEventCheckboxCheck = function() {
        $(document).on('click', '.' + mElemClass_Button_CheckboxUnchecked, function(e) {

            //select this
            $(this).addClass(mElemClass_Button_CheckboxChecked);
            $(this).removeClass(mElemClass_Button_CheckboxUnchecked);

            //mark as selected
            $(this).parent().attr('selected', 'selected');

            console.log('checked');
        });
    };

    /**
     * registerClickEventCheckboxUncheck
     * @returns {void}
     */
    this.registerClickEventCheckboxUncheck = function() {
        $(document).on('click', '.' + mElemClass_Button_CheckboxChecked, function(e) {

            //uncheck this
            $(this).addClass(mElemClass_Button_CheckboxUnchecked);
            $(this).removeClass(mElemClass_Button_CheckboxChecked);

            //mark as unchecked
            $(this).parent().removeAttr('selected');

            console.log('unchecked');
        });
    };

    /**
     * registerBlurEventTextareaCustomText
     * @returns {void}
     */
    this.registerBlurEventTextareaCustomText = function() {
        $(document).on('blur', '.' + mElemClass_Textarea_CustomText, function(e) {

            //check if textarea is filled
            if ($(this).val() !== '') {
                $(this).parent().attr('selected', 'selected');
            } else {
                $(this).parent().removeAttr('selected');
            }

            console.log('blured');
        });
    };

    /**
     * requestPoll
     * @returns {undefined}
     */
    function submitPollAnswers() {

        mReadyToSubmit = true;

        if (mSelectedQuestionIdRadio !== ''
                || mSelectedQuestionIdsCheckbox !== ''
                || mSelectedQuestionIdsCustomText !== '') {

            //data to submit per ajax
            var objData = {
                plugin: "poll",
                action: 'submitPollAnswers',
                pollName: 'currPoll',
                selectedQuestionIdRadio: mSelectedQuestionIdRadio,
                selectedQuestionIdsCheckbox: mSelectedQuestionIdsCheckbox,
                selectedQuestionIdsCustomText: mSelectedQuestionIdsCustomText
            };

        } else {
            mReadyToSubmit = false;
            console.log('Error on submitting Request.');
        }

        //call ajax-request to update 
        if (mReadyToSubmit && !localStorage.hyb_poll_dontTrackMe) {

            mObjFunc.jsonRequest(
                    objData,
                    submitPollAnswersCallback,
                    mElem_Button_SavePollAnswers);
        }
    }

    /**
     * submitPollAnswersCallback
     * @param {type} jsonResponse
     * @param {type} element
     * @returns {undefined}
     */
    function submitPollAnswersCallback(jsonResponse, element) {
        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            //replace questions with thank you msg
            $(mElemPollContainer).find('ul').fadeOut('fast', function() {
                $(this).replaceWith('<p id="thankYouMsg">' + mThankYouMsg + '</p>');
            });

            //remove send button
            $(mElem_Button_SavePollAnswers).fadeOut('fast', function() {
                $(this).remove();

                window.setTimeout(function() {
                    $(mElemPollContainer).fadeOut('fast');
                }, 3000);
            });
            
            //mark poll as answered
            localStorage.answeredPolls  = localStorage.answeredPolls + '|' + mPollId;

        } else {
            console.log(jsonResponse);
        }
    }

    /**
     * validateThankYouMsg
     * @param String thankYouMsg
     * @returns void
     */
    function validateThankYouMsg(thankYouMsg) {
        var regex = /^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\/\(\)\!:\?]{1,255}<i class="fa fa-smile-o"><\/i>$/;
        var valid = regex.test(thankYouMsg);

        //validate value
        if (!valid) {
            return false;
            console.log('ThankYouMsg is not valid.');
        } else {
            return true;
        }
    }

    /**
     * Setter
     * @param {Element} elemClass
     * @returns {void}
     */
    this.setElemClass_Button_RadioSelected = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_RadioSelected = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_RadioSelected: elemClass is not valid.");
        }
    };
    this.setElemClass_Button_RadioNotSelected = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_RadioNotSelected = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_RadioNotSelected: elemClass is not valid.");
        }
    };
    this.setElemClass_Button_CheckboxChecked = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_CheckboxChecked = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_CheckboxChecked: elemClass is not valid.");
        }
    };
    this.setElemClass_Button_CheckboxUnchecked = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_CheckboxUnchecked = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_CheckboxUnchecked: elemClass is not valid.");
        }
    };
    this.setElemClass_Textarea_CustomText = function(elemClass) {
        if (elemClass) {
            mElemClass_Textarea_CustomText = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Textarea_CustomText: elemClass is not valid.");
        }
    };
    this.setElem_Button_SavePollAnswers = function(elem) {
        if (elem) {
            mElem_Button_SavePollAnswers = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_SavePollAnswers: elem is not valid.");
        }
    };
    this.setElemClass_Button_ClosePoll = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_ClosePoll = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_ClosePoll: elem is not valid.");
        }
    };
    this.setElemClass_Button_OpenPoll = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_OpenPoll = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_OpenPoll: elem is not valid.");
        }
    };
    this.setElemPollContainer = function(elem) {
        if (elem) {
            mElemPollContainer = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemPollContainer: elem is not valid.");
        }
    };
    this.setThankYouMsg = function(thankYouMsg) {

        if (validateThankYouMsg(thankYouMsg) === true) {
            mThankYouMsg = thankYouMsg;
        } else {
            mReadyToSubmit = false;
            console.log("function setThankYouMsg: thankYouMsg is not valid.");
        }
    };
    this.setMarginBottom = function(marginBottom) {

        if (mObjFunc.isNumeric(marginBottom)) {

            mMarginBottom = marginBottom;

        } else {
            mReadyToSubmit = false;
            console.log("function setMarginBottom: marginBottom is not valid.");
        }
    };
    this.setMarginRight = function(marginRight) {

        if (mObjFunc.isNumeric(marginRight)) {
            mMarginRight = marginRight;
        } else {
            mReadyToSubmit = false;
            console.log("function setMarginRight: marginRight is not valid.");
        }
    };
};