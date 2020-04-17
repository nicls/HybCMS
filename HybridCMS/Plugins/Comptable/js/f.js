/*
 * Javascript file that handles events on Comptable
 */

//object Comptable
var Comptable = function() {

    /**
     * Current Comptable 
     * @type Object
     */
    var mObjComptable;

    /**
     * globaly needed functions
     * @type globFunctions
     */
    var mObjFunc;

    /**
     * readyToSubmit indicates if an Ajax-Request should be done or not
     * @type Boolean
     */
    var mReadyToSubmit = true;
    
    /**
     * keys that value are ordered by ABS(value) that is perfekt
     * for sorting strings like "99 €"
     * @type array
     */
    var mArrSortKeysAbsOrder = new Array();

    /**
     * public function to init Comptable
     * @returns void
     */
    this.init = function init() {

        //initiate functions-Object
        mObjFunc = new globFunctions();

    };

    /**
     * UI-Elements
     * @type Element
     */
    var mElemClass_sortTableAtoZ;
    var mElemClass_sortTableZtoA;
    var mElemClass_markAsFavorit;
    var mElemClass_demarcateAsFavorit;
    var mElemClass_keySelector;
    var mElemClass_lockKey;
    var mElemClass_unlockKey;
    var mElemClass_tableToTrash;
    var mElem_TableBody;

    /**
     * regsiterClickEventKeySelector
     * @returns {void}
     */
    this.regsiterClickEventKeySelector = function() {
        $(document).on('click', '.' + mElemClass_keySelector, function() {

            //check if clicked keySelector is active
            if ($(this).hasClass('activeKeySelector')) {

                var isLocked = $(this).find('i').hasClass('hyb_unlock');

                if (!isLocked) {

                    //deselect key
                    deselectKeySelector(this);
                }

            } else {
                //select key
                handleClickEventKeySelector(this);
            }

        });
    };

    function deselectKeySelector(elem) {

        var key = $(elem).attr('key');
        var className = mkClassName(key);

        $('.hyb_ds_' + className).removeClass('visibleDataset');
        $('.hyb_ds_' + className).hide();

        //remove active keySelektor that are not locked
        $(elem).removeClass('activeKeySelector');
        
        //hide lock-symbol
        $(elem).find('i').css('display', 'none');           
    }

    /**
     * handleClickedKeySelector
     * @param {Element} elem
     * @returns {void}
     */
    function handleClickEventKeySelector(elem) {

        var key = $(elem).attr('key');
        var className = mkClassName(key);

        //remove visible key that are not locked
        $('.visibleDataset').each(function() {
            var isLocked = $(this).hasClass('lockedKey');

            if (!isLocked) {
                $(this).removeClass('visibleDataset');
                $(this).hide();
            }
        });

        //remove active keySelektor that are not locked
        $('.activeKeySelector').each(function() {

            isLocked = $(this).find('i').hasClass(mElemClass_unlockKey);
            if (!isLocked) {
                $(this).removeClass('activeKeySelector');
                
                //hide lock-symbol
                $(this).find('i').css('display', 'none');
            }
        });

        //show clicked dataset
        $('.hyb_ds_' + className).fadeIn('slow');
        $('.hyb_ds_' + className).removeClass('hiddenDataset');
        $('.hyb_ds_' + className).addClass('visibleDataset');
        $(elem).addClass('activeKeySelector');
        
        //show lock-symbol
        $(elem).find('span i').first().css('display', 'inline-block');
    }

    /**
     * regsiterClickEventLockKey
     * @returns {void}
     */
    this.regsiterClickEventLockKey = function() {
        $(document).on('click', '.' + mElemClass_lockKey, function() {

            key = $(this).attr('key');

            handleClickEventLockKey(key);

        });
    };

    /**
     * hideAllDatasets
     * @returns {undefined}
     */
    this.hideAllDatasets = function() {
        $('.visibleDataset').toggleClass('hiddenDataset');
        $('.visibleDataset').toggleClass('visibleDataset');
    };

    /**
     * handleClickEventLockKey
     * @param {String} key
     * @returns {void}
     */
    function handleClickEventLockKey(key) {
        $('.' + mElemClass_lockKey).each(function() {

            var hasKey = $(this).attr('key') === key;

            if (hasKey) {

                //change icon
                $(this).toggleClass('fa-lock');
                $(this).toggleClass('fa-unlock-alt');
                $(this).toggleClass(mElemClass_lockKey);
                $(this).toggleClass(mElemClass_unlockKey);
                $(this).fadeIn();
            }
        });

        //mark column as locked
        var className = mkClassName(key);
        $('.hyb_ds_' + className).addClass('lockedKey');
    }

    /**
     * regsiterClickEventUnlockKey
     * @returns {void}
     */
    this.regsiterClickEventUnlockKey = function() {
        $(document).on('click', '.' + mElemClass_unlockKey, function() {

            key = $(this).attr('key');

            $('.' + mElemClass_unlockKey).each(function() {

                var hasKey = $(this).attr('key') === key;

                if (hasKey) {

                    //change icon
                    $(this).toggleClass('fa-lock');
                    $(this).toggleClass('fa-unlock-alt');
                    $(this).toggleClass(mElemClass_lockKey);
                    $(this).toggleClass(mElemClass_unlockKey);
                    
                    //hide lock-symbol of keyselector
                    if($(this).hasClass('childOfKeySelector')) {                    
                        $(this).css('display', 'none');                    
                    }
                }
            });


            //mark column as locked
            var className = mkClassName(key);
            $('.hyb_ds_' + className).removeClass('lockedKey');

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
                $(this).parent().parent().parent().fadeOut('slow', function() {
                    $(this).remove();
                });
            }
        });
    };

    /**
     * regsiterMouseoverEventMarkAsFavorit
     * @returns {void}
     */
    this.regsiterClickEventMarkAsFavorit = function() {
        $(document).on('click', '.' + mElemClass_markAsFavorit, function() {

            $(this).toggleClass(mElemClass_markAsFavorit);
            $(this).toggleClass(mElemClass_demarcateAsFavorit);
            $(this).toggleClass('fa-star-o');
            $(this).toggleClass('fa-star');
            $(this).parent().parent().parent().toggleClass('hyb_isFavorit');

        });
    };

    /**
     * regsiterMouseoverEventMarkAsFavorit
     * @returns {void}
     */
    this.regsiterClickEventDemarcateAsFavorit = function() {
        $(document).on('click', '.' + mElemClass_demarcateAsFavorit, function() {

            $(this).toggleClass(mElemClass_demarcateAsFavorit);
            $(this).toggleClass(mElemClass_markAsFavorit);
            $(this).toggleClass('fa-star-o');
            $(this).toggleClass('fa-star');
            $(this).parent().parent().parent().toggleClass('hyb_isFavorit');            

        });
    };

    /**
     * registerClickEventSortTableAsc
     * @returns {void}
     */
    this.registerClickEventSortTableAtoZ = function() {
        $(document).on('click', '.' + mElemClass_sortTableAtoZ, function() {

            var sortBy = $(this).attr('key');
            var comptableName = $(this).attr('comptableName');

            $(this).toggleClass(mElemClass_sortTableZtoA);
            $(this).toggleClass(mElemClass_sortTableAtoZ);
            $(this).toggleClass('fa-sort-alpha-asc');
            $(this).toggleClass('fa-sort-alpha-desc');

            requestSortedTableNames(comptableName, sortBy, 'asc');
        });
    };
    
    /**
     * Public function to sort tabel ascending
     * @param {string} sortBy key-Attribute of the column
     * @returns {void}
     */
    this.sortTableAtoZ = function(sortBy)
    {
        var elem = $('.hyb_sortAtoZ[key="'+ sortBy +'"]').first();
        var comptableName = $(elem).attr('comptableName');

        $(elem).toggleClass(mElemClass_sortTableZtoA);
        $(elem).toggleClass(mElemClass_sortTableAtoZ);
        $(elem).toggleClass('fa-sort-alpha-asc');
        $(elem).toggleClass('fa-sort-alpha-desc');

        requestSortedTableNames(comptableName, sortBy, 'asc', sortTableFast);
    }

    /**
     * registerClickEventSortTableAsc
     * @returns {void}
     */
    this.registerClickEventSortTableZtoA = function() {
        $(document).on('click', '.' + mElemClass_sortTableZtoA, function() {

            var sortBy = $(this).attr('key');
            var comptableName = $(this).attr('comptableName');

            $(this).toggleClass(mElemClass_sortTableZtoA);
            $(this).toggleClass(mElemClass_sortTableAtoZ);
            $(this).toggleClass('fa-sort-alpha-asc');
            $(this).toggleClass('fa-sort-alpha-desc');

            requestSortedTableNames(comptableName, sortBy, 'desc');
        });
    };

    /**
     * tableToTop
     * @param {String} tableId
     * @returns {undefined}
     */
    this.tableToTop = function(tableId) {
        var elemTr = $('#' + tableId).detach();
        $(mElem_TableBody).prepend(elemTr);
    };

    /**
     * initialShowKey
     * @param {String} idKeySelector
     * @returns {vpod}
     */
    this.initialShowKey = function(idKeySelector) {
        if ($('#' + idKeySelector)) {
            handleClickEventKeySelector($('#' + idKeySelector));
        }
    };

    /**
     * initialLockKey
     * @param {String} key
     * @returns {void}
     */
    this.initialLockKey = function(key) {
        if (key) {
            handleClickEventLockKey(key);
        }
    };

    /**
     * requestSortedTableNames - private function to request tableNames per CORS from server
     * @returns JSON-Object
     */
    function requestSortedTableNames(comptableName, sortBy, direction, sortTableFunction) {
        
        //use default sort table function if not defined
        if(typeof sortTableFunction === 'undefined') {
            sortTableFunction = sortTable;
        }

        if (direction !== 'asc' && direction !== 'desc') {
            mReadyToSubmit = false;
        }
        
        var useAbs = 'false';
        if(-1 !== $.inArray(sortBy, mArrSortKeysAbsOrder)) {
            useAbs = 'true';
        }

        if (mReadyToSubmit) {
            $.ajax({
                dataType: "json",
                type: "POST",
                url: "/HybridCMS/Ajax/api/ajax.php",
                data: {
                    plugin: 'comptable',
                    action: 'requestSortedTableNames',
                    comptableName: comptableName,
                    sortBy: sortBy,
                    direction: direction,
                    useAbs: useAbs
                },
                success: sortTableFunction
            });
        }
    }

    /**
     * sortTable
     * @param {Object} jsonTableNames
     * @returns {void}
     */
    function sortTable(jsonTableNames) {

        printSpinner();
        window.setTimeout(function() {

            removeSpinner();

            sortTableFast(jsonTableNames);
        }, 500);
    }
    
    /**
     * sortTable without delay and spinner
     * @param {Object} jsonTableNames
     * @returns {void}
     */
    function sortTableFast(jsonTableNames) {

        for (var key in jsonTableNames) {

            if (jsonTableNames.hasOwnProperty(key)) {

                //build the id
                var id = 'hyb_tbl_' + mkClassName(jsonTableNames[key]);

                var elemTr = $('#' + id).detach();
                $(mElem_TableBody).append(elemTr);
            }
        }
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

    function printSpinner() {

        $('html').prepend('<div id="spinner_overlay" style="background:white; opacity:0.6; height:100%; width:100%; position:fixed; z-index:99;"></div>');
        var spinner = new Spinner().spin();
        $('html').append(spinner.el);

        var widthDocument = $(document).width();
        var heightDocument = $(document).height();

        $('.spinner').css('position', 'absolute');

        $('.spinner').css('top', heightDocument / 2 - 5);
        $('.spinner').css('left', widthDocument / 2 - 5);

    }

    function removeSpinner() {
        $('#spinner_overlay').remove();
        $('.spinner').remove();
    }

    /**
     * setElemClass_sortTableAtoZ
     * @param {Element} elemClass
     * @returns {void}
     */
    this.setElemClass_sortTableAtoZ = function(elemClass) {
        if (elemClass) {
            mElemClass_sortTableAtoZ = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_sortTableAtoZ: elemClass is not valid.");
        }
    };
    this.setElemClass_sortTableZtoA = function(elemClass) {
        if (elemClass) {
            mElemClass_sortTableZtoA = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_sortTableZtoA: elemClass is not valid.");
        }
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
    this.setElemClass_keySelector = function(elemClass) {
        if (elemClass) {
            mElemClass_keySelector = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_keySelector: elemClass is not valid.");
        }
    };
    this.setElemClass_lockKey = function(elemClass) {
        if (elemClass) {
            mElemClass_lockKey = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_lockKey: elemClass is not valid.");
        }
    };
    this.setElemClass_unlockKey = function(elemClass) {
        if (elemClass) {
            mElemClass_unlockKey = elemClass;
        } else {
            mReadyToSubmit = false;
            console.log("function setElemClass_unlockKey: elemClass is not valid.");
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
    this.setElem_TableBody = function(elem) {
        if (elem) {
            mElem_TableBody = elem;
        } else {
            mReadyToSubmit = false;
            console.log("function setElem_TableBody: elem is not valid.");
        }
    };
    
    /**
     * add keyname to arrSortKeysAbsOrder
     * @param {string} key
     * @returns {void}
     */
    this.addKeyToArrSortKeysAbsOrder = function(keyname)
    {
        if(keyname.match(/^[a-zA-Z0-9öäüÖÄÜß\.,\-_\+\s\(\)]+$/))
        {
            mArrSortKeysAbsOrder.push(keyname);
        }
        else
        {
            console.log("Keyname ist not valid.");
        }
    };      
};

/**
 * Filters a key represented by a string like Manufacturer Names
 * @param {String} keyname
 * filter
 * @returns {void}
 */
var CompFilterString = function()
{        
    /**
     * classname of the key that you would like to filter
     */
    var mKeyname;
    
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
     * Checkboxen of the current Filter
     * @type Dom Elements
     */
    var mElems_checkboxes;
        
    /**
     * Array of distinct Values of that key
     */
    var mArrValues = new Array();
        
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
     * @param Beoolean checkCheckboxes
     * @returns {void}
     */
    this.printFilter = function(checkCheckboxes)
    {
        var headline = "<h4>" + mKeyname + "</h4>";        
        var checkbox = "";
        
        for(var i=0; i < mArrValues.length; i++) 
        {
            checkbox += '<div>';
            checkbox += "<input class='add-right-10' type='checkbox' name='" + mArrValues[i] + "' />";            
            checkbox += "<label for='"+ mArrValues[i] +"'>" 
                                  + mArrValues[i] 
                                  + " ("
                                  + getNumberOfValue(mArrValues[i]) 
                                  + ")"
                                  + "</label>";
            checkbox += '</div>';
        }
        
        //print checkboxes to DOM
        $("." + mContainerClassName).html(headline + checkbox).ready(function() 
        {            
            registerClickEventCheckboxes();
            if(true === checkCheckboxes) {
                checkAllCheckboxes();
            }
        });
    };
    
    /**
     * Register Click Event for ckeching and unchecking checkboxes
     * @returns {void}
     */
    function registerClickEventCheckboxes() 
    {
        //get all checkboxes
        mElems_checkboxes = 
                $("." + mContainerClassName + " input[type='checkbox']");

        //register click events
        $(mElems_checkboxes).on(
                'change', function() 
        {
            var value = $(this).attr('name'); 

            if($(this).is(':checked')) 
            {
                
                //set filter active
                if(false === currFilterIsActive()) 
                {
                    //activate the filter of the current/this instance
                    activateCurrFilter();
                    
                    //add blocking filter to datasets that does not contain
                    //value of the current filter instance
                    addBlockingFilterToOthers(value);
                }
                
                //remove blocking filter from those datasets that contain 
                //value
                removeBlockingFilterFromCurrent(value);
                
                
                hideBlockedDatasets();
                showNotBlockedDatasets();
            }
            else
            {
                if(0 < countCheckedCheckboxes())
                {
                    //add blocking filter to those datasets that match the 
                    //current unchecked value
                    addBlockingFilterToCurrent(value);
                    hideBlockedDatasets();
                }
                else
                {
                    deactivateCurrFilter();
                    removeBlockingFilterFromAll();
                }
                
                showNotBlockedDatasets();
            }
        });
    }
    
    /**
     * Pulbic function to check all checkboxes
     * @returns {undefined}
     */
    function checkAllCheckboxes() {
        
        if("undefined" !== typeof mElems_checkboxes)
        {
            $.each(mElems_checkboxes, function(){
                $(this).prop('checked', true);
                $(this).trigger("change");
            }); 
           
        }
        else 
        {
            console.log("mElems_checkboxes is undefined");
        }
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
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                                                 
            var currFilter = mkClassName(mKeyname);

            if(-1 < $.inArray(currFilter, arrBlockingFilter))
            {                                        
                 var index = arrBlockingFilter.indexOf(currFilter);
                 arrBlockingFilter.splice(index, 1);

                 $(this).parent().attr('hyb_blocking_filter', 
                     arrBlockingFilter.join(" "));
            }
        });
    }  
    
    
    /**
     * Remove blockingFilter on datasets with a given value
     * @returns {void}
     */
    function removeBlockingFilterFromCurrent(value)
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                                        
           //remove blocking filter from all those datasets that does 
           //correspond to the value
           if($(this).text() === value) 
           {                              
               if(-1 < $.inArray(mCurrFilter, arrBlockingFilter))
               {                                        
                    var index = arrBlockingFilter.indexOf(mCurrFilter);
                    arrBlockingFilter.splice(index, 1);
                    
                    $(this).parent().attr('hyb_blocking_filter', 
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
     * Hide all unchecked Rows. Show all Rows if no Checkbox is checked.
     * @returns {void}
     */
    function addBlockingFilterToOthers(value)
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                            
           //add blocking filter to all those datasets that does not 
           //correspond to the value
           if($(this).text() !== value) 
           {
               var currFilter = mkClassName(mKeyname);
               
               if(-1 === $.inArray(currFilter, arrBlockingFilter))
               {
                    arrBlockingFilter.push(currFilter);
                    $(this).parent().attr('hyb_blocking_filter',
                       arrBlockingFilter.join(" "));
               }
           }
        });        
    }
    
    /**
     * Hide all unchecked Rows. Show all Rows if no Checkbox is checked.
     * @returns {void}
     */
    function addBlockingFilterToCurrent(value)
    {
        var datasets = $('td.' + mElemClass_datasets);             
        
        $.each(datasets, function() {
            
            var arrBlockingFilter = new Array();
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                            
           //add blocking filter to all those datasets that does not 
           //correspond to the value
           if($(this).text() === value) 
           {               
               if(-1 === $.inArray(mCurrFilter, arrBlockingFilter))
               {
                    arrBlockingFilter.push(mCurrFilter);
                    $(this).parent().attr('hyb_blocking_filter',
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
            
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');
                        
            if(blocking_filter && 0 < blocking_filter.length) {
                $(this).parent().hide();
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
            
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');
                        
            if(!blocking_filter || 0 === blocking_filter.length) {
                $(this).parent().show();
            }
        });
    }   
    
    /**
     * Counts the number of checked checkboxes opf the current filter
     * @returns {Number
     */
    function countCheckedCheckboxes()
    {
        var cntActiveCheckboxes = 0;
        
        $.each(mElems_checkboxes, function(){
            if($(this).is(':checked')) 
            {
                cntActiveCheckboxes++;
            }
        });
        
        return cntActiveCheckboxes;
    }
    
    
    /**
     * Counts the number of a value in the table
     * @param {Sring} value
     * @returns {Number|cnt}
     */
    function getNumberOfValue(value)
    {
        arrValues = $('td.' + mElemClass_datasets);
        cnt = 0;
        
        for(var i=0; i < arrValues.length; i++) 
        {
            if($(arrValues[i]).text() === value)
            {
                cnt++;
            }            
        }  
        
        return cnt;
    }
    
    /**
     * Extracts all distinct values of the given keyname
     * @returns {void}
     */
    function extractDistinctKeyValues() 
    {
        arrValues = $('td.' + mElemClass_datasets);
        
        //delete duplicate values
        for(var i=0; i < arrValues.length; i++) 
        {
            if(-1 === $.inArray($(arrValues[i]).text(), mArrValues))
            {
                mArrValues.push($(arrValues[i]).text());
            }
        }      
        
        mArrValues.sort();
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
     
};


/**
 * Filters a key represented by a string like Manufacturer Names using a range
 * @param {String} keyname
 * @returns {void}
 */
var CompFilterRange = function()
{    
    
    //initiate functions-Object
    var mObjFunc = new globFunctions();
        
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
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
            if(blocking_filter) 
            {
                arrBlockingFilter = blocking_filter.split(" ");
            }
                                                 
            var currFilter = mkClassName(mKeyname);

            if(-1 < $.inArray(currFilter, arrBlockingFilter))
            {                                        
                 var index = arrBlockingFilter.indexOf(currFilter);
                 arrBlockingFilter.splice(index, 1);

                 $(this).parent().attr('hyb_blocking_filter', 
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
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
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
                    
                    $(this).parent().attr('hyb_blocking_filter', 
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
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');  
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
                    $(this).parent().attr('hyb_blocking_filter',
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
            
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');
                        
            if(blocking_filter && 0 < blocking_filter.length) {
                $(this).parent().hide();
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
            
            var blocking_filter = $(this).parent().attr('hyb_blocking_filter');
                        
            if(!blocking_filter || 0 === blocking_filter.length) {
                $(this).parent().show();
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
