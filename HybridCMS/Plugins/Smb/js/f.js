/**
 * Javascript file for Social Media Buttons
 * @returns {void}
 */
var Smb = function()
{          
   /**
    * Sharedata API
    * @type String
    */
   var mJsonBaseUrl = "/HybridCMS/Modules/sm/index.php?url=";
      
   /**
    * Url to share
    * @type @exp;document@pro;location@pro;href
    */
   var mShareUrl = window.location.href.split('#')[0];   
   
   /**
    * Number of total Shares on facebook
    * @type Integer
    */
   var mSharesFacebook = 0;
   
   /**
    * Number of total Shares on Twitter
    * @type Integer
    */
   var mSharesTwitter = 0;
   
   /**
    * Number of shares on googleplus
    * @type Integer
    */
   var mSharesGoogleplus = 0;
   
   /**
    * Number of total shares
    * @type Integer
    */
   var mSharesTotal = 0;
   
   /**
    * ClassName of the Facebook Button
    * @type String
    */
   var mElemClass_FacebookBtn = 'hyb_facebookBtn';
   
   /**
    * ClassName of the Twitter Button
    * @type String
    */
   var mElemClass_TwitterBtn = 'hyb_twitterBtn';   
   
   /**
    * ClassName of the Googleplus Button
    * @type String
    */
   var mElemClass_GoogleplusBtn = 'hyb_googleplusBtn';  
   
   /**
    * ClassName of the TotalShares Display
    * @type String
    */
   var mElemClass_TotalShares = 'hyb_totalShares';  
         
   /**
    * Init function
    * @returns {void}
    */
   this.init = function() 
   {                     
        //get number of shares
        jsonRequest(mJsonBaseUrl + mShareUrl);                   
   };
            
   
    /**
     * Json Request to third party
     * @param objData - data so submit per ajax
     * @param url - url to submit the request to
     * @param callback - callackfunction
     */
    function jsonRequest(url) {
                
        $.ajax({
            type: "GET",
            dataType: "json",
            url: url
        }).success(function(jsonResponse, textStatus) {
                     
            if(jsonResponse) 
            {
                fitFacebookBtn(jsonResponse);
                fitGoogleplusBtn(jsonResponse);
                fitTwitterBtn(jsonResponse);
            }
            
        }).error(function(msg, textStatus) {
            
            console.log("Error sending jsonp-Request.");
            
        }).done(function(msg) {     
                        
        });
    }; 
       
    
    /**
     * Insert Shares into the facebook Button
     * @param {object} shares
     * @returns {void}
     */
    function fitFacebookBtn(shares)
    {      
        var spanShares = $('.' + mElemClass_FacebookBtn 
                + ' span.hyb_shares');
        
        if('undefined' === typeof(spanShares)) { return; }            
        if('undefined' === typeof(shares.facebook)) { return; }
        if(shares.facebook == '0') { return; } 
        
        //update facebook shares
        mSharesFacebook = shares.facebook;

        //update total shares
        mSharesTotal += mSharesFacebook; 
        showTotalShares();        
        
        var msgType = (mSharesFacebook === 1) ? " Like" : " Likes";
        
        $(spanShares).hide();         
        $(spanShares).text(" " + mSharesFacebook + msgType);
        $(spanShares).fadeIn();         
    }
    
    /**
     * Insert Shares into the twitter Button
     * @param {object} shares
     * @returns {void}
     */
    function fitTwitterBtn(shares)
    {            
        var spanShares = $('.' + mElemClass_TwitterBtn 
                + ' span.hyb_shares');   
        
        if('undefined' === typeof(spanShares)) { return; }            
        if('undefined' === typeof(shares.twitter)) { return; }   
        if(shares.twitter == '0') { return; } 
        
        //update Twitter shares
        mSharesTwitter = shares.twitter;

        //update total shares
        mSharesTotal += mSharesTwitter;     
        showTotalShares();
        
        var msgType = (mSharesTwitter === 1) ? " Tweet" : " Tweets";
        
        $(spanShares).hide();
        $(spanShares).text(" " + mSharesTwitter + msgType);
        $(spanShares).fadeIn();        
    }  
    
    /**
     * Insert Shares into the Googleplus Button
     * @param {object} shares
     * @returns {void}
     */
    function fitGoogleplusBtn(shares)
    {   
        var spanShares = $('.' + mElemClass_GoogleplusBtn 
                + ' span.hyb_shares');
        
        if('undefined' === typeof spanShares) { return; }            
        if('undefined' === typeof shares.googleplus) { return; }
        if(shares.googleplus == '0') { return; } 
            
        //update google Plus shares
        mSharesGoogleplus = shares.googleplus;

        //update total shares
        mSharesTotal += mSharesGoogleplus;
        showTotalShares();
        
        var msgType = (mSharesGoogleplus == 1) ? " Share" : " Shares";
        
        $(spanShares).hide();         
        $(spanShares).text(" " + mSharesGoogleplus + msgType);
        $(spanShares).fadeIn();        
    } 
    
    /**
     * Show sum of all sahres
     * @returns {void}
     */
    function showTotalShares() 
    {
        if(mSharesTotal > 0) {
            
            var spanTotalShares = document.getElementsByClassName(mElemClass_TotalShares);
            if(spanTotalShares)
            {
                $(spanTotalShares).text(mSharesTotal 
                        + " Shares bis jetzt - Dankeschön!");
                $(spanTotalShares).css('display', 'block');
            }
        }
    };
};

/**
 * Init Plugin
 * #########################################
 */
$(document).ready(function(){
        
   var objSmb = new Smb();
   objSmb.init();
});