/**
 * register Mouse-Events to add a new Comptable to DB
 */
$(document).ready(function() {

    var objComptable = new Comptable();
    objComptable.init();
    
    //set inputFields
    objComptable.setElem_Input_TableName($("[name='tableName']"));
    objComptable.setElem_Input_ComptableName($("[name='comptableName']"));
    objComptable.setElem_Input_TableUrl($("[name='tableUrl']"));
    objComptable.setElem_Input_TableImgUrl($("[name='tableImgUrl']"));
    objComptable.setElem_Textbox_TableNote($("[name='tableNote']"));
    objComptable.setElem_Checkbox_TableIsActive($("[name='isActive']"));    
    objComptable.setElem_Checkbox_TableIsFavorit($("[name='isFavorit']"));    
    objComptable.setElem_Button_InsertTable($('#btn_insertTable'));
        
    objComptable.setElemClass_Button_DeleteTable('btn_delete_table');
    objComptable.setElemClass_CheckboxButtonUnchecked_TableIsActive('hyb_comptable_tbl_isActive .fa-square-o');
    objComptable.setElemClass_CheckboxButtonChecked_TableIsActive('hyb_comptable_tbl_isActive .fa-check-square');
    objComptable.setElemClass_CheckboxButtonUnchecked_TableIsFavorit('hyb_comptable_tbl_isFavorit .fa-square-o');
    objComptable.setElemClass_CheckboxButtonChecked_TableIsFavorit('hyb_comptable_tbl_isFavorit .fa-check-square');
    objComptable.setElem_AjaxError($('.errorMsg'));
    objComptable.setElem_AjaxResponse($('.userResponse'));
    
    //text-buttons to edit a text
    objComptable.setElemClass_hyb_editText('hyb_editText');    
    
    //set tables to include added data per Ajax
    objComptable.setElem_TableOfTables($('#hyb_tableOfTables'));
    
    //register ClickEvents
    objComptable.registerClickEventInsertTable();
    objComptable.registerClickEventDeleteTable();
    objComptable.registerClickEventEditTableIsActive();
    objComptable.registerClickEventEditTableIsFavorit();
    objComptable.registerClickEventEditText();    
    

}); //end document.ready