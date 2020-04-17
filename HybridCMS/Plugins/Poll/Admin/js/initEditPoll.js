/**
 * register Mouse-Events to add a new PollQuestion to DB
 */
$(document).ready(function() {

    var objPoll = new Poll();
    objPoll.init();
    
    //set inputField for pollName
    objPoll.setElem_Input_PollId($("[name='pollId']"));
    objPoll.setElem_Input_PollName($("[name='pollName']"));    
    objPoll.setElem_Input_PollQuestion($("[name='pollQuestion']"));
    
    //Button
    objPoll.setElem_Button_InsertPollQuestion($("[name='insertPollQuestion']"));  
    
    //checkbox
    objPoll.setElem_Checkbox_PollQuestionActive($("[name='questionActive']"));
    
    //selects
    objPoll.setElem_Select_PollQuestionType($("[name='questionType']")); 
    objPoll.setElem_Select_PollQuestionPriority($("[name='questionPriority']")); 
    
    //classnames
    objPoll.setElemClass_Button_DeletePollQuestion('btn_delete_pollQuestion'); 
    
    //set tables to include added data per Ajax
    objPoll.setElem_TableOfPollQuestions($('#hyb_tableOfPollQuestions'));    
    
    //text-buttons to edit a text
    objPoll.setElemClass_hyb_editText('hyb_editText');   
    objPoll.setElemClass_hyb_editSelectBox('hyb_editSelectBox');
    objPoll.setElemClass_hyb_editCheckboxUnchecked('fa-square-o');
    objPoll.setElemClass_hyb_editCheckboxChecked('fa-check-square');

    //admin setup
    objPoll.setElem_hyb_checkbox_dontTrackMe($('#hyb_poll_dontTrackMe i'));    
    
    //register ClickEvents
    objPoll.registerClickEventInsertPollQuestion();
    objPoll.registerClickEventDeletePollQuestion();
    objPoll.registerClickEventEditText();
    objPoll.registerClickEventEditSelectBox();
    objPoll.registerClickEventEditCheckbox();
    objPoll.registerClickEventDontTrackMe('fa-circle-o', 'fa-check-circle-o');    

}); //end document.ready