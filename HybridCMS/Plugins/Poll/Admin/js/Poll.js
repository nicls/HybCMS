var Poll = function() {

    /**
     * globaly needed functions
     * @type globFunctions
     */
    var mObjFunc;

    /**
     * Current Poll 
     * @type Object
     */
    var mObjPoll;

    /**
     * UI-Elements
     */

    //Input Elements   
    var mElem_Input_PollId;
    var mElem_Input_PollName;
    var mElem_Input_PollQuestion;
    var mElem_Select_PollQuestionType;
    var mElem_Select_PollQuestionPriority;

    //checkbox
    var mElem_Checkbox_PollQuestionActive;

    //Buttons Insert
    var mElem_Button_InsertPoll;
    var mElem_Button_InsertPollQuestion;

    //Buttons delete
    var mElemClass_Button_DeletePoll;
    var mElemClass_Button_DeletePollQuestion;

    //UI-Elements
    var mElem_TableOfPolls;
    var mElem_TableOfPollsQuestions;

    //text-buttons and text-Attributes
    var mElemClass_hyb_editText;
    var mCurrElem_hyb_editText;
    var mNewText_editText;
    var mOldText_editText;

    //text-buttons that changes to select-boxes
    var mElemClass_hyb_editSelectBox;
    var mOldOptionValue_editSelectBox;
    var mNewOptionValue_editSelectBox;
    var mCurrElem_hyb_editSelectBox;

    //checkboxes
    var mElemClass_hyb_editCheckboxUnchecked;
    var mElemClass_hyb_editCheckboxChecked;
    var mCurrElem_hyb_editCheckbox;

    //Prevent admin-visits from tracking
    var mElem_hyb_checkbox_dontTrackMe;
    var mElemClass_hyb_checkbox_dontTrackMeUnchecked;
    var mElemClass_hyb_checkbox_dontTrackMeChecked;

    /**
     * readyToSubmit indicates if an Ajax-Request should be done or not
     * @type Boolean
     */
    var mReadyToSubmit = true;

    /**
     * public function to init Poll
     * @returns void
     */
    this.init = function init() {

        //initiate functions-Object
        mObjFunc = new globFunctions();
    };


    /**
     * registerClickEventDontTrackMe
     * @param {type} classChecked
     * @param {type} classUnchecked
     * @returns {void}
     */
    this.registerClickEventDontTrackMe = function(classUnchecked, classChecked) {

        mElemClass_hyb_checkbox_dontTrackMeChecked = classChecked;
        mElemClass_hyb_checkbox_dontTrackMeUnchecked = classUnchecked;

        console.log('reg');

        //initially set state
        if (localStorage.hyb_poll_dontTrackMe) {
            checkDontTrackMe(mElem_hyb_checkbox_dontTrackMe);
        }

        $(mElem_hyb_checkbox_dontTrackMe).click(function() {

            if ($(this).hasClass(mElemClass_hyb_checkbox_dontTrackMeChecked)) {
                uncheckDontTrackMe(this);
            } else {
                checkDontTrackMe(this);
            }

        });
    };

    /**
     * Sets a flag to track admins views and clicks
     * @returns {void}
     */
    function checkDontTrackMe(elem) {
        //uncheck checkbox
        $(elem).addClass(mElemClass_hyb_checkbox_dontTrackMeChecked);
        $(elem).removeClass(mElemClass_hyb_checkbox_dontTrackMeUnchecked);

        //add flag to local Storage
        localStorage.setItem('hyb_poll_dontTrackMe', '1');

        console.log('checked');
    }

    /**
     * Sets a flag to prevent admin from getting tracked
     * @returns {void}
     */
    function uncheckDontTrackMe(elem) {
        //uncheck checkbox
        $(elem).addClass(mElemClass_hyb_checkbox_dontTrackMeUnchecked);
        $(elem).removeClass(mElemClass_hyb_checkbox_dontTrackMeChecked);
        
        //remove flag from local storeage
        localStorage.removeItem('hyb_poll_dontTrackMe');

        console.log('unchecked');
    }

    /**
     * public function registerClickEventEditText
     * @returns void
     */
    this.registerClickEventEditText = function() {

        $(document).on('click', '.' + mElemClass_hyb_editText, function(e) {

            mOldText_editText = '';
            mNewText_editText = '';
            mCurrElem_hyb_editText = this;
            $(mCurrElem_hyb_editText).toggleClass(mElemClass_hyb_editText);
            $(mCurrElem_hyb_editText).find('.errorMsg').remove();

            var elemSpan = $(mCurrElem_hyb_editText).children().first();

            //get oringinal text
            mOldText_editText = $(elemSpan).text().trim();

            $(elemSpan).text('');
            $(elemSpan).append('<textarea id="hyb_currElemEditing">' + mOldText_editText + '</textarea>');
            $(mCurrElem_hyb_editText).find('#hyb_currElemEditing').focus();

            //register blur-event to make a textarea to span
            registerBlurEventEditText(this);
        });
    };//end function registerClickEventEditText 

    /**
     * public function registerChangeEventEditSelectBox
     * @returns void
     */
    this.registerClickEventEditSelectBox = function() {

        $(document).on('click', '.' + mElemClass_hyb_editSelectBox, function(e) {

            mOldOption_editSelectBox = '';
            mNewOption_editSelectBox = '';
            mCurrElem_hyb_editSelectBox = this;

            $(mCurrElem_hyb_editSelectBox).toggleClass(mElemClass_hyb_editSelectBox);
            $(mCurrElem_hyb_editSelectBox).find('.errorMsg').remove();

            var elemSpan = $(mCurrElem_hyb_editSelectBox).children().first();

            //get oringinal text
            mOldOptionValue_editSelectBox = $(elemSpan).text().trim();

            //get options
            var options = getOptions();

            $(elemSpan).text('');
            $(elemSpan).append('<select id="hyb_currElemEditing">' + options + '</select>');

            //register change-event to make a textarea to span
            registerChangedEventEditSelectBox(this);
        });
    };//end function registerClickEventEditText     


    /**
     * registerClickEventEditDatasetIsFavorit
     * @returns void
     */
    this.registerClickEventEditCheckbox = function() {

        $(document).on('click', '.' + mElemClass_hyb_editCheckboxUnchecked, function(e) {
            handleClickEventEditCheckbox(e, this, 1);
        });

        $(document).on('click', '.' + mElemClass_hyb_editCheckboxChecked, function(e) {
            handleClickEventEditCheckbox(e, this, 0);
        });
    };

    /**
     * handleClickEventEditCheckbox - invoked by
     * registerClickEventEditTableIsFavorit()
     * 
     * @param e - Event
     * @param elem - Element
     * @param state - Integer (1 or 0)
     * @returns void
     */
    function handleClickEventEditCheckbox(e, elem, state) {

        //precent formsubission
        e.preventDefault();

        mCurrElem_hyb_editCheckbox = $(elem).parent();

        mReadyToSubmit = true;

        //get action
        var action = ajax_editCheckbox_getAction();

        mReadyToSubmit = true;

        if (action) {

            var pollName = $(elem).attr('pollName');
            var pollId = $(elem).attr('pollId');
            var pollQuestionId = $(elem).attr('questionId');

            //data to submit per ajax
            var objData = mObjPoll = {
                admin: "true",
                object: "poll",
                action: action,
                pollName: pollName,
                pollId: pollId,
                pollQuestionId: pollQuestionId,
                checkboxState: state
            };

        } else {
            mReadyToSubmit = false;
            console.log('Error on submitting Request. Action is not defined.');
        }

        //call ajax-request to update 
        if (mReadyToSubmit) {

            mObjFunc.jsonRequest(
                    objData,
                    editCheckboxUpdateCallback,
                    elem);
        }
    }


    /**
     * getOptions
     * @returns {String}
     */
    function getOptions() {
        var objRefTmp = mCurrElem_hyb_editSelectBox;
        var op = '';

        ////////////////////////////////////////////////////
        //options for attribut priority of a pollQuestion //
        ////////////////////////////////////////////////////
        if ($(objRefTmp).hasClass('hyb_pollQuestion_priority')) {

            for (i = 1; i < 10; i++) {

                var state = '';
                if (mOldOptionValue_editSelectBox == i) {
                    state = "selected='selected'";
                }

                op += '<option ' + state + '>' + i + '</option>';
            }
            return op;
        }
        //////////////////////////////////////////////////
        //options for attribut type of a pollQuestion ////
        //////////////////////////////////////////////////
        else if ($(objRefTmp).hasClass('hyb_pollQuestion_type')) {

            //radio
            op += '<option';
            if (mOldOptionValue_editSelectBox === 'radio') {
                op += ' selected="selected"';
            }
            op += '>radio</option>';

            //checkbox
            op += '<option';
            if (mOldOptionValue_editSelectBox === 'checkbox') {
                op += ' selected="selected"';
            }
            op += '>checkbox</option>';

            //customText
            op += '<option';
            if (mOldOptionValue_editSelectBox === 'customText') {
                op += ' selected="selected"';
            }
            op += '>customText</option>';

            return op;
        }
    }

    /**
     * registerChangedEventEditSelectBox
     * @param {Element} elem
     * @returns {void}
     */
    function registerChangedEventEditSelectBox(elem) {
        $(elem).find('#hyb_currElemEditing').change(function() {

            //get selected Value
            mNewOptionValue_editSelectBox = $(this).val().trim();
            var elemSpan = $(this).parent();

            //reconstruct the old state if old-text is new-text
            if (mNewOptionValue_editSelectBox === mOldOptionValue_editSelectBox) {
                console.log('Nothing changed!');
                $(this).replaceWith(mOldOptionValue_editSelectBox);
                $(mCurrElem_hyb_editSelectBox).toggleClass(mElemClass_hyb_editSelectBox);

                return;
            }

            //get action
            var action = ajax_editSelectBox_getAction();

            mReadyToSubmit = true;

            if (action) {

                var pollName = $(elemSpan).attr('pollName');
                var pollId = $(elemSpan).attr('pollId');
                var pollQuestionId = $(elemSpan).attr('questionId');

                //data to submit per ajax
                var objData = mObjPoll = {
                    admin: "true",
                    object: "poll",
                    action: action,
                    pollName: pollName,
                    pollId: pollId,
                    pollQuestionId: pollQuestionId,
                    optionValue: mNewOptionValue_editSelectBox
                };

            } else {
                mReadyToSubmit = false;
                console.log('Error on submitting Request. Action is not defined.');
            }

            //call ajax-request to update 
            if (mReadyToSubmit) {

                mObjFunc.jsonRequest(
                        objData,
                        editSelectBoxUpdateCallback,
                        elemSpan);
            }
        });
    }


    /**
     * registerBlurEventEditText - gets invoked when the focus leaves an 
     * textarea update box
     * 
     * @param elem - Element
     * @returns void
     */
    function registerBlurEventEditText(elem) {

        $(elem).find('#hyb_currElemEditing').blur(function() {

            mNewText_editText = $(this).val().trim();
            var elemSpan = $(this).parent();

            //reconstruct the old state if old-text is new-text
            if (mNewText_editText === mOldText_editText) {
                console.log('Nothing changed!');
                $(this).replaceWith(mOldText_editText);
                $(mCurrElem_hyb_editText).toggleClass(mElemClass_hyb_editText);

                return;
            }

            var action = ajax_editText_getAction();

            mReadyToSubmit = true;

            if (action) {

                var pollName = $(elemSpan).attr('pollName');
                var pollId = $(elemSpan).attr('pollId');
                var pollQuestionId = $(elemSpan).attr('questionId');

                //data to submit per ajax
                var objData = mObjPoll = {
                    admin: "true",
                    object: "poll",
                    action: action,
                    pollName: pollName,
                    pollId: pollId,
                    pollQuestionId: pollQuestionId,
                    textValue: mNewText_editText
                };

            } else {
                mReadyToSubmit = false;
                console.log('Error on submitting Request. Action is not defined.');
            }

            //call ajax-request to update 
            if (mReadyToSubmit) {

                mObjFunc.jsonRequest(
                        objData,
                        editTextUpdateCallback,
                        elemSpan);
            }

        });
    }
    ;//end function registerBlurEventEditText

    /**
     * ajax_editText_getAction - get action of the current text editing
     * @returns string
     */
    function ajax_editText_getAction() {
        var action = '';
        var objRefTmp = mCurrElem_hyb_editText;

        if ($(objRefTmp).hasClass('hyb_poll_pollName')) {
            action = 'updatePollName';
        } else if ($(objRefTmp).hasClass('hyb_pollQuestion_question')) {
            action = 'updatePollQuestion';
        } else if ($(objRefTmp).hasClass('hyb_poll_pollInfo')) {
            action = 'updateInfo';
        }

        return action;
    }

    /**
     * ajax_editSelectBox_getAction - get action of the current selectBox editing
     * @returns string
     */
    function ajax_editSelectBox_getAction() {
        var action = '';
        var objRefTmp = mCurrElem_hyb_editSelectBox;

        if ($(objRefTmp).hasClass('hyb_pollQuestion_priority')) {
            action = 'updatePollQuestionPriority';
        } else if ($(objRefTmp).hasClass('hyb_pollQuestion_type')) {
            action = 'updatePollQuestionType';
        }

        return action;
    }

    /**
     * ajax_editCheckbox_getAction - get action of the current checkbox editing
     * @returns string
     */
    function ajax_editCheckbox_getAction() {
        var action = '';
        var objRefTmp = mCurrElem_hyb_editCheckbox;

        if ($(objRefTmp).hasClass('hyb_pollQuestion_active')) {
            action = 'updatePollQuestionActive';
        }

        return action;
    }

    /**
     * public function to handle event to insert a pollQuestion
     * @returns {undefined}
     */
    this.registerClickEventInsertPollQuestion = function() {
        mElem_Button_InsertPollQuestion.click(function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            $(document).find('.userResponse').remove();

            //get question
            var pollId = mElem_Input_PollId.val();
            var pollName = mElem_Input_PollName.val();
            var question = mElem_Input_PollQuestion.val();
            var questionType = mElem_Select_PollQuestionType.val();
            var questionPriority = mElem_Select_PollQuestionPriority.val();
            var questionActive = '1';
            if (!mElem_Checkbox_PollQuestionActive.is(':checked')) {
                questionActive = '0';
            }
            //check if user input is korrekt
            validatePollId(pollId);
            validatePollName(pollName);
            validatePollQuestion(question);
            validatePollQuestionType(questionType);
            validatePollQuestionPriority(questionPriority);

            //data to submit per ajax
            var objData = mObjPoll = {
                admin: "true",
                object: "poll",
                action: "insertPollQuestion",
                pollId: pollId,
                pollName: pollName,
                pollQuestion: question,
                pollQuestionType: questionType,
                pollQuestionPriority: questionPriority,
                pollQuestionActive: questionActive
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.jsonRequest(
                        objData,
                        pollQuestionInsertCallback,
                        this);
            }

        });
    };

    /**
     * Register click event to delete a pollQuestion
     * @returns {void}
     */
    this.registerClickEventDeletePollQuestion = function() {

        $(document).on('click', '.' + mElemClass_Button_DeletePollQuestion, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            $(document).find('.userResponse').remove();

            //get question
            var pollName = $(this).attr('pollName');
            var questionId = $(this).attr('questionId');

            //check if user input is korrekt
            validatePollName(pollName);
            validatePollQuestionId(questionId);

            //data to submit per ajax
            var objData = mObjPoll = {
                admin: "true",
                object: "poll",
                action: "deletePollQuestion",
                pollName: pollName,
                pollQuestionId: questionId
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this PollQuestion?");

            //call ajax-request to delete Table
            if (mReadyToSubmit && confirmed) {

                mObjFunc.jsonRequest(
                        objData,
                        pollQuestionDeleteCallback,
                        this);
            }

        });
    };

    /**
     * public function registerClickEventInsertPoll
     * @returns void
     */
    this.registerClickEventInsertPoll = function() {

        mElem_Button_InsertPoll.click(function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            $(document).find('.userResponse').remove();

            //get pollName
            var pollName = mElem_Input_PollName.val();

            //check if user input is korrekt
            validatePollName(pollName);

            //data to submit per ajax
            var objData = mObjPoll = {
                admin: "true",
                object: "poll",
                action: "insertPoll",
                pollName: pollName
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.jsonRequest(
                        objData,
                        pollInsertCallback,
                        this);
            }

        });
    };//end function registerClickEventInsertPoll    

    /**
     * public function registerClickEventDeletePoll
     * @returns void
     */
    this.registerClickEventDeletePoll = function() {

        $(document).on('click', '.' + mElemClass_Button_DeletePoll, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //hide previous mnessages
            $('.userResponse').remove();

            //get pollId
            var pollId = $(this).attr('pollId');
            var pollName = $(this).attr('pollName');

            //check if user input is korrekt
            validatePollId(pollId);
            validatePollName(pollName);

            //data to submit per ajax
            var objData = mObjPoll = {
                admin: "true",
                object: "poll",
                action: "deletePoll",
                pollId: pollId,
                pollName: pollName
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this Poll?");

            //call ajax-request to delete Table
            if (mReadyToSubmit && confirmed) {
                mObjFunc.jsonRequest(
                        objData,
                        pollDeleteCallback,
                        this);
            }

        });
    };//end function registerClickEventDeletePoll 

    /**
     * callback-function to handle response of
     * the ajax-request to update a textfield
     * 
     * @param jsonResponse - String
     * @param element - Element
     * @returns void
     */
    function editTextUpdateCallback(jsonResponse, element) {

        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            $(element).find('textarea').replaceWith(mNewText_editText);

            $(mCurrElem_hyb_editText).toggleClass(mElemClass_hyb_editText);

            $(element).after(' <i id="textEdit_successful" class="green fa fa-check"></i>');

            window.setTimeout(function() {
                $('#textEdit_successful').fadeOut('slow');
                $('#textEdit_successful').remove();
            }, 2000);

        } else {
            $(element).find('textarea').replaceWith(mOldText_editText);
            $(element).after(' <span class="red errorMsg">Request failed <i class="fa fa-exclamation"></i></span>');
            $(mCurrElem_hyb_editText).toggleClass(mElemClass_hyb_editText);
            console.log(jsonResponse);
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to update a textfield
     * 
     * @param jsonResponse - String
     * @param element - Element
     * @returns void
     */
    function editSelectBoxUpdateCallback(jsonResponse, element) {

        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            $(element).find('select').replaceWith(mNewOptionValue_editSelectBox);

            $(mCurrElem_hyb_editSelectBox).toggleClass(mElemClass_hyb_editSelectBox);

            $(element).after(' <i id="textEdit_successful" class="green fa fa-check"></i>');

            window.setTimeout(function() {
                $('#textEdit_successful').fadeOut('slow');
                $('#textEdit_successful').remove();
            }, 2000);

        } else {
            $(element).find('select').replaceWith(mOldOptionValue_editSelectBox);
            $(element).after(' <span class="red errorMsg">Request failed <i class="fa fa-exclamation"></i></span>');
            $(mCurrElem_hyb_editSelectBox).toggleClass(mElemClass_hyb_editSelectBox);
            console.log(jsonResponse);
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to update a selectBox
     * 
     * @param jsonResponse - String
     * @param element - Element
     * @returns void
     */
    function editCheckboxUpdateCallback(jsonResponse, element) {

        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            $(element).toggleClass(mElemClass_hyb_editCheckboxChecked);
            $(element).toggleClass(mElemClass_hyb_editCheckboxUnchecked);

        } else {
            console.log(jsonResponse);
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to delete a poll
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function pollDeleteCallback(jsonResponse, element) {

        //hide previous mnessages
        $('.userResponse').remove();

        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            //remove poll from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log("Fehler beim Löschen der Poll!");
        }

    }//end function pollDeleteCallback      

    /**
     * callback-function to handle response of
     * the ajax-request to delete a pollQuestion
     * 
     * @param jsonResponse - Json
     * @param element - Element
     * @returns void
     */
    function pollQuestionDeleteCallback(jsonResponse, element) {

        //hide previous mnessages
        $('.userResponse').remove();

        //check if request was successful
        if (jsonResponse.info.status === 'successful') {

            //remove poll from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log("Fehler beim Löschen der PollQuestion!");
        }

    }//end function pollQuestionDeleteCallback          

    /**
     * callback-function to handle response of
     * the ajax-request to add a pollQuestion
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function pollQuestionInsertCallback(jsonResponse, element) {

        //hide previous mnessages
        $('.userResponse').remove();

        //get question id
        questionId = jsonResponse.pollQuestion.questionId;

        //check if request was successful
        if (jsonResponse.info.status === 'successful' && mObjFunc.isNumeric(questionId)) {

            //add comptable to table
            var op = '<tr>';
            //question
            op += '<td class="hyb_pollQuestion_question hyb_editText">';
            op += '<span class="hyb_edit" '
                    + 'pollName="' + mObjPoll.pollName + '" '
                    + 'questionId="' + questionId + '" '
                    + '">';
            op += mObjPoll.pollQuestion;
            op += '</span>';
            op += '</td>';                    

            //priority
            op += '<td class="hyb_pollQuestion_priority hyb_editText">';
            op += '<span class="hyb_edit" '
                    + 'pollName="' + mObjPoll.pollName + '" '
                    + 'questionId="' + questionId + '" '
                    + '">';
            op += mObjPoll.pollQuestionPriority;
            op += '</span>';
            op += '</td>';

            //type
            op += '<td class="hyb_pollQuestion_type hyb_editSelectBox">';
            op += '<span class="hyb_edit" '
                    + 'pollName="' + mObjPoll.pollName + '" '
                    + 'questionId="' + questionId + '" '
                    + '">';
            op += mObjPoll.pollQuestionType;
            op += '</span>';
            op += '</td>';

            //Private
            op += '<td class="hyb_pollQuestion_active">';
            if (mObjPoll.pollQuestionActive === '1') {
                op += '<i class="hyb_edit fa fa-check-square" '
                        + 'pollName="' + mObjPoll.pollName + '" '
                        + 'questionId="' + questionId + '" '
                        + '">'
                        + '</i>';
            } else {
                op += '<i class="hyb_edit fa fa-square-o" '
                        + 'pollName="' + mObjPoll.pollName + '" '
                        + 'questionId="' + questionId + '" '
                        + '">'
                        + '</i>';
            }
            op += '</td>';

            //views
            op += '<td class="hyb_pollQuestion_views">';
            op += '<span>';
            op += 0;
            op += '</span>';
            op += '</td>';

            //selected
            op += '<td class="hyb_pollQuestion_selected">';
            op += '<span>';
            op += 0;
            op += '</span>';
            op += '</td>';

            //selectio-ratio
            op += '<td class="hyb_pollQuestion_ratio">';
            op += '<span>';
            op += 0;
            op += '</span>';
            op += '</td>';

            //TimeCreated
            op += '<td class="hyb_pollQuestion_timeCreated">';
            op += '<span>';
            op += 'now';
            op += '</span>';
            op += '</td>';

            //delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_pollQuestion" '
                    + 'pollName="' + mObjPoll.pollName + '" '
                    + 'questionId="' + questionId
                    + '"></i>';
            op += '</td>';

            op += '</tr>';

            mElem_TableOfPollQuestions.find('tr').first().after(op);
            mElem_TableOfPollQuestions.find('tr').first().next().hide();
            mElem_TableOfPollQuestions.find('tr').first().next().fadeIn('slow');

        } else {

            $(element).after('<p class="userResponse">Poll konnte nicht gespeichert werden :o(');
            $('.userResponse').hide();
            $('.userResponse').fadeIn('fast');

        }

    }

    /**
     * callback-function to handle response of
     * the ajax-request to add a poll
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function pollInsertCallback(jsonResponse, element) {

        //hide previous mnessages
        $('.userResponse').remove();

        //get pollId
        var pollId = jsonResponse.poll.pollId;
        var pollName = jsonResponse.poll.pollName;
        var pollInfo = jsonResponse.poll.info;

        validatePollId(pollId);
        validatePollName(pollName);

        //check if request was successful
        if (jsonResponse.info.status === 'successful' && mObjFunc.isNumeric(pollId)) {

            var op = '';
            op += '<tr>';

            //poll-name            
            op += '<td class="hyb_poll_pollName hyb_editText">';
            op += '<span class="hyb_edit" '
                    + 'pollName="' + (pollName) + '" '
                    + 'pollId="' + (pollId) + '" '
                    + '">';
            op += (pollName);
            op += '</span>';
            op += '</td>';
            
            //poll-info
            op += '<td class="hyb_poll_pollInfo hyb_editText">';
            op += '<span class="hyb_edit" '
                   + 'pollName="' + (pollName) + '" '
                   + 'pollId="' + (pollId) + '" '
                   + '">';
            op += (pollInfo);
            op += '</span>';
            op += '</td>';                

            //pollId
            op += '<td>';
            op += pollId;
            op += '</td>';

            //edit poll
            op += '<td>';
            op += '<a href="/admin/plugins/index.php?name=poll&action=editPoll&pollId='
                    + pollId
                    + '&pollName=' + pollName + '">';
            op += '<i class="fa fa-cog"></i>';
            op += '</a>';
            op += '</td>';

            //elem to delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_poll" '
                    + 'pollId="' + (pollId) + '" '
                    + 'pollName="' + (pollName)
                    + '"></i>';
            op += '</td>';

            op += '</tr>';

            mElem_TableOfPolls.find('tr').first().after(op);
            mElem_TableOfPolls.find('tr').first().next().hide();
            mElem_TableOfPolls.find('tr').first().next().fadeIn('slow');

        } else {
            $(element).after('<p class="userResponse">Poll konnte nicht gespeichert werden :o(');
            $('.userResponse').hide();
            $('.userResponse').fadeIn('fast');

        }
    }

    /**
     * validatePollName
     * @param pollName - String
     * @returns void
     */
    function validatePollName(pollName, elem) {

        if (!elem) {
            elem = mElem_Input_PollName;
        }
        var regex = /^[a-zA-Z0-9äöüÄÖÜß_\-\+\&\!\.:,\s\?]{1,100}$/;
        var valid = regex.test(pollName);

        //validate comptableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">PollName ist nicht gültig.</p>');
        }
    }//end function validatePollName    

    /**
     * validatePollQuestion
     * @param pollQuestion - String
     * @returns void
     */
    function validatePollQuestion(pollQuestion, elem) {

        if (!elem) {
            elem = mElem_Input_PollQuestion;
        }
        var regex = /^[a-zA-Z0-9äöüÄÖÜß_\-\+\&\!\.:,\s\?]{1,255}$/;
        var valid = regex.test(pollQuestion);

        //validate pollQuestion
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">PollQuestion ist nicht gültig.</p>');
        }
    }//end function validatePollQuestion    


    /**
     * validatePollQuestionId
     * @param Integer pollQuestionId
     * @returns void
     */
    function validatePollQuestionId(pollQuestionId, elem) {

        if (!elem) {
            elem = mElem_Input_PollQuestion;
        }

        //validate pollQuestionId
        if (!mObjFunc.isNumeric(pollQuestionId)) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">PollQuestion ist nicht gültig.</p>');
        }
    }//end function validatePollQuestionId   


    /**
     * validatePollId
     * @param Integer pollId
     * @returns void
     */
    function validatePollId(pollId) {

        //validate pollId
        if (!mObjFunc.isNumeric(pollId)) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">PollId ist nicht gültig.</p>');
        }
    }//end function validatePollId       

    /**
     * validatePollQuestionType
     * @param pollQuestionType - String
     * @returns void
     */
    function validatePollQuestionType(pollQuestionType, elem) {

        if (!elem) {
            elem = mElem_Select_PollQuestionType;
        }

        var tmp = pollQuestionType;

        //validate pollQuestionType
        if (!(tmp === 'checkbox' || tmp === 'radio' || tmp === 'customText')) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">pollQuestionType ist nicht gültig.</p>');
        }
    }//end function validatePollQuestionType      

    /**
     * validatePollQuestionPriority
     * @param pollQuestionPriority - String
     * @returns void
     */
    function validatePollQuestionPriority(pollQuestionPriority, elem) {

        if (!elem) {
            elem = mElem_Select_PollQuestionPriority;
        }

        //validate pollQuestionPriority
        if (pollQuestionPriority < 1 || pollQuestionPriority > 10) {
            mReadyToSubmit = false;
            $(elem).after('<p class="userResponse">PollQuestionPriority ist nicht gültig.</p>');
        }
    }//end function validatePollQuestionType       



    this.setElem_Checkbox_PollQuestionActive = function(elem) {
        if (elem) {

            mElem_Checkbox_PollQuestionActive = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Checkbox_PollQuestionActive: elem is not valid.");
        }
    };
    this.setElem_Input_PollName = function(elem) {
        if (elem) {

            mElem_Input_PollName = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_PollName: elem is not valid.");
        }
    };
    this.setElem_Input_PollId = function(elem) {
        if (elem) {

            mElem_Input_PollId = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_PollId: elem is not valid.");
        }
    };
    this.setElem_Input_PollQuestion = function(elem) {
        if (elem) {

            mElem_Input_PollQuestion = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_PollQuestion: elem is not valid.");
        }
    };
    this.setElem_Select_PollQuestionType = function(elem) {
        if (elem) {

            mElem_Select_PollQuestionType = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Select_PollQuestionType: elem is not valid.");
        }
    };
    this.setElem_Select_PollQuestionPriority = function(elem) {
        if (elem) {

            mElem_Select_PollQuestionPriority = elem;

        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Select_PollQuestionPriority: elem is not valid.");
        }
    };
    this.setElem_TableOfPolls = function(elem) {
        if (elem) {
            mElem_TableOfPolls = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfPolls: elem is not valid.");
        }
    };
    this.setElem_TableOfPollQuestions = function(elem) {
        if (elem) {
            mElem_TableOfPollQuestions = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfPollQuestions: elem is not valid.");
        }
    };
    this.setElem_Button_InsertPoll = function(elem) {
        if (elem) {
            mElem_Button_InsertPoll = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertPoll: elem is not valid.");
        }
    };
    this.setElem_Button_InsertPollQuestion = function(elem) {
        if (elem) {
            mElem_Button_InsertPollQuestion = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertPollQuestion: elem is not valid.");
        }
    };
    this.setElemClass_Button_DeletePoll = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeletePoll = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeletePoll: elemClass is not valid.");
        }
    };
    this.setElemClass_Button_DeletePollQuestion = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeletePollQuestion = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeletePollQuestion: elemClass is not valid.");
        }
    };
    this.setElemClass_hyb_editText = function(elemClass) {
        if (elemClass) {
            mElemClass_hyb_editText = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function mElemClass_hyb_editText: elemClass is not valid.");
        }
    };
    this.setElemClass_hyb_editSelectBox = function(elemClass) {
        if (elemClass) {
            mElemClass_hyb_editSelectBox = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_hyb_editSelectBox: elemClass is not valid.");
        }
    };
    this.setElemClass_hyb_editCheckboxUnchecked = function(elemClass) {
        if (elemClass) {
            mElemClass_hyb_editCheckboxUnchecked = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_hyb_editCheckboxUnchecked: elemClass is not valid.");
        }
    };
    this.setElemClass_hyb_editCheckboxChecked = function(elemClass) {
        if (elemClass) {
            mElemClass_hyb_editCheckboxChecked = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_hyb_editCheckboxChecked: elemClass is not valid.");
        }
    };
    this.setElem_hyb_checkbox_dontTrackMe = function(elem) {
        if (elem) {
            mElem_hyb_checkbox_dontTrackMe = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_hyb_checkbox_dontTrackMe: elem is not valid.");
        }
    };
};