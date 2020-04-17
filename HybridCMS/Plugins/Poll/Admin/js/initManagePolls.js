/**
 * register Mouse-Events to add a new Poll to DB
 */
$(document).ready(function() {

    var objPoll = new Poll();
    objPoll.init();
    
    //set inputField for pollName
    objPoll.setElem_Input_PollName($("[name='pollName']"));
    objPoll.setElem_Button_InsertPoll($("[name='insertPoll']"));
    objPoll.setElemClass_Button_DeletePoll('btn_delete_poll'); 
    
    //set tables to include added data per Ajax
    objPoll.setElem_TableOfPolls($('#hyb_tableOfPolls'));    
    
    //text-buttons to edit a text
    objPoll.setElemClass_hyb_editText('hyb_editText'); 
    
    //Admin setup
    objPoll.setElem_hyb_checkbox_dontTrackMe($('#hyb_poll_dontTrackMe i'));    
    
    //register ClickEvents
    objPoll.registerClickEventInsertPoll();
    objPoll.registerClickEventDeletePoll();    
    objPoll.registerClickEventEditText();
    objPoll.registerClickEventDontTrackMe('fa-circle-o', 'fa-check-circle-o');  
    
   
}); //end document.ready