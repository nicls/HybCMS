/**
 * register Mouse-Events to add a new Dataset to DB
 */
$(document).ready(function() {

    var objComptable = new Comptable();
    objComptable.init();
    objComptable.hideAllDatasets();

    //set ui-elements
    objComptable.setElemClass_sortTableAtoZ("hyb_sortAtoZ");
    objComptable.setElemClass_sortTableZtoA("hyb_sortZtoA");
    objComptable.setElem_TableBody($('#hyb_tableBody'));
    objComptable.setElemClass_demarcateAsFavorit('hyb_demarcateAsFavorit');
    objComptable.setElemClass_markAsFavorit('hyb_markAsFavorit');
    objComptable.setElemClass_keySelector('keySelector');
    objComptable.setElemClass_lockKey('hyb_lock');
    objComptable.setElemClass_unlockKey('hyb_unlock');
    objComptable.setElemClass_tableToTrash('hyb_toTrash');

    //register ClickEvents
    objComptable.registerClickEventSortTableAtoZ();
    objComptable.registerClickEventSortTableZtoA();
    objComptable.regsiterClickEventDemarcateAsFavorit();
    objComptable.regsiterClickEventMarkAsFavorit();
    objComptable.regsiterClickEventKeySelector();
    objComptable.regsiterClickEventLockKey();
    objComptable.regsiterClickEventUnlockKey();
    objComptable.regsiterClickEventTableToTrash();

    //tables to top
    //objComptable.tableToTop('hyb_tbl_snapshot_mini');
    //objComptable.tableToTop('hyb_tbl_snapshot_pro');
    //objComptable.tableToTop('hyb_tbl_gprs_cam_2');    

    //initial show keys        
    objComptable.initialShowKey('hyb_keySelector_preis_ca');  
    
    //sort prices with ABS
    objComptable.addKeyToArrSortKeysAbsOrder('Preis ca.');
    
    //sort table by price
    objComptable.sortTableAtoZ('Preis ca.');

    //show Preis + Hersteller
    if($(window).width() > 760)
    {
        objComptable.initialLockKey('Preis ca.'); 
        objComptable.initialShowKey('hyb_keySelector_hersteller');
    }
    
    //show Preis + Hersteller + Bewertung
    if($(window).width() > 1072)
    {       
        objComptable.initialLockKey('Hersteller');
        objComptable.initialShowKey('hyb_keySelector_bewertung_bei_amazon');
    }   
    
    //show Preis + Hersteller + Bewertung + Auslösezeit
    if(false && $(window).width() > 1199)
    {      
        objComptable.initialLockKey('Bewertung bei Amazon');        
        objComptable.initialShowKey('hyb_keySelector_auslösezeit');
    } 
        
    //show Preis + Hersteller + Bewertung + Auslösezeit
    if(false && $(window).width() > 1199)
    {      
        objComptable.initialLockKey('Bewertung bei Amazon');        
        objComptable.initialShowKey('hyb_keySelector_auslösezeit');
    } 
            
    //set min-height of comptable
    tmpHeight = $('.hyb_comptable_keys').height();
    $('#hyb_comptableContainer').css('min-height', tmpHeight); 
    
    /**
     * Setup Filters
     */
    /*
    var objFilterHersteller = new CompFilterString();
    objFilterHersteller.setKeyname("Hersteller");
    objFilterHersteller.setContainerClassName('hyb_comp_filter_hersteller');
    objFilterHersteller.init();
    objFilterHersteller.printFilter(true);
     
    var objFilterGprs = new CompFilterString();
    objFilterGprs.setKeyname("Senden an Email (GPRS)");
    objFilterGprs.setContainerClassName('hyb_comp_filter_gprs');
    objFilterGprs.init();
    objFilterGprs.printFilter(true);    

    var objFilterBlitz = new CompFilterString();
    objFilterBlitz.setKeyname("Blitz ist unsichtbar");
    objFilterBlitz.setContainerClassName('hyb_comp_filter_blitz');
    objFilterBlitz.init();
    objFilterBlitz.printFilter(true);   
    
    var objFilterBlitz = new CompFilterString();
    objFilterBlitz.setKeyname("WLAN");
    objFilterBlitz.setContainerClassName('hyb_comp_filter_wlan');
    objFilterBlitz.init();
    objFilterBlitz.printFilter(true);      
    */
    /*
    var objFilterInterpol = new CompFilterString();
    objFilterInterpol.setKeyname("Auflösung interpoliert");
    objFilterInterpol.setContainerClassName('hyb_comp_filter_interpol');
    objFilterInterpol.init();
    objFilterInterpol.printFilter();    
    */
    var objFilterPreis = new CompFilterRange();
    objFilterPreis.setKeyname("Preis ca.");
    objFilterPreis.setContainerClassName('hyb_comp_filter_preis');
    objFilterPreis.setObjRegEx(/^(\d*)\s\u20ac$/);
    objFilterPreis.setStartMinValue(80);
    objFilterPreis.setStartMaxValue(540);
    objFilterPreis.setSliderSteps(90);
    objFilterPreis.init();
    objFilterPreis.printFilter();
    
    var objFilterAmazon = new CompFilterRange();
    objFilterAmazon.setKeyname("Bewertung bei Amazon");
    objFilterAmazon.setHeadline("Bewertung bei Amazon (0 bis 5 Sterne)");    
    objFilterAmazon.setContainerClassName('hyb_comp_filter_amazon');
    objFilterAmazon.setObjRegEx(/^(\d,\d)\s.*$/);
    objFilterAmazon.setStartMinValue(0);
    objFilterAmazon.setStartMaxValue(5);
    objFilterAmazon.setSliderSteps(1);
    objFilterAmazon.init();
    objFilterAmazon.printFilter();      
        
    //track events on outgoing links
    var objGlobFunc = new globFunctions();
    $("a[href*='redirect.html?url=http://www.amazon.de']").on('click', function(){
        objGlobFunc.gaTrkEvent(
                'Amazon', //category
                $(this).attr('title'), //action
                $(this).attr('href'), //opt_label
                0, //opt_value
                true //opt_noninteraction
                );
    });
    
    //make images zoomable
    if(objGlobFunc.getDeviceType() === "desktop")
    {
        $('.hyb_ds_table_name .img').zoom(); 
    }
    
    //make filter inital open or closed depending on 
    //the daytime and day of the week
    /*
    var objDate = new Date();
    var h = objDate.getHours(); 
    var d = objDate.getDay();
    if(h < 12 && (d !== 6 && d !== 0)) {
        $('#collapseOne_filter').collapse();
    }
    */
    /*
    //scroll to table
    if($("#logo:in-viewport").length === 1) 
    {
        var scrollComptable = $(window).bind("scroll", function(event) {            
            var accordion = $("#wildkamera-vergleich:above-the-top");
            if(accordion.length === 1) 
            {                
                $(window).unbind("scroll"); 

                $('html, body').animate({
                    scrollTop: $('#hyb_comptableKeysContainer').offset().top -60
                }, 1500);        
            }
        });
    }   
    */
    
}); //end document.ready
