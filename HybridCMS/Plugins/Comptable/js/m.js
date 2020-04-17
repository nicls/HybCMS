/*
 * Javascript file that handles events on Comptable
 */

//object Comptable
var Comptable = function() {
    
    var mElemClass_markAsFavorit;
    var mElemClass_demarcateAsFavorit;
    var mElemClass_tableToTrash;    
        
    /**
     * regsiterMouseoverEventMarkAsFavorit
     * @returns {void}
     */
    this.regsiterClickEventMarkAsFavorit = function() {
        $(document).on('click', '.' + mElemClass_markAsFavorit, function() {
                      
            var tablename = $(this).parent().parent().parent().parent().parent().attr('id');
                        
            $('#' + tablename + ' .' + mElemClass_markAsFavorit).toggleClass(mElemClass_demarcateAsFavorit);
            $('#' + tablename + ' .' + mElemClass_markAsFavorit + ' i').toggleClass('fa-star-o');
            $('#' + tablename + ' .' + mElemClass_markAsFavorit + ' i').toggleClass('fa-star');
            $('#' + tablename + ' .' + mElemClass_markAsFavorit).toggleClass(mElemClass_markAsFavorit);
            
            $('#' + tablename).addClass('hyb_isFavorit');

        });
    };

    /**
     * regsiterMouseoverEventMarkAsFavorit
     * @returns {void}
     */
    this.regsiterClickEventDemarcateAsFavorit = function() {
        $(document).on('click', '.' + mElemClass_demarcateAsFavorit, function() {

            var tablename = $(this).parent().parent().parent().parent().parent().attr('id');
            
            $('#' + tablename + ' .' + mElemClass_demarcateAsFavorit).toggleClass(mElemClass_markAsFavorit);
            $('#' + tablename + ' .' + mElemClass_demarcateAsFavorit + ' i').toggleClass('fa-star-o');
            $('#' + tablename + ' .' + mElemClass_demarcateAsFavorit + ' i').toggleClass('fa-star');
            $('#' + tablename + ' .' + mElemClass_demarcateAsFavorit).toggleClass(mElemClass_demarcateAsFavorit);
            
            $('#' + tablename).removeClass('hyb_isFavorit');            

        });
    };    
    
    /**
     * regsiterClickEventTableToTrash
     * @returns {void}
     */
    this.regsiterClickEventTableToTrash = function() {
        $(document).on('click', '.' + mElemClass_tableToTrash, function() {

            var confirmed = confirm("Spalte wirklich ausblenden?");
            
            if(confirmed) {
                var tablename = $(this).parent().parent().parent().parent().parent().attr('id');
                $('#' + tablename).hide('slow');  
            }
        });
    };    
    
    this.setElemClass_markAsFavorit = function(elemClass) {
        if (elemClass) {
            mElemClass_markAsFavorit = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_markAsFavorit: elemClass is not valid.");
        }
    };
    this.setElemClass_demarcateAsFavorit = function(elemClass) {
        if (elemClass) {
            mElemClass_demarcateAsFavorit = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_demarcateAsFavorit: elemClass is not valid.");
        }
    };
    this.setElemClass_tableToTrash = function(elemClass) {
        if (elemClass) {
            mElemClass_tableToTrash = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function mElemClass_tableToTrash: elemClass is not valid.");
        }
    };    
};


/**
 * Filters a key represented by a string like Manufacturer Names using a range
 * @param {String} keyname
 * @returns {void}
 */
var CompFilterRange = function()
{    
    
    //initiate functions-Object
    var mObjFunc = objGlobFunc;
        
    /**
     * classname of the key that you would like to filter
     */
    var mKeyname;
    
    /**
     * Headline obove the slider. If headline is not set, the keyname is used
     * instead of the headline.
     * @type string
     */
    var mHeadline;
    
    /**
     * Current Filter name
     * @type String
     */
    var mCurrFilter;
    
    /**
     * Classname of all datasets of the given key
     */
    var mElemClass_datasets;
    
    /**
     * Container Classname of the Element that holds the filter.
     * @type String
     */
    var mContainerClassName;
        
    /**
     * Array of distinct Values of that key.
     */
    var mArrValues = new Array();
    
    /**
     * Min Value
     * @type Integer
     */
    var mMinValue;
    var mCurrMinValue;
    var mStartMinValue;
    
    /**
     * Min Value
     * @type Integer
     */
    var mMaxValue;
    var mCurrMaxValue;
    var mStartMaxValue;
    
    /**
     * Steps in the scala
     * @type Integer
     */
    var mSliderSteps;
    
    /**
     * Regex Pattern to normalice the values
     * @type Regex
     */
    var mObjRegEx;
        
    /**
     * Initialise Filter
     * @returns {void}
     */
    this.init = function() 
    {           
        extractDistinctKeyValues();
        mCurrFilter = mkClassName(mKeyname);
    };
    
    /**
     * Prints the Filter to html
     * @returns {void}
     */
    this.printFilter = function()
    {
        //check if headline is set, else use keyname
        if(typeof mHeadline === 'undefined') {
            mHeadline = mKeyname;
        }
        
        var headline = "<h4>" + mHeadline + "</h4>";                        
        var slider = '<div class="hyb_slider"></div>';
                
        //print slider to DOM
        $("." + mContainerClassName).html(headline + slider)
            .ready(function() {    
                $("." + mContainerClassName + " .hyb_slider") 
                    .slider({ 
                        min: mMinValue, 
                        max: mMaxValue, 
                        step: mSliderSteps,
                        range: true, 
                        values: [mStartMinValue, mStartMaxValue] 
                    })
                    .slider("pips", {
                        rest: "label",
                        prefix: "",
                        suffix: ""
                    })
                    .slider("float")
                    .ready(function() {
                        registerOnchangeEventSlider();                
                        
                        $("." + mContainerClassName + " .hyb_slider").slider(
                                "option", "values", [mStartMinValue, mStartMaxValue]);
                    });
            });
    };
    
    /**
     * Register Onchange Event for Slider
     * @returns {void}
     */
    function registerOnchangeEventSlider() 
    {
        //register click events
        $("." + mContainerClassName + " .hyb_slider")
            .on( "slidechange", function(event, ui) 
            {
                var values = $(this).slider( "option", "values" );
                
                mCurrMinValue = Math.min(values[0], values[1]);
                mCurrMaxValue = Math.max(values[0], values[1]);
                
                //check if slider is activated by the user
                //if the current values are euqal to the min and max values 
                //the filter is inactive
                if(mCurrMinValue > mMinValue
                   ||
                   mCurrMaxValue < mMaxValue) 
                {

                    //set filter active
                    if(false === currFilterIsActive()) 
                    {
                        //activate the filter of the current/this instance
                        activateCurrFilter();
                    }
                    
                    //add blocking filter to datasets that does not contain
                    //value of the current filter instance
                    addBlockingFilterToOthers();                    

                    //remove blocking filter from those datasets that contain 
                    //value
                    removeBlockingFilterFromCurrent();

                    hideBlockedDatasets();
                    showNotBlockedDatasets();
                }
                else
                {
                    //deactivate filter and show all datasets that are not 
                    //blocked by any filter
                    deactivateCurrFilter();
                    removeBlockingFilterFromAll();                    
                    showNotBlockedDatasets();
                }
            });
    }
    
    /**
     * Remove curent blockingFilter from all datasets
     * @returns {void}
     */
    function removeBlockingFilterFromAll()
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() 
        {
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                                                 
            var currFilter = mkClassName(mKeyname);

            if(-1 < $.inArray(currFilter, arrBlockingFilter))
            {                                        
                 var index = arrBlockingFilter.indexOf(currFilter);
                 arrBlockingFilter.splice(index, 1);

                 $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter', 
                     arrBlockingFilter.join(" "));
            }
        });
    }  
    
    
    /**
     * Remove blockingFilter on datasets that are in range of the current 
     * min and max values
     * @returns {void}
     */
    function removeBlockingFilterFromCurrent()
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
            
            //normalice value
            var value = $(this).text();

            //nomralice 
            var match = value.match(mObjRegEx);

            //check if somethin was found
            if(match)
            {
                value = parseInt(match[1]);
            }
            else 
            {
                value = 0;
            }
                                                    
           //remove blocking filter from all those datasets that are in
           //range of the current min and max value
           if(value >= mCurrMinValue && value <= mCurrMaxValue) 
           {                              
               if(-1 < $.inArray(mCurrFilter, arrBlockingFilter))
               {                                        
                    var index = arrBlockingFilter.indexOf(mCurrFilter);
                    arrBlockingFilter.splice(index, 1);
                    
                    $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter', 
                        arrBlockingFilter.join(" "));
               }
           }
        });        
    }  
    
    /**
     * Activates the current filter.
     * @returns {void}
     */
    function activateCurrFilter()
    {      
        var arrCurrActiveFilters = new Array();
        var currActiveFilters = $("body").attr("activeFilters");
        if(currActiveFilters) 
        {
            arrCurrActiveFilters = currActiveFilters.split(" ");            
        }
        
        if(-1 === $.inArray(mCurrFilter, arrCurrActiveFilters)) 
        {            
            arrCurrActiveFilters.push(mCurrFilter);
            $("body").attr("activeFilters", arrCurrActiveFilters.join(" "));
        }
    }
    
    /**
     * Checks if the current filter is active or not
     * @returns {Boolean}
     */
    function currFilterIsActive()
    {
        var arrCurrActiveFilters = new Array();
        var currActiveFilters = $("body").attr("activeFilters");
        if(currActiveFilters) 
        {
            arrCurrActiveFilters = currActiveFilters.split(" ");            
        }
        
        if(-1 < $.inArray(mCurrFilter, arrCurrActiveFilters)) 
        {            
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /**
     * Deactivate the current filter
     * @returns {void}
     */
    function deactivateCurrFilter()
    {       
        var arrCurrActiveFilters = new Array();
        var currActiveFilters = $("body").attr("activeFilters");
        if(currActiveFilters) 
        {
            arrCurrActiveFilters = currActiveFilters.split(" ");            
        }
        
        if(-1 < $.inArray(mCurrFilter, arrCurrActiveFilters)) 
        {            
            var index = arrCurrActiveFilters.indexOf(mCurrFilter);
            arrCurrActiveFilters.splice(index, 1);            
            $("body").attr("activeFilters", 
                arrCurrActiveFilters.join(" "));
        }
    }
    
    /**
     * Add blocking filter to all datasets that are not in range of the 
     * current min and max value
     * @returns {void}
     */
    function addBlockingFilterToOthers()
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                            
            var value = $(this).text();

            //nomralice 
            var match = value.match(mObjRegEx);

            //check if somethin was found
            if(match)
            {
                value = parseInt(match[1]);
            }
            else 
            {
                value = 0;
            }
                                                    
           //add blocking filter to all those datasets that does not 
           //match the range of the current min and max value
           if(value < mCurrMinValue || value > mCurrMaxValue) 
           {
               var currFilter = mkClassName(mKeyname);
               
               if(-1 === $.inArray(currFilter, arrBlockingFilter))
               {
                    arrBlockingFilter.push(currFilter);
                    $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter',
                       arrBlockingFilter.join(" "));
               }
           }
        });        
    }
                           
    
    /**
     * Hide Datasets that have blockingFilters
     * @returns {void}
     */
    function hideBlockedDatasets()
    {
        var datasets = $('td.' + mElemClass_datasets);  
                
        $.each(datasets, function() {
            
            var blocking_filter = $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter');
                        
            if(blocking_filter && 0 < blocking_filter.length) {                        
                $(this).parent().parent().parent().parent().parent().parent().hide();
            }
        });
    }
    
    
    /**
     * Show Datasets that have no blockingFilters
     * @returns {void}
     */
    function showNotBlockedDatasets()
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var blocking_filter = $(this).parent().parent().parent().parent().parent().parent().attr('hyb_blocking_filter');
                        
            if(!blocking_filter || 0 === blocking_filter.length) {
                $(this).parent().parent().parent().parent().parent().parent().show();
            }
        });
    }   

    
    /**
     * Extracts all distinct values of the given keyname
     * @returns {void}
     */
    function extractDistinctKeyValues() 
    {
        var arrValues = $('td.' + mElemClass_datasets);
        
        //delete duplicate values
        for(var i=0; i < arrValues.length; i++) 
        {
            if(-1 === $.inArray($(arrValues[i]).text(), mArrValues))
            {
                var value = $(arrValues[i]).text();
                 
                if(mObjRegEx && mObjRegEx instanceof RegExp)
                {
                    //nomralice 
                    var match = value.match(mObjRegEx);

                    //check if somethin was found
                    if(match)
                    {
                        value = parseInt(match[1]);
                    }
                    else 
                    {
                        value = 0;
                    }
                }
                
                mArrValues.push(value);
            }
        }      
        
        mArrValues.sort(function(a, b){return a-b;});        
        
        mMinValue = Math.floor(mArrValues[0]); 
        mMaxValue = Math.ceil(mArrValues[mArrValues.length - 1]);
    }    
 
    /**
     * Converts a string into a css class-name
     * @param String
     * @return String
     */
    function mkClassName(string) {

        string = string.toLowerCase();
        string = string.replace(/ä/g, 'ae');
        string = string.replace(/ö/g, 'oe');
        string = string.replace(/ü/g, 'ue');
        string = string.replace(/ß/g, 'ss');
        string = string.replace(/\s/g, '_');
        string = string.replace(/-/g, '_');
        string = string.replace(/\./g, '');
        string = string.replace(/\(/g, '');
        string = string.replace(/\)/g, '');

        return string;
    }    
    
    /**
     * Sets Keyname and mElemClass_datasets
     * @returns {void}
     */
    this.setKeyname = function(keyname)
    {
        if(keyname.match(/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/))
        {
            mKeyname = keyname;
            mElemClass_datasets = "hyb_ds_" + mkClassName(keyname);
        }
        else
        {
            console.log("Keyname ist not valid.");
        }
    };
    
    /**
     * Sets Headline
     * @returns {void}
     */
    this.setHeadline = function(headline)
    {
        if(headline .match(/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/))
        {
            mHeadline = headline;
        }
        else
        {
            console.log("Headline ist not valid.");
        }
    };    
    
    /**
     * Sets the ContainerClassname that holds the filter for the user.
     * @returns {void}
     */
    this.setContainerClassName = function(containerClassName)
    {
        if(containerClassName.match(/^[a-zA-Z0-9\-_]+$/))
        {
            mContainerClassName = containerClassName;
        }
        else
        {
            console.log("containerClassName ist not valid.");
        }
    }; 
    
    /**
     * Sets the regEx to mormalice the values.
     * @returns {void}
     */
    this.setObjRegEx = function(objRegEx)
    {
        if(objRegEx instanceof RegExp)
        {
            mObjRegEx = objRegEx;
        }
        else
        {
            console.log("objRegEx ist not valid.");
        }
    }; 
    

    /**
     * Set slider steps
     * @param {numeric} sliderSteps
     * @returns {void}
     */
    this.setSliderSteps = function(sliderSteps)
    {
        if(true === mObjFunc.isNumeric(sliderSteps))
        {
            mSliderSteps = sliderSteps;
        }
        else 
        {
            console.log("slider steps in not numeric.");
        }
    };
    
    /**
     * Set currMinValue
     * @param {numeric} startMinValue
     * @returns {void}
     */
    this.setStartMinValue = function(startMinValue)
    {
        if(true === mObjFunc.isNumeric(startMinValue))
        {
            mStartMinValue = startMinValue;
        }
        else 
        {
            console.log("startMinValue steps in not numeric.");
        }
    };    
    
    /**
     * Set currMaxValue
     * @param {numeric} currMinValue
     * @returns {void}
     */
    this.setStartMaxValue = function(startMaxValue)
    {
        if(true === mObjFunc.isNumeric(startMaxValue))
        {
            mStartMaxValue = startMaxValue;
        }
        else 
        {
            console.log("startMaxValue steps in not numeric.");
        }
    };       
     
};


/**
 * register Mouse-Events to add a new Dataset to DB
 */
$(document).ready(function() {
    var objComptable = new Comptable();

    //set ui-elements
    objComptable.setElemClass_demarcateAsFavorit('hyb_demarcateAsFavorit');
    objComptable.setElemClass_markAsFavorit('hyb_markAsFavorit');
    objComptable.setElemClass_tableToTrash('hyb_toTrash');

    //register ClickEvents
    objComptable.regsiterClickEventDemarcateAsFavorit();
    objComptable.regsiterClickEventMarkAsFavorit();
    objComptable.regsiterClickEventTableToTrash();
    
    var objFilterPreis = new CompFilterRange();
    objFilterPreis.setKeyname("Preis ca.");
    objFilterPreis.setContainerClassName('hyb_comp_filter_preis');
    objFilterPreis.setObjRegEx(/^(\d*)\s\u20ac$/);
    objFilterPreis.setStartMinValue(80);
    objFilterPreis.setStartMaxValue(190);
    objFilterPreis.setSliderSteps(90);
    objFilterPreis.init();
    objFilterPreis.printFilter();    
    
    var objFilterAmazon = new CompFilterRange();
    objFilterAmazon.setKeyname("Bewertung bei Amazon");
    objFilterAmazon.setHeadline("Bewertung bei Amazon (0-5 Sterne)");
    objFilterAmazon.setContainerClassName('hyb_comp_filter_amazon');
    objFilterAmazon.setObjRegEx(/^(\d,\d)\s.*$/);
    objFilterAmazon.setStartMinValue(0);
    objFilterAmazon.setStartMaxValue(5);
    objFilterAmazon.setSliderSteps(1);
    objFilterAmazon.init();
    objFilterAmazon.printFilter();     
    
    //track events on outgoing links
    $("a[href*='redirect.html?url=http://www.amazon.de']").on('click', function(){
        objGlobFunc.gaTrkEvent(
                'Amazon', //category
                $(this).attr('title'), //action
                $(this).attr('href'), //opt_label
                0, //opt_value
                true //opt_noninteraction
                );
    });    
});

