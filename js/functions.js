/**
 * Often needed functions
 * @returns void
 */
var globFunctions = function() {

    var self = this;

    /**
     * ajaxRequest
     *
     * @param objData - data so submit per ajax
     * @param callback - callackfunction
     * @param element - DOM-Element that will be passed throu the callback
     */
    this.ajaxRequest = function(objData, callback, elemUserResponseAjax) {

        var objResponse = new Object();

        $.ajax({
            type: "POST",
            url: "/HybridCMS/Ajax/api/ajax.php",
            data: objData
        }).success(function(msg, textStatus) {

            if (msg.search('failed') === -1) {
                objResponse.success = true;
            } else {
                objResponse.success = false;
            }
        }).error(function(msg, textStatus) {
            objResponse.success = false;

        }).done(function(msg) {

            //call callbackfunction
            callback(msg, elemUserResponseAjax);

        });
    };

    /**
     * JsonRequest
     *
     * @param objData - data so submit per ajax
     * @param callback - callackfunction
     * @param element - DOM-Element that will be passed throu the callback
     */
    this.jsonRequest = function(objData, callback, elemUserResponseAjax) {
                
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "/HybridCMS/Ajax/api/ajax.php",
            data: objData
        }).success(function(jsonResponse, textStatus) {
            
            //call callbackfunction
            callback(jsonResponse, elemUserResponseAjax);
            
        }).error(function(msg, textStatus) {
            
        }).done(function(msg) {        

        });
    };

    /**
     * validateUrl
     * @param {String} url
     * @returns {Boolean}
     */
    this.validateUrl = function(url) {
        var regex = new RegExp("^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\.\,_\?\'/\\\+&%\$#\=~;])*$");
        return regex.test(url);
    };
    
    /**
     * Checks if n is numeric
     * @param {mixed} n
     * @returns {boolean}
     */
    this.isNumeric = function(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };    
    
    /**
     * Checks if a given string is alphaNumeric
     * @param {string} string
     * @returns {Boolean}
     */
    this.isAlphaNumeric = function(string)
    {        
        var isAlphaNumeric = true; 
        
        if( /[^a-zA-Z0-9]/.test(string) ) 
        {          
           isAlphaNumeric = false;
        }
        
        return isAlphaNumeric;     
    };    
     
    /**
     * Shuffles an array randomly
     * @param {mixed[]} arr
     * @returns {mixed[]}
     */
    this.shuffleArray = function(arr)
    {
        for(var j, x, i = arr.length; 
            i; 
            j = Math.floor(Math.random() * i), 
                x = arr[--i], arr[i] = arr[j], arr[j] = x);
        return arr;
    };     

    /**
     * getPageScroll - return the current scroll-height
     * 
     * @return Integer[]
     */
    this.getPageScroll = function() {
        var xScroll, yScroll;
        if (self.pageYOffset) {
            yScroll = self.pageYOffset;
            xScroll = self.pageXOffset;
        } else if (document.documentElement && document.documentElement.scrollTop) {
            yScroll = document.documentElement.scrollTop;
            xScroll = document.documentElement.scrollLeft;
        } else if (document.body) {// all other Explorers
            yScroll = document.body.scrollTop;
            xScroll = document.body.scrollLeft;
        }
        return new Array(xScroll, yScroll);
    };

    /**
     * 
     * @param String category - The name you supply for the group of objects you want to track.
     * @param String action - A string that is uniquely paired with each category, and commonly used to define the type of user interaction for the web object.
     * @param String opt_label - An optional string to provide additional dimensions to the event data.
     * @param String opt_value - An integer that you can use to provide numerical data about the user event.
     * @param Integer opt_noninteraction - A boolean that when set to true, indicates that the event hit will not be used in bounce-rate calculation.
     * @returns void
     */
    this.gaTrkEvent = function(category, action, opt_label, opt_value, 
        opt_noninteraction) 
    {
        if(typeof opt_noninteraction !== 'boolean')
        {
            opt_noninteraction = false;
        }

        //register click events
        if (
            (typeof(_gaq) != "undefined" || typeof(ga) != "undefined") 
            && 
            category 
            && 
            action 
            && 
            opt_label 
            && 
            !isNaN(opt_value) 
            ) 
        {
            if(ga) //use universal analytics    
            {
                ga('send', {
                  'hitType': 'event',
                  'eventCategory': category,
                  'eventAction': action, 
                  'eventLabel': opt_label,
                  'eventValue': opt_value
                });                 
            }
            else //use old analytics method
            {
                _gaq.push(
                    [
                        '_trackEvent',
                        category,
                        action,
                        opt_label,
                        opt_value,
                        opt_noninteraction
                    ]);
            }
        } else {
            console.log("Universal GA-Tracker: " +  typeof(ga));
            console.log("Old GA-Tracker: " +  typeof(_gaq));
            console.log("category: " + category);
            console.log("action: " + action);
            console.log("opt_label: " + opt_label);
            console.log("opt_value: " + opt_value);
        }
    };
    
    /**
     * Track Time On Site every 10 Seconds
     * @returns {undefined}
     */
    this.gaTrackTimeOnSite = function() {
        
        var scrollPos = self.getPageScroll();
        
        setInterval(function() {            
                scrollPosCurr = self.getPageScroll();                
                if(scrollPos[1] !== scrollPosCurr[1])
                {
                    scrollPos = scrollPosCurr;
                    
                    if(typeof(ga) != "undefined")
                    {
                        ga('send', 'event', 'NoBounce', 'Over 10 seconds');
                    }
                }
        },10000); 
    };
    
    /**
     * Returns the type of the client device as String.
     * @returns {String}
     */
    this.getDeviceType = function()
    {
        var deviceType = $('body').attr('hyb-device');
        
        if(!deviceType)
        {
            deviceType = 'desktop';
            console.log('Device could not be detected.');
        }
        
        return deviceType;
    }
    
    /**
     * Return the parts of the time like date and hours by converting a unix 
     * timestamp.
     * @param {number} timestamp
     * @returns {number[]}
     */
    this.getTimeObjects = function(unix_timestamp)
    { 
        // create a new javascript Date object based on the timestamp
        // multiplied by 1000 so that the argument is in milliseconds, not seconds
        var date = new Date(unix_timestamp*1000);
        
        var monthAsString = new Array("Januar", "Februar", "M&auml;rz", "April", "Mai", 
            "Juni", "Juli", "August", "September", "Oktober", "November", 
            "Dezember");
        
        var ret = {
            hours: date.getHours(),
            minutes: date.getMinutes(),
            seconds: date.getSeconds(),
            date: date.getDate(),
            fullYear: date.getFullYear(),
            monthAsString: monthAsString[date.getMonth()],
            month: date.getMonth() + 1,
            day: date.getDay(),
            milliseconds: date.getMilliseconds()
        }
        
        return ret;
    }
    
    /**
     * Checks if a given path is fully qualified.
     * @param {String} path
     * @returns {Bool}
     */
    this.filePathIsAbsolute = function(path)
    {
      return /^(?:[A-Za-z]:)?\\/.test(path);
    }   
    
    this.fileNameIsImage = function(pathToImg)
    {
        return /^\/[\w*\-_/]*\.(png|gif|jpg|jpeg)$/.test(pathToImg)
    }
    
    /**
     * Lazy load an Image
     * @param {Element img} imgElem
     * @returns {void}
     */
    this.lazyLoadImage = function(imgElem)
    {
        imgUrl = $(imgElem).attr('hyb-ll-src');
        alt = $(imgElem).attr('hyb-ll-alt');
        
        if(imgUrl)
        {
            $(imgElem).attr('src', imgUrl);
            $(imgElem).removeAttr('hyb-ll-src');
            
            $(imgElem).attr('alt', alt);            
            $(imgElem).removeAttr('hyb-ll-alt');
            
            //rest dimensions
            $(imgElem).attr('height', 'auto');
            $(imgElem).attr('width', 'auto');             
        }
    }
    
    /**
     * Defer an Image
     * @param {Element img} imgElem
     * @returns {void}
     */
    this.deferLoadImage = function(imgElem)
    {
        imgUrl = $(imgElem).attr('hyb-dl-src');
        alt = $(imgElem).attr('hyb-dl-alt');
        
        if(imgUrl)
        {
            $(imgElem).attr('src', imgUrl);
            $(imgElem).removeAttr('hyb-dl-src');
            
            $(imgElem).attr('alt', alt);            
            $(imgElem).removeAttr('hyb-dl-alt');
            
            //rest dimensions
            $(imgElem).attr('height', 'auto');
            $(imgElem).attr('width', 'auto');             
        }
    }   
    
    /**
     * spin.js has to be loaded to use the spinner
     * @returns {void}
     */
    this.printSpinner = function() {        
        if(typeof Spinner === 'function') {

            $('html').prepend('<div id="spinner_overlay" style="background:white; opacity:0.6; height:100%; width:100%; position:fixed; z-index:99;"></div>');
            var spinner = new Spinner().spin();
            $('html').append(spinner.el);

            var widthDocument = $(document).width();
            var heightDocument = $(document).height();

            $('.spinner').css('position', 'absolute');

            $('.spinner').css('top', heightDocument / 2 - 5);
            $('.spinner').css('left', widthDocument / 2 - 5);
        }

    };

    this.removeSpinner = function() {
        $('#spinner_overlay').remove();
        $('.spinner').remove();
    };    

};//end class globFunctions

//create instance
var objGlobFunc = new globFunctions();