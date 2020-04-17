/**
 * Often needed functions for the admin area
 * @returns void
 */
var globFunctions = function() {

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
            url: "/HybridCMS/Ajax/api/admin.php",
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
            dataType: "json",
            url: "/HybridCMS/Ajax/api/admin.php",
            data: objData,
            type: "POST",
            success: function(jsonResponse, textStatus, jqXHR) {
                //call callbackfunction
                callback(jsonResponse, elemUserResponseAjax);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log("Fehler beim JsonRequest!");
            }
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
     * validateEmail
     * @param {String} email
     * @returns {Boolean}
     */
    this.validateEmail = function(email) {
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(email);
    };

    /**
     * timeConverter - formats a unix timestamp 
     * @param {Integer} UNIX_timestamp
     * @returns {String}
     */
    this.timeConverter = function(UNIX_timestamp) {
        var a = new Date(UNIX_timestamp * 1000);
        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        var year = a.getFullYear();
        var month = months[a.getMonth()];
        var date = a.getDate();
        var hour = a.getHours();
        var min = a.getMinutes();
        var sec = a.getSeconds();
        var time = date + ',' + month + ' ' + year + ' ' + hour + ':' + min + ':' + sec;
        return time;
    };

    /**
     * Checks if n is numeric
     * @param {mixed} n
     * @returns {boolean}
     */
    this.isNumeric = function(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    };

}//end class globFunctions


/**
 * ajaxRequest
 *
 * @param objData - data so submit per ajax
 * @param callback - callackfunction
 * @param element - DOM-Element that will be passed throu the callback
 */
function ajaxRequest(objData, callback, element) {

    $.ajax({
        type: "POST",
        url: "/HybridCMS/Ajax/api/admin.php",
        data: objData
    }).done(function(msg) {

        //call callbackfunction
        callback(msg, element);
    });
}

//validate URL
function validateUrl(url) {
    var regex = new RegExp("^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,10}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\.\,_\?\'/\\\+&%\$#\=~;])*$");
    return regex.test(url);
}

//validate cssId
function validateCSSId(cssId) {
    var regex = /^[a-zA-Z0-9_\-]{1,60}$/;
    return regex.test(cssId);
}