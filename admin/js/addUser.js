/**
 * register Mouse-Events
 */
$(document).ready(function() {

    var objUser = new User();
    objUser.init();

    objUser.setElem_InsertUser($("[name='insertUser']"));
    objUser.setElem_InputUsername($("[name='username']"));
    objUser.setElem_SelectRolename($("[name='rolename']"));
    objUser.setElem_InputPassword($("[name='password']"));
    objUser.setElem_InputEmail($("[name='email']"));
    objUser.setElem_InputTwitter($("[name='twitter']"));
    objUser.setElem_InputFacebook($("[name='facebook']"));
    objUser.setElem_InputGoogleplus($("[name='googleplus']"));
    objUser.setElem_InputYoutube($("[name='youtube']"));
    objUser.setElem_InputWebsite($("[name='website']"));
    objUser.setElem_TextareaAboutme($("[name='aboutme']"));

    objUser.registerClickEventInsertUser();

});

