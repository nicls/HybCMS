$(function(){

    var currentValue = $('body').css('font-size');
    $('#hyb_fontSizeSlider').attr('value', currentValue.replace('px', ''));
    
    
    //initial set sizes
    fontChangedCallback(currentValue.replace('px', ''));    

    $('#hyb_fontSizeSlider').change(function(){
        var fontSize = this.value;
        
        $('body').css('font-size', fontSize + 'px');
        
        try {
            
            //call callback function
            if(typeof(fontChangedCallback) === "function") {
                fontChangedCallback(fontSize);
            }
            
        } catch (e) {
            console.log(e);
        }        
    });
});

/**
 * fontChangedCallback
 * @param {Integer} fontSize
 * @returns {void}
 */
function fontChangedCallback(fontSize) {
    console.log('FontChanged: ' + fontSize);
}