$(document).ready(function(){

    //lazy load images
    objGlobFunc = new globFunctions();              
            
    if(objGlobFunc)
    {
        //initial show loading gif to the users
        $('img[hyb-ll-src]').attr('src', '/images/ajax-loader-32x32.gif');
        $('img[hyb-ll-src]').attr('height', '32');
        $('img[hyb-ll-src]').attr('width', '32');
        $('img[hyb-ll-src]').addClass('center');
        
        $('img[hyb-dl-src]').attr('src', '/images/ajax-loader-32x32.gif');
        $('img[hyb-dl-src]').attr('height', '32');
        $('img[hyb-dl-src]').attr('width', '32');
        $('img[hyb-dl-src]').addClass('center');
        
        //lazy load images that are in viewport at pageload
        $("img[hyb-ll-src]:in-viewport").each(function() 
        {
            objGlobFunc.lazyLoadImage(this);
        }); 
        
        //load defered Images
        $("img[hyb-dl-src]").each(function() {
            objGlobFunc.deferLoadImage(this);
        });
    
        //lazy load images that scroll into viewport
        $(window).bind("scroll", function(event) 
        {
            $("img[hyb-ll-src]:in-viewport").each(function() 
            {               
                objGlobFunc.lazyLoadImage(this);               
            });       
        });
    }
});

//Magnific Popup
$(document).ready(function() {
    $('.lightbox-gallery').magnificPopup({ 
        type: 'image',
        closeOnContentClick: false,
        gallery: {
            enabled: false, // set to true to enable gallery

            preload: [0,2], // read about this option in next Lazy-loading section

            navigateByImgClick: true,

            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button

            tPrev: 'Previous (Left arrow key)', // title for left button
            tNext: 'Next (Right arrow key)', // title for right button
            tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
          }

    });
});

$(document).ready(function(){
    
    //smooth scrolling when clicking an anchor link
    $("a[href*=#]").click(function(){
        
        //prevent from sliding-action of bootstrap accordion anchors
        if($(this).attr('data-toggle'))
        {
            return true;
        }
            
        $('html, body').animate({
            scrollTop: $('[name="' + $.attr(this, 'href').substr(1) + '"]').offset().top
        }, 500);
        return true;
    });   
});

