/*
 * Javascript file that handles events on articles
 */

//object Comptable
var Comptable = function() {

    /**
     * globaly needed functions
     * @type globFunctions
     */
    var mObjFunc;

    /**
     * Current Comptable 
     * @type Object
     */
    var mObjComptable;

    /**
     * UI-Elements
     */

    //Input Elements
    var mElem_Input_ComptableName;
    var mElem_Input_TableName;
    var mElem_Input_TableUrl;
    var mElem_Input_TableImgUrl;
    var mElem_Textbox_TableNote;
    var mElem_Checkbox_TableIsFavorit;
    var mElem_Input_DatasetKey;
    var mElem_Input_DatasetValue;
    var mElem_Textbox_DatasetNote;
    var mElem_Textarea_BulkInsertDatasets;
    var mElemClass_CheckboxButtonUnchecked_DatasetPrivate;
    var mElemClass_CheckboxButtonChecked_DatasetPrivate;
    var mElemClass_CheckboxButtonUnchecked_TableIsFavorit;
    var mElemClass_CheckboxButtonChecked_TableIsFavorit;

    //Buttons
    var mElem_Button_InsertComptable;
    var mElem_Button_InsertTable;
    var mElemClass_Button_DeleteTable;
    var mElem_Button_InsertDataset;
    var mElem_Button_BulkInsertDatasets;
    var mElemClass_InputButton_DatasetValue;
    var mElemClass_TextboxButton_DatasetNote;
    var mElemClass_CheckboxButton_DatasetPrivate;
    var mElemClass_Button_DeleteDataset;
    var mElemClass_Button_DeleteComptable;

    //Ajax UI-Elements
    var mElem_AjaxError;
    var mElem_AjaxResponse;
    var mElem_TableOfDatasets;
    var mElem_TableOfTables;
    var mElem_TableOfComptables;

    //text-buttons and text-Attributes
    var mElemClass_hyb_editText;
    var mCurrElem_hyb_editText;
    var mNewText_editText;
    var mOldText_editText;


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
                
                var tableName = $(elemSpan).attr('tableName');
                var datasetKey = $(elemSpan).attr('datasetKey');

                //data to submit per ajax
                var objData = mObjComptable = {
                    admin: "true",
                    object: "comptable",
                    action: action,
                    comptableName: $(elemSpan).attr('comptableName'),
                    tableName: tableName,
                    datasetKey: datasetKey,
                    textValue: mNewText_editText
                };

            } else {
                mReadyToSubmit = false;
                console.log('Error on submitting Request. Action is not defined.');
            }

            //call ajax-request to update 
            if (mReadyToSubmit) {

                mObjFunc.ajaxRequest(
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

        if ($(objRefTmp).hasClass('hyb_comptable_ds_value')) {
            action = 'updateValueOnDataset';
        } else if ($(objRefTmp).hasClass('hyb_comptable_ds_note')) {
            action = 'updateNoteOnDataset';
        } else if ($(objRefTmp).hasClass('hyb_comptable_ds_note')) {
            action = 'updateNoteOnDataset';
        } else if ($(objRefTmp).hasClass('hyb_comptable_tbl_note')) {
            action = 'updateNoteOnTable';
        } else if ($(objRefTmp).hasClass('hyb_comptable_tbl_url')) {
            action = 'updateUrlOnTable';
        } else if ($(objRefTmp).hasClass('hyb_comptable_tbl_imgUrl')) {
            action = 'updateImgUrlOnTable';
        }

        return action;
    }

    /**
     * public function registerClickEventInsertComptable
     * @returns void
     */
    this.registerClickEventInsertComptable = function() {

        mElem_Button_InsertComptable.click(function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');
            $(document).find('.errorMsg').remove();

            //get comptableName
            var comptableName = mElem_Input_ComptableName.val();

            //check if user input is korrekt
            validateComptableName(comptableName);

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "insertComptable",
                comptableName: comptableName
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.ajaxRequest(
                        objData,
                        comptableInsertCallback,
                        mElem_AjaxResponse);
            }

        });
    };//end function registerClickEventInsertComptable

    /**
     * public function registerClickEventDeleteComptable
     * @returns void
     */
    this.registerClickEventDeleteComptable = function() {

        $(document).on('click', '.' + mElemClass_Button_DeleteComptable, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');

            //get comptableName
            var comptableName = $(this).attr('comptableName');

            //check if user input is korrekt
            validateComptableName(comptableName);

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "deleteComptable",
                comptableName: comptableName
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this Comptable?");

            //call ajax-request to delete Table
            if (mReadyToSubmit && confirmed) {
                mObjFunc.ajaxRequest(
                        objData,
                        comptableDeleteCallback,
                        this);
            }

        });
    };//end function registerClickEventDeleteComptable       

    /**
     * public function registerClickEventDeleteTable
     * @returns void
     */
    this.registerClickEventDeleteTable = function() {

        $(document).on('click', '.' + mElemClass_Button_DeleteTable, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');

            //get comptableName
            var comptableName = $(this).attr('comptableName');

            //get tableName
            var tableName = $(this).attr('tableName');

            //check if user input is korrekt
            validateComptableName(comptableName);
            validateTableName(tableName);

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "deleteTable",
                comptableName: comptableName,
                tableName: tableName
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this Table?");

            //call ajax-request to delete Table
            if (mReadyToSubmit && confirmed) {
                mObjFunc.ajaxRequest(
                        objData,
                        tableDeleteCallback,
                        this);
            }

        });
    };//end function registerClickEventDeleteTable        

    /**
     * registerClickEventEditTableIsActive
     * @returns void
     */
    this.registerClickEventEditTableIsActive = function() 
    {
        $(document).on('click', 
                '.' + mElemClass_CheckboxButtonUnchecked_TableIsActive, 
                function(e) 
        {
            handleClickEventEditTableIsActive(e, this, 1);
        });

        $(document).on('click', 
                '.' + mElemClass_CheckboxButtonChecked_TableIsActive, 
                function(e) 
        {
            handleClickEventEditTableIsActive(e, this, 0);
        });
    };
    
    
    /**
     * handleClickEventEditTableIsActive - invoked by
     * registerClickEventEditTableIsActive()
     * 
     * @param e - Event
     * @param elem - Element
     * @param state - Integer
     * @returns void
     */
    function handleClickEventEditTableIsActive(e, elem, state) {

        //precent formsubission
        e.preventDefault();

        mReadyToSubmit = true;

        //clear errormessages
        mElem_AjaxError.text('');

        //get comptableName
        var comptableName = $(elem).attr('comptableName');

        //get tableName
        var tableName = $(elem).attr('tableName');

        //check if user input is korrekt
        validateComptableName(comptableName);
        validateTableName(tableName);

        //data to submit per ajax
        var objData = mObjComptable = {
            admin: "true",
            object: "comptable",
            action: "updateIsActiveOnTable",
            comptableName: comptableName,
            tableName: tableName,
            isActive: state
        };

        //call ajax-request to delete Dataset
        if (mReadyToSubmit) 
        {
            mObjFunc.ajaxRequest(
                    objData,
                    tableUpdateCallback,
                    elem);
        }
    }    
    
    
    /**
     * registerClickEventEditTableIsFavorit
     * @returns void
     */
    this.registerClickEventEditTableIsFavorit = function() {

        $(document).on('click', 
                '.' + mElemClass_CheckboxButtonUnchecked_TableIsFavorit, 
                function(e) 
        {
            handleClickEventEditTableIsFavorit(e, this, 1);
        });

        $(document).on('click', 
                '.' + mElemClass_CheckboxButtonChecked_TableIsFavorit, 
                function(e) 
        {
            handleClickEventEditTableIsFavorit(e, this, 0);
        });
    };

    /**
     * handleClickEventEditTableIsFavorit - invoked by
     * registerClickEventEditTableIsFavorit()
     * 
     * @param e - Event
     * @param elem - Element
     * @param state - Integer
     * @returns void
     */
    function handleClickEventEditTableIsFavorit(e, elem, state) {

        //precent formsubission
        e.preventDefault();

        mReadyToSubmit = true;

        //clear errormessages
        mElem_AjaxError.text('');

        //get comptableName
        var comptableName = $(elem).attr('comptableName');

        //get tableName
        var tableName = $(elem).attr('tableName');

        //check if user input is korrekt
        validateComptableName(comptableName);
        validateTableName(tableName);

        //data to submit per ajax
        var objData = mObjComptable = {
            admin: "true",
            object: "comptable",
            action: "updateIsFavoritOnTable",
            comptableName: comptableName,
            tableName: tableName,
            isFavorit: state
        };

        //call ajax-request to delete Dataset
        if (mReadyToSubmit) {

            mObjFunc.ajaxRequest(
                    objData,
                    tableUpdateCallback,
                    elem);
        }
    }

    /**
     * public function registerClickEventInsertTable
     * @returns void
     */
    this.registerClickEventInsertTable = function() {

        mElem_Button_InsertTable.click(function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');
            $(document).find('.errorMsg').remove();

            //get form-values
            var comptableName = mElem_Input_ComptableName.val();
            var tableName = mElem_Input_TableName.val();
            var url = mElem_Input_TableUrl.val();
            var imgUrl = mElem_Input_TableImgUrl.val();
            var tableNote = mElem_Textbox_TableNote.val();
            var isActive = '';
            var isFavorit = '';
            if (mElem_Checkbox_TableIsActive.is(':checked')) 
            {
                isActive = '1';
            }
            if (mElem_Checkbox_TableIsFavorit.is(':checked')) 
            {
                isFavorit = '1';
            }

            //check if user input is korrekt
            validateComptableName(comptableName);
            validateTableName(tableName);

            if (url !== '') {
                validateTableUrl(url, mElem_Input_TableUrl);
            }
            if (imgUrl !== '') {
                validateTableImgUrl(imgUrl, mElem_Input_TableImgUrl);
            }

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "insertTable",
                comptableName: comptableName,
                tableName: tableName,
                url: url,
                imgUrl: imgUrl,
                tableNote: tableNote,
                isActive: isActive,
                isFavorit: isFavorit
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) 
            {
                mObjFunc.ajaxRequest(
                        objData,
                        tableInsertCallback,
                        mElem_AjaxResponse);
            }

        });
    };//end function registerClickEventInsertTable    


    /**
     * registerClickEventBulkInsertDatasets
     * @returns {void}
     */
    this.registerClickEventBulkInsertDatasets = function() 
    {
        mElem_Button_BulkInsertDatasets.click(function(e) 
        {    
            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');
            $(document).find('.errorMsg').remove();
            
            //get form-values
            var comptableName = mElem_Input_ComptableName.val();
            var tableName = mElem_Input_TableName.val();
            var keyVals = $(mElem_Textarea_BulkInsertDatasets).val();     
            var arrKeyVals = getLines(keyVals);
                                  
            $(arrKeyVals).each(function() {
               var arrTmp = this.toString().split(';');                           
               
               if(arrTmp.length === 2) {
                   
                    validateKey(arrTmp[0], mElem_Textarea_BulkInsertDatasets);
                    validateValue(arrTmp[1], mElem_Textarea_BulkInsertDatasets);
                    
               } else {
                   $(mElem_Textarea_BulkInsertDatasets).after(
                           '<p class="errorMsg">Datasets sind nicht gültig.</p>');
                   mReadyToSubmit = false;
               }
            });   
            
            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "bulkInsertDatasets",
                comptableName: comptableName,
                tableName: tableName,
                datasetKeyVals: keyVals,
                datasetNote: '',
                private: 0
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.ajaxRequest(
                        objData,
                        datasetBulkInsertCallback,
                        mElem_AjaxResponse);
            }            
        });
    };

    /**
     * registerClickEventInsertDataset
     * @returns void
     */
    this.registerClickEventInsertDataset = function() 
    {
        mElem_Button_InsertDataset.click(function(e) 
        {
            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');
            $(document).find('.errorMsg').remove();

            //get form-values
            var comptableName = mElem_Input_ComptableName.val();
            var tableName = mElem_Input_TableName.val();
            var key = mElem_Input_DatasetKey.val();
            var value = mElem_Input_DatasetValue.val();
            var datasetNote = mElem_Textbox_DatasetNote.val();
            var private = '';
            if (mElem_Checkbox_DatasetPrivate.is(':checked')) {
                private = '1';
            }

            //check if user input is korrekt
            validateComptableName(comptableName);
            validateTableName(tableName);
            validateKey(key, mElem_Input_DatasetKey);
            validateValue(value, mElem_Input_DatasetValue);

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "insertDataset",
                comptableName: comptableName,
                tableName: tableName,
                datasetKey: key,
                datasetValue: value,
                datasetNote: datasetNote,
                private: private
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {

                mObjFunc.ajaxRequest(
                        objData,
                        datasetInsertCallback,
                        mElem_AjaxResponse);
            }

        });
    };//end function registerClickEventInsertDataset


    /**
     * registerClickEventDeleteDataset
     * @returns void
     */
    this.registerClickEventDeleteDataset = function() {

        $(document).on('click', '.' + mElemClass_Button_DeleteDataset, function(e) {

            //precent formsubission
            e.preventDefault();

            mReadyToSubmit = true;

            //clear errormessages
            mElem_AjaxError.text('');

            //get comptableName
            var comptableName = $(this).attr('comptableName');

            //get tableName
            var tableName = $(this).attr('tableName');

            //get key
            var key = $(this).attr('datasetKey');

            //check if user input is korrekt
            validateComptableName(comptableName);
            validateTableName(tableName);
            validateKey(key);

            //data to submit per ajax
            var objData = mObjComptable = {
                admin: "true",
                object: "comptable",
                action: "deleteDataset",
                comptableName: comptableName,
                tableName: tableName,
                datasetKey: key
            };

            //ask user to confirm
            var confirmed = confirm("Do you really want to delete this Dataset?");

            //call ajax-request to delete Dataset
            if (mReadyToSubmit && confirmed) {

                mObjFunc.ajaxRequest(
                        objData,
                        datasetDeleteCallback,
                        this);
            }

        });
    };//end function registerClickEventDeleteDataset


    /**
     * registerClickEventEditDatasetPrivate
     * @returns void
     */
    this.registerClickEventEditDatasetPrivate = function() {

        $(document).on('click', '.' + mElemClass_CheckboxButtonUnchecked_DatasetPrivate, function(e) {
            handleClickEventEditDatasetPrivate(e, this, 1);
        });

        $(document).on('click', '.' + mElemClass_CheckboxButtonChecked_DatasetPrivate, function(e) {
            handleClickEventEditDatasetPrivate(e, this, 0);
        });
    };

    /**
     * handleClickEventEditDatasetPrivate - invoked by
     * registerClickEventEditDatasetPrivate()
     * 
     * @param e - Event
     * @param elem - Element
     * @param state - Integer
     * @returns void
     */
    function handleClickEventEditDatasetPrivate(e, elem, state) {

        //precent formsubission
        e.preventDefault();

        mReadyToSubmit = true;

        //clear errormessages
        mElem_AjaxError.text('');

        //get comptableName
        var comptableName = $(elem).attr('comptableName');

        //get tableName
        var tableName = $(elem).attr('tableName');

        //get key
        var key = $(elem).attr('datasetKey');

        //check if user input is korrekt
        validateComptableName(comptableName);
        validateTableName(tableName);
        validateKey(key);

        //data to submit per ajax
        var objData = mObjComptable = {
            admin: "true",
            object: "comptable",
            action: "updatePrivateOnDataset",
            comptableName: comptableName,
            tableName: tableName,
            datasetKey: key,
            private: state
        };

        //call ajax-request to delete Dataset
        if (mReadyToSubmit) {

            mObjFunc.ajaxRequest(
                    objData,
                    datasetUpdateCallback,
                    elem);
        }
    }


    /**
     * callback-function to handle response of
     * the ajax-request to update a textfield
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function editTextUpdateCallback(response, element) {

        //check if request was successful
        if (response.substring(0, 4) === 'true') {
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
            console.log(response);
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to delete a table
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function tableDeleteCallback(response, element) {

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //remove table from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log(response);
        }

    }//end function tableDeleteCallback

    /**
     * callback-function to handle response of
     * the ajax-request to delete a comptable
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function comptableDeleteCallback(response, element) {

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //remove comptable from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log(response);
        }

    }//end function comptableDeleteCallback    

    /**
     * callback-function to handle response of
     * the ajax-request to add a comptable
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function comptableInsertCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //add comptable to table
            var op = '<tr>';
            op += '<td>';
            op += '<a href="/admin/plugins/index.php?name=comptable&action=editComptable&comptable='
                    + (mObjComptable.comptableName)
                    + '" title="Edit ' + (mObjComptable.comptableName) + '">';
            op += (mObjComptable.comptableName);
            op += '</a>';
            op += '</td>';

            //elem to delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_comptable" '
                    + 'comptableName="' + (mObjComptable.comptableName)
                    + '"></i>';
            op += '</td>';

            op += '</tr>';

            mElem_TableOfComptables.find('tr').first().after(op);
            mElem_TableOfComptables.find('tr').first().next().hide();
            mElem_TableOfComptables.find('tr').first().next().fadeIn('slow');

        } else {
            $(element).text('Table konnte nicht gespeichert werden :o(');
            $(element).fadeIn('fast');
        }
    }


    /**
     * callback-function to handle response of
     * the ajax-request to add a table
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function tableInsertCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //add table to table
            var op = '<tr>';
            
            op += '<td>';
            op += '<a href="/admin/plugins/index.php?name=comptable&action=editTable&comptable='
                    + mObjComptable.comptableName + "&table=" + mObjComptable.tableName
                    + '" title="Edit ' + mObjComptable.tableName + '">';
            op += mObjComptable.tableName;
            op += '</a>';
            op += '</td>';

            //url
            op += '<td class="hyb_comptable_tbl_url hyb_editText">';
            op += '<span class="hyb_edit"'
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName
                    + '">';
            op += mObjComptable.url;
            op += '</span>';
            op += '</td>';

            //image-url
            op += '<td class="hyb_comptable_tbl_imgUrl hyb_editText">';
            op += '<span class="hyb_edit"'
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName
                    + '">';
            op += mObjComptable.imgUrl;
            op += '</span>';
            op += '</td>';
            
            //IsActive
            op += '<td class="hyb_comptable_tbl_isActive">';
            if (mObjComptable.isActive === 1) 
            {
                op += '<i class="hyb_edit fa fa-check-square" '
                        + 'tableName="'.mObjComptable.tableName + '" '
                        + 'comptableName="'.mObjComptable.comptableName
                        + '">'
                        + '</i>';
            } 
            else 
            {
                op += '<i class="hyb_edit fa fa-square-o" '
                        + 'tableName="' + mObjComptable.tableName + '" '
                        + 'comptableName="' + mObjComptable.comptableName
                        + '">'
                        + '</i>';
            }
            op += '</td>';            

            //IsFavorit
            op += '<td class="hyb_comptable_tbl_isFavorit">';
            if (mObjComptable.isFavorit === 1) 
            {
                op += '<i class="hyb_edit fa fa-check-square" '
                        + 'tableName="'.mObjComptable.tableName + '" '
                        + 'comptableName="'.mObjComptable.comptableName
                        + '">'
                        + '</i>';
            } 
            else 
            {
                op += '<i class="hyb_edit fa fa-square-o" '
                        + 'tableName="' + mObjComptable.tableName + '" '
                        + 'comptableName="' + mObjComptable.comptableName
                        + '">'
                        + '</i>';
            }
            op += '</td>';

            //note
            op += '<td class="hyb_comptable_tbl_note hyb_editText">';
            op += '<span class="hyb_edit"'
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName
                    + '">';
            op += mObjComptable.tableNote;
            op += '</span>';
            op += '</td>';

            //created
            op += '<td class="hyb_comptable_ds_created">';
            op += '<span>';
            op += 'now';
            op += '</span>';
            op += '</td>';

            //elem to delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_table" '
                    + 'tableName="' + (mObjComptable.tableName) + '" '
                    + 'comptableName="' + (mObjComptable.comptableName)
                    + '"></i>';
            op += '</td>';

            op += '</tr>';
            
            mElem_TableOfTables.find('tr').first().after(op);
            mElem_TableOfTables.find('tr').first().next().hide();
            mElem_TableOfTables.find('tr').first().next().fadeIn('slow');

        } else {
            $(element).text('Table konnte nicht gespeichert werden :o(');
            $(element).fadeIn('fast');
        }
    }
    
    /**
     * datasetBulkInsertCallback
     * @param {String} response
     * @param {Element} element
     * @returns {void}
     */
    function datasetBulkInsertCallback(response, element) {
        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {
            $(element).text('Datasets wurden gespeichert :o)');
            $(element).fadeIn('fast');
        } else {
            $(element).text('Datasets wurden nicht gespeichert :o(');
            $(element).fadeIn('fast');            
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to add a dataset
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function datasetInsertCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //add dataset to table
            var op = '';

            //begin new Dataset
            op += '<tr>';

            //Key
            op += '<td class="hyb_comptable_ds_key">';
            op += mObjComptable.datasetKey;
            op += '</td>';

            //value
            op += '<td class="hyb_comptable_ds_value hyb_editText">';
            op += '<span class="hyb_edit hyb_edit_input"'
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName + '" '
                    + 'datasetKey="' + mObjComptable.datasetKey
                    + '">';
            op += mObjComptable.datasetValue;
            op += '</span>';
            op += '</td>';

            //Private
            op += '<td class="hyb_comptable_ds_isPrivate">';
            if (mObjComptable.private === '1') {
                op += '<i class="hyb_edit hyb_edit_checkbox fa fa-check-square"'
                        + 'tableName="' + mObjComptable.tableName + '" '
                        + 'comptableName="' + mObjComptable.comptableName + '" '
                        + 'datasetKey="' + mObjComptable.datasetKey
                        + '">'
                        + '</i>';
            } else {
                op += '<i class="hyb_edit hyb_edit_checkbox fa fa-square-o"'
                        + 'tableName="' + mObjComptable.tableName + '" '
                        + 'comptableName="' + mObjComptable.comptableName + '" '
                        + 'datasetKey="' + mObjComptable.datasetKey
                        + '">'
                        + '</i>';
            }
            op += '</td>';

            //note
            op += '<td class="hyb_comptable_ds_note hyb_editText">';
            op += '<span class="hyb_edit hyb_edit_textbox"'
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName + '" '
                    + 'datasetKey="' + mObjComptable.datasetKey
                    + '">';
            op += mObjComptable.datasetNote;
            op += '</span>';
            op += '</td>';

            //created
            op += '<td class="hyb_comptable_ds_created">';
            op += '<span>';
            op += 'now';
            op += '</span>';
            op += '</td>';

            //lastChanged
            op += '<td class="hyb_comptable_ds_lastChanged">';
            op += '<span>';
            op += '-';
            op += '</span>';
            op += '</td>';

            //delete table from db
            op += '<td>';
            op += '<i class="fa fa-trash-o btn_delete_dataset" '
                    + 'tableName="' + mObjComptable.tableName + '" '
                    + 'comptableName="' + mObjComptable.comptableName + '" '
                    + 'datasetKey="' + mObjComptable.datasetKey
                    + '"></i>';
            op += '</td>';

            op += '</tr>';

            mElem_TableOfDatasets.find('tr').first().after(op);
            mElem_TableOfDatasets.find('tr').first().next().hide();
            mElem_TableOfDatasets.find('tr').first().next().fadeIn('slow');

        } else {
            $(element).text('Dataset konnte nicht gespeichert werden :o(');
            $(element).fadeIn('fast');
        }
    }

    /**
     * callback-function to handle response of
     * the ajax-request to delete a table
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function datasetDeleteCallback(response, element) {

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //remove dataset from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log(response);
        }

    }//end function datasetDeleteCallback    

    /**
     * callback-function to handle response of
     * the ajax-request to delete a table
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function datasetDeleteCallback(response, element) {

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            //remove dataset from DOM
            $(element).parent().parent('tr').fadeOut('slow', function() {
                $(element).parent().parent('tr').remove();
            });

        } else {
            console.log(response);
        }

    }//end function datasetUpdateCallback      

    /**
     * callback-function to handle response of
     * the ajax-request to update a dataset
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function datasetUpdateCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            var arrTmp = mElemClass_CheckboxButtonChecked_DatasetPrivate.split('.');
            var classNameChecked = arrTmp[arrTmp.length - 1];

            var arrTmp = mElemClass_CheckboxButtonUnchecked_DatasetPrivate.split('.');
            var classNameUnchecked = arrTmp[arrTmp.length - 1];

            $(element).toggleClass(classNameChecked);
            $(element).toggleClass(classNameUnchecked);

        } else {
            console.log(response);
            console.log('Dataset konnte nicht geändert werden :o(');
        }

        //show message
        $(element).fadeIn('fast');
    }//end function datasetUpdateCallback

    /**
     * callback-function to handle response of
     * the ajax-request to update a table
     * 
     * @param response - String
     * @param element - Element
     * @returns void
     */
    function tableUpdateCallback(response, element) {

        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {

            var arrTmp = mElemClass_CheckboxButtonChecked_TableIsFavorit.split('.');
            var classNameChecked = arrTmp[arrTmp.length - 1];

            var arrTmp = mElemClass_CheckboxButtonUnchecked_TableIsFavorit.split('.');
            var classNameUnchecked = arrTmp[arrTmp.length - 1];

            $(element).toggleClass(classNameChecked);
            $(element).toggleClass(classNameUnchecked);

        } else {
            console.log(response);
            console.log('Table konnte nicht geändert werden :o(');
        }

        //show message
        $(element).fadeIn('fast');
    }//end function tableUpdateCallback

    /**
     * validateComptableName
     * @param comptableName - String
     * @returns void
     */
    function validateComptableName(comptableName, elem) {

        if (!elem) {
            elem = mElem_Input_ComptableName;
        }
        var regex = /^[a-zA-Z0-9_\-]{1,45}$/;
        var valid = regex.test(comptableName);

        //validate comptableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">ComptableName ist nicht gültig.</p>');
        }
    }//end function validateComptableName

    /**
     * validateTableName
     * @param tableName - String
     * @returns void
     */
    function validateTableName(tableName, elem) {
        var regex = /^[a-zA-Z0-9_\-\.\+\s\-]{1,45}$/;
        var valid = regex.test(tableName);

        //validate tableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">TableName ist nicht gültig.</p>');
        }
    }//end function validateComptableName    

    /**
     * validateTableUrl
     * @param tableUrl - String
     * @returns void
     */
    function validateTableUrl(tableUrl, elem) {

        //validate tableName
        if (!mObjFunc.validateUrl(tableUrl)) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Url ist nicht gültig.</p>');
        }
    }//end function validateTableUrl      

    /**
     * validateTableImgUrl
     * @param tableImgUrl - String
     * @returns void
     */
    function validateTableImgUrl(tableImgUrl, elem) {
        var regex = /^[a-zA-Z0-9_\-\/]{1,255}(\.jpg|\.jpeg|\.png|\.gif)$/;
        var valid = regex.test(tableImgUrl);

        //validate tableName
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Image Url ist nicht gültig.</p>');
        }
    }//end function validateTableImgUrl 

    /**
     * validateKey
     * @param String key
     * @returns void
     */
    function validateKey(key, elem) {
        var regex = /^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]{1,45}$/;
        var valid = regex.test(key);

        //validate key
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Key ist nicht gültig.</p>');
        }
    }

    /**
     * validateValue
     * @param String value
     * @returns void
     */
    function validateValue(value, elem) {
        var regex = /^[a-zA-Z0-9öäüÖÄÜß\.,\-\:_\+\s\/\(\)]{1,255}$/;
        var valid = regex.test(value);

        //validate value
        if (!valid) {
            mReadyToSubmit = false;
            $(elem).after('<p class="errorMsg">Value '+ value +' ist nicht gültig.</p>');
        }
    }
    
    /**
     * getLines returns an array of lines
     * @param {String} text
     * @returns {String[]}
     */
    function getLines(text) { 
        return text.trim().split(/\r*\n/); 
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
    this.setElem_Button_InsertComptable = function(elem) {
        if (elem) {
            mElem_Button_InsertComptable = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertComptable: elem is not valid.");
        }
    };
    this.setElem_Button_InsertTable = function(elem) {
        if (elem) {
            mElem_Button_InsertTable = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertTable: elem is not valid.");
        }
    };
    this.setElemClass_Button_DeleteTable = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeleteTable = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeleteTable: elemClass is not valid.");
        }
    };
    this.setElem_Input_TableName = function(elem) {
        if (elem) {
            mElem_Input_TableName = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_TableName: elem is not valid.");
        }
    };
    this.setElem_Input_ComptableName = function(elem) {
        if (elem) {
            mElem_Input_ComptableName = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_ComptableName: elem is not valid.");
        }
    };

    this.setElem_Input_TableUrl = function(elem) {
        if (elem) {
            mElem_Input_TableUrl = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_TableUrl: elem is not valid.");
        }
    };

    this.setElem_Input_TableImgUrl = function(elem) {
        if (elem) {
            mElem_Input_TableImgUrl = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_TableImgUrl: elem is not valid.");
        }
    };

    this.setElem_Textbox_TableNote = function(elem) {
        if (elem) {
            mElem_Textbox_TableNote = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Textbox_TableNote: elem is not valid.");
        }
    };

    this.setElem_Checkbox_TableIsFavorit = function(elem) {
        if (elem) {
            mElem_Checkbox_TableIsFavorit = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Checkbox_TableIsFavorit: elem is not valid.");
        }
    };
    
    this.setElem_Checkbox_TableIsActive = function(elem) {
        if (elem) {
            mElem_Checkbox_TableIsActive = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Checkbox_TableIsActive: elem is not valid.");
        }
    };    

    this.setElem_TableOfTables = function(elem) {
        if (elem) {
            mElem_TableOfTables = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfTables: elem is not valid.");
        }
    };

    this.setElem_TableOfComptables = function(elem) {
        if (elem) {
            mElem_TableOfComptables = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfComptables: elem is not valid.");
        }
    };
    this.setElem_Input_DatasetKey = function(elem) {
        if (elem) {
            mElem_Input_DatasetKey = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_DatasetKey: elem is not valid.");
        }
    };
    this.setElem_Input_DatasetValue = function(elem) {
        if (elem) {
            mElem_Input_DatasetValue = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Input_DatasetValue: elem is not valid.");
        }
    };
    this.setElem_Checkbox_DatasetPrivate = function(elem) {
        if (elem) {
            mElem_Checkbox_DatasetPrivate = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Checkbox_DatasetPrivate: elem is not valid.");
        }
    };

    this.setElem_Button_InsertDataset = function(elem) {
        if (elem) {
            mElem_Button_InsertDataset = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_InsertDataset: elem is not valid.");
        }
    };
    this.setElem_Button_BulkInsertDatasets = function(elem) {
        if (elem) {
            mElem_Button_BulkInsertDatasets = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Button_BulkInsertDatasets: elem is not valid.");
        }
    };    
    this.setElem_Textarea_BulkInsertDatasets = function(elem) {
        if (elem) {
            mElem_Textarea_BulkInsertDatasets = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Textarea_BulkInsertDatasets: elem is not valid.");
        }
    };       
    this.setElemClass_InputButton_DatasetValue = function(elemClass) {
        if (elemClass) {
            ElemClass_InputButton_DatasetValue = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_InputButton_DatasetValue: elemClass is not valid.");
        }
    };
    this.setElemClass_TextboxButton_DatasetNote = function(elemClass) {
        if (elemClass) {
            mElemClass_TextboxButton_DatasetNote = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_TextboxButton_DatasetNote: elemClass is not valid.");
        }
    };
    this.setElemClass_CheckboxButtonUnchecked_DatasetPrivate = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonUnchecked_DatasetPrivate = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonUnchecked_DatasetPrivate: elemClass is not valid.");
        }
    };
    this.setElemClass_CheckboxButtonChecked_DatasetPrivate = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonChecked_DatasetPrivate = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonChecked_DatasetPrivate: elemClass is not valid.");
        }
    };
    this.setElemClass_CheckboxButtonUnchecked_TableIsActive = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonUnchecked_TableIsActive = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonUnchecked_TableIsActive: elemClass is not valid.");
        }
    };    
    this.setElemClass_CheckboxButtonChecked_TableIsActive = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonChecked_TableIsActive = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonChecked_TableIsActive: elemClass is not valid.");
        }
    };
    this.setElemClass_CheckboxButtonUnchecked_TableIsFavorit = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonUnchecked_TableIsFavorit = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonUnchecked_TableIsFavorit: elemClass is not valid.");
        }
    };
    this.setElemClass_CheckboxButtonChecked_TableIsFavorit = function(elemClass) {
        if (elemClass) {
            mElemClass_CheckboxButtonChecked_TableIsFavorit = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_CheckboxButtonChecked_TableIsFavorit: elemClass is not valid.");
        }
    };
    this.setElemClass_Button_DeleteDataset = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeleteDataset = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeleteDataset: elemClass is not valid.");
        }
    };
    this.setElem_TableOfDatasets = function(elem) {
        if (elem) {
            mElem_TableOfDatasets = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfDatasets: elem is not valid.");
        }
    };
    this.setElem_Textbox_DatasetNote = function(elem) {
        if (elem) {
            mElem_Textbox_DatasetNote = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_Textbox_DatasetNote: elem is not valid.");
        }
    };
    this.setElem_TableOfComptables = function(elem) {
        if (elem) {
            mElem_TableOfComptables = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableOfComptables: elem is not valid.");
        }
    };
    this.setElemClass_Button_DeleteComptable = function(elemClass) {
        if (elemClass) {
            mElemClass_Button_DeleteComptable = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_Button_DeleteComptable: elemClass is not valid.");
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
}; //end class Compatable





