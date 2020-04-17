/**
 * register Mouse-Events
 */
$(document).ready(function() {

    var objPoll = new Poll();
    objPoll.init();
    
    //set Buttons
    objPoll.setElemClass_Button_RadioNotSelected('fa-circle-o');
    objPoll.setElemClass_Button_RadioSelected('fa-dot-circle-o');
    objPoll.setElemClass_Button_CheckboxUnchecked('fa-square-o');
    objPoll.setElemClass_Button_CheckboxChecked('fa-check-square');
    objPoll.setElemClass_Textarea_CustomText('textAreaCustomText');
    objPoll.setElem_Button_SavePollAnswers($('#hyb_savePollAnswer'));
    objPoll.setElemClass_Button_ClosePoll('closePoll');
    objPoll.setElemClass_Button_OpenPoll('openPoll');

    //register ClickEvents
    objPoll.registerClickEventRadioSelect();
    objPoll.registerClickEventCheckboxCheck();
    objPoll.registerClickEventCheckboxUncheck();
    objPoll.registerBlurEventTextareaCustomText();
    objPoll.registerClickEventSavePollAnswers();
    objPoll.registerClickEventClosePoll();
    objPoll.registerClickEventOpenPoll();
    
    //set pollContaier
    objPoll.setElemPollContainer($('#pollContainer'));
    
    //set Position
    objPoll.setMarginBottom(20);
    objPoll.setMarginRight(30);
    
    //set thank you Msg
    objPoll.setThankYouMsg('Vielen Dank! <i class="fa fa-smile-o"></i>');
    
    //initial open poll
    objPoll.initialOpenPoll();


}); //end document.ready