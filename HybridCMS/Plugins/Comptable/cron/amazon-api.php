<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

/* include Amazon API Client
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Modules/aws_signed_request.php');
?>

             
<?php
    
    $comptableName = "Wildkameras";
    $datasetKeyPrice = 'Preis ca.';
    $datasetKeyRatings = 'Bewertung bei Amazon';
    $arrResults = array();
    $emailErrors = "";

    $public_key = '';
    $private_key = '';
    $associate_tag = '';
    
    $objComptable = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);
    $objComptable->fetchComptable();
    $arrObjTables = $objComptable->getArrObjTables();
    $arrObjTablesActive = array();
    $csvItemIds = "";
    $cnt = 0;
    
    //indicates the hour when to update the ratings to prevent to mutch 
    //requests on a day
    $hourUpdateRatings = intval(date('G'))-1 === 16 
                       || 
                       intval(date('G')) === 16 
                       || 
                       intval(date('G'))+1 === 16;
    
    //ensures that the rating gets updated on sunday
    $dayUpdateRating = intval(date('N'))%2 !== 0;
    
    foreach ($arrObjTables as $objTable) 
    { 
        //add if the current table is active
        if(true === $objTable->getIsActive()) {
            $arrObjTablesActive[] = $objTable;
            //break;
        }
    }    
    
    foreach ($arrObjTablesActive as $objTable) 
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
                    
                    //get rating             
                    if($hourUpdateRatings && $dayUpdateRating)
                    {
                        if(true === isset($pxmlItem->CustomerReviews, $pxmlItem->CustomerReviews->HasReviews)) 
                        {                

                            $arrResults[$currItemId]["NumberReviews"] = 'noch keine'; 

                            if($pxmlItem->CustomerReviews->HasReviews == 'true')
                            {
                                $urlReviews = $pxmlItem->CustomerReviews->IFrameURL;
                                $contentReviews = file_get_contents($urlReviews);

                                if(false !== $contentReviews) 
                                {
                                    //get number of ratings
                                    $patternNumRatings = "/(\d{1,4})\sKundenrezension/";
                                    $patternNumStars = "/([1-5]\.[0-9])\svon\s5\sSternen/";

                                    preg_match($patternNumRatings, $contentReviews, $arrMatchesNumRatings); 
                                    preg_match($patternNumStars, $contentReviews, $arrMatchesNumStars); 
                                    #echo "<pre>"; print_r($arrMatchesNumStars); echo "</pre>";

                                    if(true === isset($arrMatchesNumRatings[1], $patternNumStars[1])) 
                                    { 
                                       $tmpStars = str_replace('.', ',', $arrMatchesNumStars[1]);
                                       $tmpRatings = $arrMatchesNumRatings[1] === '1' ? 
                                               $arrMatchesNumRatings[1] . " Bewertung" : 
                                               $arrMatchesNumRatings[1] . " Bewertungen";

                                       $numberReviews = $tmpStars . " von 5 (" . $tmpRatings . ")";
                                       $arrResults[$currItemId]["NumberReviews"] = $numberReviews;

                                    }
                                    else 
                                    {
                                        $msg = "Rating für ". $objTable->getTableName() ." konnte nicht extrahiert werden!!</br>";                        
                                        mail($emailErrors, 'Cron ' .  HYB_HOST_NAME . ' Amzn Ratings', $msg);
                                    }

                                    #echo "<h1>Anzahl Reviews: </h1><pre>"; print_r($arrResults[$currItemId]["NumberReviews"]); echo "</pre>";
                                }
                            }
                        }
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

        //get Database object
        $objDBComptable = new \HybridCMS\Plugins\Comptable\
                Database\DBComptable();
        
        //update prices
        foreach($arrResults as $arrResult) 
        {
            if(true === isset($arrResult['available'])
               &&
               1 == $arrResult['available'] 
               && 
               true === isset($arrResult['offerprice'])) 
            {

                //compare prices
                $objTable = $arrResult['objTable'];
                $priceDB = (int)$objTable->getValueOfDateset($datasetKeyPrice);
                $priceAPI = (int)round($arrResult['offerprice']/100);

                if($priceDB !== $priceAPI) 
                {
                    $objComptableTmp = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);
                    $objTableTmp = new \HybridCMS\Plugins\Comptable\Table($objTable->getTableName());
                    $objDatasetTmp = new \HybridCMS\Plugins\Comptable\Dataset($datasetKeyPrice, $priceAPI . ' €');
                    $objTableTmp->addDataset($objDatasetTmp);
                    $objComptableTmp->addTable($objTableTmp);
                    $affectedRows = $objDBComptable->updateValueOnDataset($db, $objComptableTmp);

                    if(1 !== $affectedRows) 
                    {
                        $msg = "Datensatz für ". $objTable->getTableName() ." konnte nicht geändert werden!!</br>";                        
                        mail($emailErrors, 'Cron ' .  HYB_HOST_NAME, $msg);
                    }
                    else 
                    {
                        echo $objTable->getTableName() . " von " . $priceDB . " auf " . $priceAPI . " geändert.</br>";
                    }
                }
            } 
            else 
            {
                //Cam is not available
                $msg = $arrResult['tableName'] . " ist nicht mehr verfügbar.";
                mail($emailErrors, 'Cron ' .  HYB_HOST_NAME, $msg);
                #$arrResult['objTable'] = '';
                #echo "<pre>"; print_r($arrResult); echo "</pre>";
                
            }
        }
            
        //update ratings
        foreach($arrResults as $arrResult) 
        {
            if(true === isset($arrResult['NumberReviews']))
            {
                //compare ratings
                $objTable = $arrResult['objTable'];
                $ratingsDB = $objTable->getValueOfDateset($datasetKeyRatings);
                $ratingsAPI = $arrResult['NumberReviews'];

                if($ratingsDB !== $ratingsAPI) 
                {
                    $objComptableTmp = new \HybridCMS\Plugins\Comptable\Comptable($comptableName);
                    $objTableTmp = new \HybridCMS\Plugins\Comptable\Table($objTable->getTableName());
                    $objDatasetTmp = new \HybridCMS\Plugins\Comptable\Dataset($datasetKeyRatings, $ratingsAPI);
                    $objTableTmp->addDataset($objDatasetTmp);
                    $objComptableTmp->addTable($objTableTmp);
                    $affectedRows = $objDBComptable->updateValueOnDataset($db, $objComptableTmp);

                    if(1 !== $affectedRows) 
                    {
                        $msg = "DB Fehler: Rating-Datensatz für ". $objTable->getTableName() ." konnte nicht geändert werden!!</br>";                        
                        mail($emailErrors, 'Cron ' .  HYB_HOST_NAME, $msg);
                    }
                    else 
                    {
                        echo $objTable->getTableName() . " von " . $ratingsDB . " auf " . $ratingsAPI . " geändert.</br>";
                    }
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
             