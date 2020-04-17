/**
 * register Mouse-Events to add a new Dataset to DB
 */
$(document).ready(function() {

    var objComptable = new Comptable();
    objComptable.init();
    
    //set elements to add a new Dataset
    objComptable.setElem_Input_TableName($("[name='tableName']")); //(hidden field)
    objComptable.setElem_Input_ComptableName($("[name='comptableName']"));//(hidden field)
    objComptable.setElem_Input_DatasetKey($("[name='key']"));
    objComptable.setElem_Input_DatasetValue($("[name='value']"));
    objComptable.setElem_Textarea_BulkInsertDatasets("[name='datasets']");
    objComptable.setElem_Textbox_DatasetNote($("[name='datasetNote']"));    
    objComptable.setElem_Checkbox_DatasetPrivate($("[name='isPrivate']"));
    objComptable.setElem_Button_InsertDataset($('#btn_insertDataset'));
    objComptable.setElem_Button_BulkInsertDatasets($('#btn_bulkInsertDatasets'));
    
    //set elements to edit a Dataset
    objComptable.setElemClass_InputButton_DatasetValue('hyb_comptable_ds_value');  
    objComptable.setElemClass_TextboxButton_DatasetNote('hyb_comptable_ds_note'); 
    objComptable.setElemClass_CheckboxButtonUnchecked_DatasetPrivate('hyb_comptable_ds_isPrivate .fa-square-o');    
    objComptable.setElemClass_CheckboxButtonChecked_DatasetPrivate('hyb_comptable_ds_isPrivate .fa-check-square'); 
    objComptable.setElemClass_Button_DeleteDataset('btn_delete_dataset');
    
    //text-buttons to edit a text
    objComptable.setElemClass_hyb_editText('hyb_editText');
    
    //Ajax Responds
    objComptable.setElem_AjaxError($('.errorMsg'));
    objComptable.setElem_AjaxResponse($('.userResponse'));
        
    //set tables to include added data per Ajax
    objComptable.setElem_TableOfDatasets($('#hyb_tableOfDatasets'));
    
    //register ClickEvents
    objComptable.registerClickEventBulkInsertDatasets();
    objComptable.registerClickEventInsertDataset();
    objComptable.registerClickEventDeleteDataset();
    objComptable.registerClickEventEditDatasetPrivate();
    objComptable.registerClickEventEditText();

}); //end document.ready