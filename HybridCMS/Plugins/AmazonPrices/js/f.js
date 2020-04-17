$(document).ready(function() 
{    
        //Print Chart
    function callbackPrices(jsonResponse, elemUserResponseAjax) 
    {        
        if(1 === jsonResponse.length) 
        { 
            $("#" + elemUserResponseAjax).parent().remove();
            return; 
        }
        
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.arrayToDataTable(jsonResponse);
        
        var title = "Preisentwicklung";        
        if($("#"+ elemUserResponseAjax).attr("data-title"))
        {
            title = $("#" + elemUserResponseAjax).attr("data-title");
        }
        
        var options = {
            title: title,
            curveType: 'function',
            legend: { position: 'bottom' },
            hAxis: {
              title: 'Tage'
            },
            vAxis: {
              title: 'Preis in Euro'
            },          
            fontName: "Open Sans Condensed"
        };        

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById(elemUserResponseAjax));
        chart.draw(data, options);
    } 
    
    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(function(){       
        
        var arrChartContainer = $('.chartAmazonPrices');

        for(var i=0; i<arrChartContainer.length; i++)
        {    

            var prodname = $(arrChartContainer[i]).attr('id');

            var objData = {
                plugin: 'AmazonPrices',
                prodname: prodname,
                action: "getProdPrices"
            }

            if(typeof objGlobFunc != 'undefined')
            {
                objGlobFunc.jsonRequest(objData, callbackPrices, prodname);    
            }
            else
            {
                console.log('GlobFunc undefined!');
            }
        } 
        
    });   
});    
