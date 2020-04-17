<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

/* include Amazon API Client
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Modules/aws_signed_request.php');
?>

             
<?php

    $arrResults = array();
    $emailErrors = "claaskalwa@yahoo.de";

    $public_key = 'AKIAJ6VBBVH66RDR7IWA';
    $private_key = 'cAjRgwvBiuNqKE6pIN/NC0YwEYKsTu6PG+ml6S2o';
    $associate_tag = 'wk09234-21';
    
    $comptableName = "Wildkameras";

    $objComptable = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);
    $objComptable->fetchComptable();
    $arrObjTables = $objComptable->getArrObjTables();
    $arrObjTablesActive = array();
    $csvItemIds = "";
    $cnt = 0;    
    
    foreach ($arrObjTables as $objTable) 
    {      
        $cnt++;                
            
        //extract the ItemId
        preg_match("/\/dp\/(.*)$/", $objTable->getUrl(), $arrMatches);        
        if(false === isset($arrMatches[1])) { exit(); }
        
        $itemId =  $arrMatches[1];
        $arrResults[$itemId]['tableName'] = $objTable->getTableName();
        $arrResults[$itemId]['objTable'] = $objTable;
        $csvItemIds .= $itemId . ',';         
        
        //do first or another request of of ASINs
        if(($cnt%10) === 0 || count($arrObjTablesActive) === $cnt) 
        {                    
            // generate signed URL
            $request = aws_signed_request('de', array(
                'Operation' => 'ItemLookup',
                'ItemId' => $csvItemIds,
                'ResponseGroup' => 'Large'), 
                $public_key, 
                $private_key, 
                $associate_tag);   
            
            //reset csvItemIds for the next request
            $csvItemIds = "";

            try 
            {                      
                // do request (you could also use curl etc.)
                $response = doRequest($request);
                if($response === FALSE) { exit('Request failed!'); } 

                // parse XML
                $pxml = simplexml_load_string($response);
                if ($pxml === FALSE) { exit('Response could not be parsed.'); } 
                #echo "<pre>"; print_r($pxml); echo "</pre>";

                //iterate all items 
                foreach($pxml->Items->Item as $pxmlItem) 
                {

                    $currItemId = (string)$pxmlItem->ASIN;
                    $arrResults[$currItemId]['itemId'] = $currItemId;
                    
                    //check if offer is available
                    if(true == isset($pxmlItem->Offers->TotalOffers)) 
                    {
                        if(0 == (int)$pxmlItem->Offers->TotalOffers)
                        {
                            $arrResults[$currItemId]['available'] = 0;
                        } 
                        else 
                        {
                            $arrResults[$currItemId]['available'] = 1;
                        }
                    }

                    //get Offer Price
                    if(true === isset($pxmlItem->Offers->Offer->OfferListing->Price->Amount)) 
                    {                
                        $arrResults[$currItemId]["offerprice"] =
                                (int)$pxmlItem->Offers->Offer->OfferListing->Price->Amount;                
                    } 
                                        
                }
            } 
            catch (\Exception $e) 
            {
                echo $e;
            }
        }
    }        
    
    echo "Request performed: " . count($arrResults) . ' of ' . count($arrObjTables) . '<br/>';      
        
    try 
    {
        //open Database-Connection
        $db = \HybridCMS\Database\DatabaseFactory::
                getFactory()->getConnection();
        
        //update prices
        foreach($arrResults as $arrResult) 
        {
            if(true === isset($arrResult['offerprice']) && false === empty($arrResult['offerprice'])) 
            {                     
                $priceAPI = (float)$arrResult['offerprice']/100;
                
                //database-object to operate on Tables
                $objDBAmazonPrices = new \HybridCMS\Plugins\AmazonPrices\Database\DBAmazonPrices();

                $objTable = $arrResult['objTable'];
                $tablename = $objTable->getTableName();
                
                $prodname = preg_replace("/[^a-zA-Z0-9]+/", "", $tablename);
                
                echo $prodname . ": " . $priceAPI . "</br>";

                $success = $objDBAmazonPrices->insertNewPrice($db, $prodname, $priceAPI);

                if(false === $success) 
                {
                    $msg = "Preis für ". $objTable->getTableName() ." konnte nicht gespeichert werden!!</br>";                        
                    #mail($emailErrors, 'Cron ' .  HYB_HOST_NAME, $msg);
                }               
            }
        }                             
        
        //close Database-Connection
        \HybridCMS\Database\DatabaseFactory
        ::getFactory()->closeConnection(); 
    } 
    catch(\Exception $e) 
    {
        //close Database-Connection
        \HybridCMS\Database\DatabaseFactory
        ::getFactory()->closeConnection(); 
        
        echo $e;
    }    
    
    /**
     * Execute CURL-Request to Amazon API
     * @param String $request
     */
    function doRequest($request) 
    {            
        $headers = array('Accept: text/xml', 'Content-Type: text/xml');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $request,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER => $headers
            )
        );

        $result = curl_exec($curl);
        return $result;
    }
?>
             
