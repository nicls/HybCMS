$(document).ready(function()
{
    var objRD = new ReadDetection(rdReadyCallback);    
    objRD.setElemClass_SectionName('hyb-rd');
    objRD.detect();
    
    /**
     * Called if Detection is completed
     * @returns {void}
     */
    function rdReadyCallback(sectionName)
    {
        console.log('approved: ' + sectionName);
    }
});