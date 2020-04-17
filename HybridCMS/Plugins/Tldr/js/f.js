$(document).ready(function(){
    
    $('#hyb_tldr select').on('change', function(){
        
        var time = $(this).val();
        
        if(typeof objGlobFunc !== 'undefined') 
        {
            objGlobFunc.gaTrkEvent(
                    'TLDR', //category
                    time, //action
                    window.location.href, //opt_label
                    0, //opt_value
                    true //opt_noninteraction
                    );   
            
            objGlobFunc.printSpinner();
        }
        
        if(time === 'short') {
            $('.hyb_tldr_long').hide();
            $('.hyb_tldr_short').show();
        }
        else
        {
            $('.hyb_tldr_short').hide();
            $('.hyb_tldr_long').show();
        }
        
        window.setTimeout(function(){
            if(typeof objGlobFunc !== 'undefined') 
            {            
                objGlobFunc.removeSpinner();
            }
        }, 160);        
        
    });
    

});
