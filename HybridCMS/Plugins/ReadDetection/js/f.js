function ReadDetection(rdReadyCallback)
{
    /**
     * Callback function that is called when the Readdetection has finished.
     * @type function
     */
    var mRDReadyCallback = rdReadyCallback;
   
    /**
     * Name of the section that is in focus
     * @type string
     */
    var mSectionName = null;
    
    /**
     * Time that has to pass till the section is approved.
     * @type @exp;window@call;setTimeout
     */
    var mTimeout;
    
    /**
     * Milliseconds of the timeouts
     * @type Number
     */
    var mSecTimeout = 30 * 1000;  
    
    /**
     * Milliseconds of the intervall to observe the sections
     * @type Number
     */
    var mSecInterval = 10 * 1000;
    
    /**
     * Intervall that checks if the mouse-cursor is moving or not.
     * If the cursor is not moving, the user has left the device alone.
     * @type @exp;window@call;setInterval
     */
    var mIntervalObserveCursor;
    
    /**
     * Intervall that checks the viewport. If the viewport is not moving,
     * the user has left the device.
     * @type @exp;window@call;setInterval
     */
    var mIntervalObserveViewPort;
        
    /**
     * Global functions
     * @type globFunctions
     */
    var mObjFunc = new globFunctions();
    
    /**
     * X and Y Position of the cursor
     * @type numeric
     */
    var mCursorPosX;
    var mCursorPosY;  
    
    /**
     * Current X and Y position of the cursor
     * @type numeric
     */
    var mCurrCursorPosX;
    var mCurrCursorPosY;
    
    /**
     * ScrollEvent
     * @type @call;$@call;scroll
     */
    var mScrollEvent;
    
    /**
     * Scroll position Y
     * @type Numeric
     */
    var mScrollPosY;
    
    /**
     * Classname of the element with the sectionName Attribut.
     * @type String
     */
    var mElemClass_SectionName;
    
    /**
     * Start ReadDetection
     * @returns {undefined}
     */
    this.detect = function()
    {
        //handle desktop devices
        if(mObjFunc.getDeviceType() === 'desktop')
        {
            registerMouseoverEvent_Section();
        }
        
        //handle mobile devices (table & phone)
        else 
        {
            registerViewPortEvent_Section();
        }
    };
    
    /**
     * Register mouseover event on all sections.
     * @returns {void}
     */
    function registerMouseoverEvent_Section()
    {
        cnt = 0;
                
        $('.' + mElemClass_SectionName).mouseover(function()
        {
            //get ReadDetection Section-Name
            var sectionName = $(this).attr('hyb-rd-section');

            //start readdetection
            if(sectionName && mSectionName !== sectionName)
            {                                        
                //remember currSectionName
                mSectionName = sectionName;

                //stop timer of old session
                stopTimer();
                startTimer(10000);
                observeCursor();

                console.log(++cnt + ": " + sectionName);
            }
        });
    }
    
    /**
     * Register ViewPort Event on all section
     * @returns {void}
     */
    function registerViewPortEvent_Section()
    {
        var cnt = 0;
        
        mScrollEvent = $(document).scroll(function(){
            var sectionName = 
                    $('.' + mElemClass_SectionName + ':in-viewport')
                        .attr('hyb-rd-section');
                
            //start readdetection
            if(sectionName && mSectionName !== sectionName)
            {                                        
                //remember currSectionName
                mSectionName = sectionName;

                //stop timer of old session
                stopTimer();
                startTimer(10000);
                observeViewPort();

                console.log(++cnt + ": " + sectionName);
            }
        });
    }
    
    /**
     * Unregister mouseover events on sections.
     * @returns {void}
     */
    function unregisterMouseoverEvent_Section()
    {
        $('.' + mElemClass_SectionName).off("mouseover");
    }
    
    /**
     * unregister ViewPort Events
     * @returns {void}
     */
    function unregisterViewPortEvent_Section()
    {
        $(mScrollEvent).off();
    }
        
    /**
     * Start the timer of n seconds to track if the user 
     * is interested in the section. 
     * @param {numeric} sec
     * @returns {void}
     */
    function startTimer()    
    {        
        //check if sectionName is set, otherwise it makes no sence to 
        // start the timer
        if(mSectionName)
        {
            mTimeout = window.setTimeout(readDetectionReady, mSecTimeout);     
        }        
    }
        
    /**
     * Function that is called when readDetection has finished.
     * @returns {void}
     */
    function readDetectionReady()
    {
        //call callback
        mRDReadyCallback(mSectionName);
        
        //cursor is pausing. 
        //Stop the timeout and the intervall.
        window.clearTimeout(mTimeout); 
        
        //stop mIntervalObserveCursor on desktop devices
        if(mIntervalObserveCursor)
        {
            window.clearInterval(mIntervalObserveCursor);
        }
        
        //stop mIntervalObserveViewPort on mobile devices
        if(mIntervalObserveViewPort)
        {
            window.clearInterval(mIntervalObserveViewPort);
        }
        
        unregisterMouseoverEvent_Section();
        unregisterViewPortEvent_Section();
    }
    
    /**
     * Check each n seconds if the viewport has changed. If the viewport 
     * hasnt changed, the user has left the device alone.
     * @returns {void}
     */
    function observeViewPort()
    {   
        //clear previous attached intervalls
        window.clearInterval(mIntervalObserveViewPort);
        
        mIntervalObserveViewPort = window.setInterval(function()
        {
            console.log("observeViewPort..");
            
            //get current scroll-position
            var scrollPos = mObjFunc.getPageScroll();
                
            if(mScrollPosY === scrollPos[1])   
            {
                //viewport has changed
                console.log("ViewPort is not changing.");
                
                //Stop the timeout and the intervall.
                window.clearTimeout(mTimeout); 
                window.clearInterval(mIntervalObserveViewPort);
                
                //unset mSectionName
                mSectionName = null;
                
            }
            else
            {
                //set new scroll height position
                mScrollPosY = scrollPos[1];
            }
                
        }, mSecInterval);
    }    
    
    /**
     * Check each n seconds if curser has moved. This function makes only 
     * sense on Desktop devices.
     * @returns {void}
     */
    function observeCursor()
    {
        //resiter mousemove event to get the current cursor positions
        registerMousemoveEvent();
        
        //clear previous attached intervalls
        window.clearInterval(mIntervalObserveCursor);
            
        mIntervalObserveCursor = window.setInterval(function()
        {   
            console.log("observeCursor..");
            if(
                (
                    mCurrCursorPosX > mCursorPosX - 10 
                    && 
                    mCurrCursorPosX < mCursorPosX + 10
                )               
                &&                
                (
                    mCurrCursorPosY > mCursorPosY - 10 
                    && 
                    mCurrCursorPosY < mCursorPosY + 10
                )
               )
            {
                
                console.log("Mouse is not moving.");
                
                //cursor is pausing. 
                //Stop the timeout and the intervall.
                window.clearTimeout(mTimeout); 
                window.clearInterval(mIntervalObserveCursor);
                
                //unset mSectionName
                mSectionName = null;
                
                //delete mousemove event
                $('.' + mElemClass_SectionName).off("mousemove");
            }
            else
            {                            
                //set new coordinates of the cursor
                mCurrCursorPosX = mCursorPosX;
                mCurrCursorPosY = mCursorPosY;
            }
            
        }, mSecInterval);
    }
    
    /**
     * saves the current cursor position
     * on every mousemovement
     * @returns {void}
     */
    function registerMousemoveEvent()
    {
        //register Event to get Cursor Position
        $(document).on("mousemove", function(event) 
        {
          mCurrCursorPosX = event.pageX;
          mCurrCursorPosY = event.pageY;
          
          if(!mCursorPosX && !mCursorPosY)
          {
              mCursorPosX = mCurrCursorPosX;
              mCursorPosY = mCurrCursorPosY;
          }
        });          
    }
    
    /**
     * Stop the timer
     * @returns {void}
     */
    function stopTimer()
    {
      window.clearTimeout(mTimeout);  
    }    
    
    
    /**
     * set the sectionname
     * @param {String} sectionName
     * @returns {void}
     */
    function setSectionName(sectionName)
    {
        if (mObjFunc.isAlphaNumeric(sectionName)) 
        {
            mSectionName = sectionName;
        }
    }
    
    /**
     * unsets the sectionName 
     * @returns {void}
     */
    function unsetSectionName()
    {
        mSectionName = null;
    }
    
    /**
     * Sets the classname of the sectionNames.
     * @param {String} elemClass
     * @returns {void}
     */
    this.setElemClass_SectionName = function(elemClass) 
    {
        if (elemClass) {
            mElemClass_SectionName = elemClass;
        } 
        else 
        {
            console.log
                ("function setElemClass_SectionName: elem is not valid.");
        }
    }; 
}
