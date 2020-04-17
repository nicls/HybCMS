jQuery(document).ready(function () {        
    $("#aggregateRating").on("rating.change", function(event, value, caption) {
        
        if("undefined" === typeof objGlobFunc)
        {
            var objGlobFunc = new globFunctions();
        }         
                        
        //refresh ratingplugin
        $("#aggregateRating").rating("refresh", {
            disabled: true, showClear: false});
        
        //get data values
        var maxRatingPoints =  $("#aggregateRating").attr("data-max");
        var idBox = $("#aggregateRating").attr("data-idBox");
        
        var urlOk = objGlobFunc.validateUrl(idBox);
        
        if(true === urlOk)
        {            
            objData = {
                idBox: idBox,
                rate: value,
                plugin: 'rating',
                action: 'updateRating',
                maxRatingPoints: maxRatingPoints
            }; 
            
            objGlobFunc.gaTrkEvent(
                    'Rating', //category
                    value, //action
                    window.location.href, //opt_label
                    0, //opt_value
                    true //opt_noninteraction
                    );   

            /**
             * Callback updateRating
             * @param {string} msg
             * @param {element} elemUserResponseAjax
             * @returns {void}
             */
            function callbackUpdateRating(msg, elemUserResponseAjax) {                           
                //add thank you text
                var thankyoutext =  $(elemUserResponseAjax).attr("data-thankyoutext");
                if(thankyoutext) $(".rating-thankyoutext").text(thankyoutext);
            }

            objGlobFunc.ajaxRequest(objData, callbackUpdateRating, $("#aggregateRating"));
        }
        
    });

});