/**
 * Filters a key represented by a string like Manufacturer Names using a range
 * @param {String} keyname
 * @returns {void}
 */
var RangeFilter = function()
{    
    
    //initiate functions-Object
    var mObjFunc = new globFunctions();
        
    /**
     * Attributname of the key that you would like to filter
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
     * Container Classname of the Element that holds the filter.
     * @type String
     */
    var mContainerClassName;
            
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
        mCurrFilter = mKeyname;
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

        //print slider to DOM
        $("." + mContainerClassName).before(headline)
            .ready(function() {    
                $(".hyb_range-filter." + mContainerClassName) 
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
                        
                        $(".hyb_range-filter." + mContainerClassName).slider(
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
        $(".hyb_range-filter." + mContainerClassName)
            .on( "slidechange", function(event, ui) 
            {
                            
                mObjFunc.printSpinner();
                window.setTimeout(function(){
                    if(typeof mObjFunc !== 'undefined') 
                    {            
                        mObjFunc.removeSpinner();
                    }
                }, 160);                 
            
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
        var datasets = $('[' + mKeyname + ']');             
        
        $.each(datasets, function() 
        {
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).attr('hyb_blocking_rangefilter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                                                 
            var currFilter = mKeyname;

            if(-1 < $.inArray(currFilter, arrBlockingFilter))
            {                                        
                 var index = arrBlockingFilter.indexOf(currFilter);
                 arrBlockingFilter.splice(index, 1);

                 $(this).attr('hyb_blocking_rangefilter', 
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
        var datasets = $('[' + mKeyname + ']');            
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).attr('hyb_blocking_rangefilter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
            
            //normalice value
            var value = $(this).attr(mKeyname);                        

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
                    
                    $(this).attr('hyb_blocking_rangefilter', 
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
        var currActiveFilters = $("body").attr("activeRangeFilters");
        if(currActiveFilters) 
        {
            arrCurrActiveFilters = currActiveFilters.split(" ");            
        }
        
        if(-1 === $.inArray(mCurrFilter, arrCurrActiveFilters)) 
        {            
            arrCurrActiveFilters.push(mCurrFilter);
            $("body").attr("activeRangeFilters", arrCurrActiveFilters.join(" "));
        }
    }
    
    /**
     * Checks if the current filter is active or not
     * @returns {Boolean}
     */
    function currFilterIsActive()
    {
        var arrCurrActiveFilters = new Array();
        var currActiveFilters = $("body").attr("activeRangeFilters");
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
        var currActiveFilters = $("body").attr("activeRangeFilters");
        if(currActiveFilters) 
        {
            arrCurrActiveFilters = currActiveFilters.split(" ");            
        }
        
        if(-1 < $.inArray(mCurrFilter, arrCurrActiveFilters)) 
        {            
            var index = arrCurrActiveFilters.indexOf(mCurrFilter);
            arrCurrActiveFilters.splice(index, 1);            
            $("body").attr("activeRangeFilters", 
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
        var datasets = $('[' + mKeyname + ']');           
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).attr('hyb_blocking_rangefilter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                            
            var value = $(this).attr(mKeyname);

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
               var currFilter = mKeyname;
               
               if(-1 === $.inArray(currFilter, arrBlockingFilter))
               {
                    arrBlockingFilter.push(currFilter);
                    $(this).attr('hyb_blocking_rangefilter',
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
        var datasets = $('[' + mKeyname + ']');            
        
        $.each(datasets, function() {
            
            var blocking_filter = $(this).attr('hyb_blocking_rangefilter');
                        
            if(blocking_filter && 0 < blocking_filter.length) {
                $(this).hide();
            }
        });
    }
    
    
    /**
     * Show Datasets that have no blockingFilters
     * @returns {void}
     */
    function showNotBlockedDatasets()
    {
        var datasets = $('[' + mKeyname + ']');         
        
        $.each(datasets, function() {
            
            var blocking_filter = $(this).attr('hyb_blocking_rangefilter');
                        
            if(!blocking_filter || 0 === blocking_filter.length) {
                $(this).show();
            }
        });
    }    
    
    /**
     * Sets Keyname and mKeyname
     * @returns {void}
     */
    this.setKeyname = function(keyname)
    {
        if(keyname.match(/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/))
        {
            mKeyname = keyname;
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
        if(headline.match(/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/))
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
     * Set minValue
     * @param {numeric} minValue
     * @returns {void}
     */
    this.setMinValue = function(minValue)
    {
        if(true === mObjFunc.isNumeric(minValue))
        {
            mMinValue = minValue;
        }
        else 
        {
            console.log("minValue steps in not numeric.");
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
    
    /**
     * Set currMaxValue
     * @param {numeric} currMinValue
     * @returns {void}
     */
    this.setMaxValue = function(maxValue)
    {
        if(true === mObjFunc.isNumeric(maxValue))
        {
            mMaxValue = maxValue;
        }
        else 
        {
            console.log("maxValue steps in not numeric.");
        }
    };        
     
};
