var User = function() {

    /**
     * indicates if ajax request is ready to submit
     * @type Boolean
     */
    var mReadyToSubmit = true;

    var mElem_InsertUser;
    var mElem_InputUsername;
    var mElem_SelectRolename;
    var mElem_InputPassword;
    var mElem_InputEmail;
    var mElem_InputTwitter;
    var mElem_InputFacebook;
    var mElem_InputGoogleplus;
    var mElem_InputYoutube;
    var mElem_InputWebsite;
    var mElem_TextareaAboutme;

    var mUsername;
    var mRolename;
    var mPassword;
    var mEmail;
    var mTwitter;
    var mFacebook;
    var mGooglePlus;
    var mYoutube;
    var mWebsite;
    var mAboutme;

    var mObjFunc;


    /**
     * public function to init Comptable
     * @returns void
     */
    this.init = function init() {

        //initiate functions-Object
        mObjFunc = new globFunctions();
    };

    /**
     * register click event on insert a user
     */
    this.registerClickEventInsertUser = function() {

        mElem_InsertUser.click(function(e) {

            //precent formsubission
            e.preventDefault();

            //clear errormessages
            $('.errorMsg').remove();
            $('#usrResponse').remove();

            //get attributes of the user
            mUsername = validateUsername($(mElem_InputUsername).val());
            mRolename = validateRolename($(mElem_SelectRolename).val());
            mPassword = validatePassword($(mElem_InputPassword).val());
            mEmail = validateEmail($(mElem_InputEmail).val());

            if ($(mElem_InputTwitter).val()) {
                mTwitter = validateTwitter($(mElem_InputTwitter).val());
            }
            if ($(mElem_InputFacebook).val()) {
                mFacebook = validateFacebook($(mElem_InputFacebook).val());
            }
            if ($(mElem_InputGoogleplus).val()) {
                mGooglePlus = validateGoogleplus($(mElem_InputGoogleplus).val());
            }
            if ($(mElem_InputYoutube).val()) {
                mYoutube = validateYoutube($(mElem_InputYoutube).val());
            }
            if ($(mElem_InputWebsite).val()) {
                mWebsite = validateWebsite($(mElem_InputWebsite).val());
            }
            if ($(mElem_TextareaAboutme).val()) {
                mAboutme = validateAboutme($(mElem_TextareaAboutme).val());
            }

            //get element for user messages
            $(this).before('<p id="usrResponse"></p>');
            var elemUserResponse = $('#usrResponse');

            //data to submit per ajax
            var objData = {
                admin: "true",
                object: "user",
                action: "insert",
                username: mUsername,
                rolename: mRolename,
                password: mPassword,
                email: mEmail,
                twitter: mTwitter,
                facebook: mFacebook,
                googleplus: mGooglePlus,
                youtube: mYoutube,
                website: mWebsite,
                aboutme: mAboutme
            };

            //call ajax-request to insert article
            if (mReadyToSubmit) {
                mObjFunc.ajaxRequest(objData, userInsertCallback, elemUserResponse);
            }

        });
    };
    
    /**
     * userInsertCallback
     * @param {string} response
     * @param {element} element
     * @returns {void}
     */
    function userInsertCallback(response, element) {
        //hide previous mnessages
        $(element).hide();

        //trim response
        response = $.trim(response);

        //check if request was successful
        if (response.substring(0, 4) === 'true') {
        
            $(element).text('User wurde erfolgreich gespeichert :)');

        } else {
            console.log(response);
            console.log('User konnte nicht gespeichert werden :o(');
            $(element).text('User konnte nicht gespeichert werden :(');
        }

        //show message
        $(element).fadeIn('fast');
    }

    function validateUsername(value) {

        var regex = /^[a-zA-Z0-9_\-\.\+\s\-]{1,45}$/;
        var valid = regex.test(value);

        if (!valid) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputUsername).after('<p class="errorMsg">Username ist nicht gültig.</p>');
        }
        return value;
    }
    function validateRolename(value) {

        var regex = /^[a-zA-Z]{1,45}$/;
        var valid = regex.test(value);

        if (!valid) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_SelectRolename).after('<p class="errorMsg">Rolename ist nicht gültig.</p>');
        }
        return value;
    }
    function validatePassword(value) {
        if (!value) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputPassword).after('<p class="errorMsg">Passwort ist nicht gültig.</p>');
        }
        return value;
    }
    function validateEmail(value) {
        if (!mObjFunc.validateEmail(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputPassword).after('<p class="errorMsg">Email ist nicht gültig.</p>');
        }
        return value;
    }    
    function validateTwitter(value) {
        if (!mObjFunc.validateUrl(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputTwitter).after('<p class="errorMsg">Twitter URL ist nicht gültig.</p>');
        }
        return value;
    }
    function validateFacebook(value) {
        if (!mObjFunc.validateUrl(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputFacebook).after('<p class="errorMsg">Facebook URL ist nicht gültig.</p>');
        }
        return value;
    }
    function validateGoogleplus(value) {
        if (!mObjFunc.validateUrl(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputGoogleplus).after('<p class="errorMsg">Googleplus URL ist nicht gültig.</p>');
        }
        return value;
    }
    function validateYoutube(value) {
        if (!mObjFunc.validateUrl(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputYoutube).after('<p class="errorMsg">Youtube URL ist nicht gültig.</p>');
        }
        return value;
    }
    function validateWebsite(value) {
        if (!mObjFunc.validateUrl(value)) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_InputWebsite).after('<p class="errorMsg">Website ist nicht gültig.</p>');
        }
        return value;
    }
    function validateAboutme(value) {
        if (value.length < 1 || value.length > 500) {
            mReadyToSubmit = false;
            value = null;
            $(mElem_TextareaAboutme).after('<p class="errorMsg">Aboutme ist nicht gültig.</p>');
        }
        return value;
    }

    this.setElem_InsertUser = function(elem) {
        if (elem) {
            mElem_InsertUser = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InsertUser: elem is not valid.");
        }
    };
    this.setElem_InputUsername = function(elem) {
        if (elem) {
            mElem_InputUsername = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputUsername: elem is not valid.");
        }
    };
    this.setElem_SelectRolename = function(elem) {
        if (elem) {
            mElem_SelectRolename = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_SelectRolename: elem is not valid.");
        }
    };
    this.setElem_InputPassword = function(elem) {
        if (elem) {
            mElem_InputPassword = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputPassword: elem is not valid.");
        }
    };
    this.setElem_InputEmail = function(elem) {
        if (elem) {
            mElem_InputEmail = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputEmail: elem is not valid.");
        }
    };    
    this.setElem_InputTwitter = function(elem) {
        if (elem) {
            mElem_InputTwitter = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputTwitter: elem is not valid.");
        }
    };
    this.setElem_InputFacebook = function(elem) {
        if (elem) {
            mElem_InputFacebook = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputFacebook: elem is not valid.");
        }
    };
    this.setElem_InputGoogleplus = function(elem) {
        if (elem) {
            mElem_InputGoogleplus = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputGoogleplus: elem is not valid.");
        }
    };
    this.setElem_InputYoutube = function(elem) {
        if (elem) {
            mElem_InputYoutube = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputYoutube: elem is not valid.");
        }
    };
    this.setElem_InputWebsite = function(elem) {
        if (elem) {
            mElem_InputWebsite = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_InputWebsite: elem is not valid.");
        }
    };
    this.setElem_TextareaAboutme = function(elem) {
        if (elem) {
            mElem_TextareaAboutme = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TextareaAboutme: elem is not valid.");
        }
    };

};

