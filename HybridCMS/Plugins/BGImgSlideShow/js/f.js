/**
 * Class BGImgSlideShow
 * @returns {undefined}
 */
var BGImgSlideShow = function(elemBackground)
{
    /**
     * Object with usefull functions
     * @type globFunctions
     */
    var mObjGlobFunc = new globFunctions();
    
    /**
     * Collection of pathes to Background images.
     */
    var mArrPathToImgs = new Array();
    
    /**
     * Indicates the element to that the images are going to be assigned.
     * @type element
     */
    var mElemBackground = validateElemBackground(elemBackground);
    
    /**
     * Indicates the miliseconds of the interval of background changing.
     * @type Integer
     */
    var mInterval = 500;
    
    /**
     * Intervall Object
     * @type @exp;window@call;setInterval
     */
    var mIntervalTimer;
    
    /**
     * Adds a new Image to the collection of background-images.
     * @param {String} pathToImg is the relative path to a background-image
     * e.g. /my/path-to/image-2560x1440.png. Allowed are png-, jpg-, svg- and 
     * svgz-images
     * @returns {void}
     */
    this.addImg = function(pathToImg)
    {
        if(pathToImg.match(
                    /^(\/[A-Za-z0-9-_]+)+\.(png|jpg|svg|svgz)$/))
        {
            mArrPathToImgs.push(pathToImg);
        }
        else
        {
            console.log("Path to image is not valid: " + pathToImg);
        }
    }  
    
    /**
     * Start the slideshow. If interval = 0, then just a random image is used 
     * background-image and no slide-show will be started. Otherwise the image 
     * changes after interval miliseconds.
     * @param {type} interval indicates the milliseconds to pass after the next 
     * background image will be shown.
     * @returns {void}
     */
    this.start = function(interval)
    {        
        if(mArrPathToImgs.length == 0)
        {
            console.log('No Background-Images to select!');
            return;
        }       
        
        //shuffle images
        mArrPathToImgs = mObjGlobFunc.shuffleArray(mArrPathToImgs);
        
        //just use a static random image as background-image
        showNextBGImg();
 
        //start the slideshow
        if(interval > 0 && true == setInterval(interval)) 
        {
            mIntervalTimer = window.setInterval(function(){
                showNextBGImg();
            }, mInterval);
        }
    }
    
    /**
     * Assigns the next img from the bottom of the stack to mElemBackground
     * as background-image.
     * @returns {void}
     */
    function showNextBGImg()
    {
        if(!mElemBackground)
        {
            console.log('No Background-Image Element given!');
            return;
        }
        
        //get the first image from the bottom of the stack
        var tmpImgPath = mArrPathToImgs.shift();
        
        //add the imagePath back to the top of the stack
        mArrPathToImgs.push(tmpImgPath);
                              
        $(mElemBackground).attr('id', 'bgImgSlideShow');
        
        //set new background image
        $(mElemBackground).css('background-image', 
            'url(' + tmpImgPath + ')');
    }
    
     /**
     * Sets mInterval
     * @param {Integer} interval
     * @returns {Boolean}
     */
    function setInterval(interval)
    {
        if(true == mObjGlobFunc.isNumeric(interval)
           && 
           interval > 0)
        {
            mInterval = interval;
            return true;
        }
        return false;
    }
    
    function validateElemBackground(elem)
    {
        if(elem)
        {
            return elem;
        }
        else
        {
            console.log("Background Image Element is undefined!");
            return null;
        }
    }
}

/**
 * Init Plugin
 * #########################################
 * setup and start background image slideshow
 */
$(document).ready(function() {

    if($('body').width() > 767)
    {       
        var objBGImgSlideShow = new BGImgSlideShow($('body'));

        //add some background images
        objBGImgSlideShow.addImg("/HybridCMS/Plugins/BGImgSlideShow/"
            + "images/wildnis-2560x1440.png");
        objBGImgSlideShow.addImg("/HybridCMS/Plugins/BGImgSlideShow/"
            + "images/tiere-savanne-2560x1440.png");
        objBGImgSlideShow.addImg("/HybridCMS/Plugins/BGImgSlideShow/"
            + "images/giraffen-savanne-2560x1440.png");

        //start the slideshow
        objBGImgSlideShow.start(1000 * 60 * 1);
    }
    
}); //end document.ready
