/**
 * register Mouse-Events to add a new Comptable to DB
 */
$(document).ready(function() {

    var objComptable = new Comptable();
    objComptable.init();
    
    //set inputField for comptableName
    objComptable.setElem_Input_ComptableName($("[name='comptableName']"));
    objComptable.setElem_Button_InsertComptable($("[name='insert']"));
    objComptable.setElem_AjaxError($('.errorMsg'));
    objComptable.setElem_AjaxResponse($('.userResponse'));
    
    //set tables to include added data per Ajax
    objComptable.setElem_TableOfComptables($('#hyb_tableOfComptables'));    
    
    //set elements to edit a Dataset
    objComptable.setElemClass_Button_DeleteComptable('btn_delete_comptable');    

    //register ClickEvents
    objComptable.registerClickEventInsertComptable();
    objComptable.registerClickEventDeleteComptable();
    

}); //end document.ready