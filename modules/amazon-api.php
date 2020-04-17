<?php
/** include hybCMSLoader
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/hybCMSLoader.php');

try { //configure page

    /* Page Setup
      ================================================= */
    $hcms->setupPage('title', array('title' => 'Module CDS - Mobile First', 'prepend' => ' - clarotool.de'));
    $hcms->setupPage('description', array('description' => 'Das ist eine Testdescription'));
    $hcms->setupPage('canonical', array('canonical' => 'http://hybcms.de'));

    /* get Articles 
      ==================================================== */

    /* load Plugins
      ================================================== */
    //Breadcrumb-Plugin
    $objBreadcrumb = new \HybridCMS\Plugins\Breadcrumb\Breadcrumb(HYB_CURRURL);


    /* add asideWidgets
      =================================================== */
    $arrParams = array('numberOfResults' => 5, 'catName' => 'catzwei');
    $objWidgetLatestArticles = new \HybridCMS\AsideWidgets\WidgetLatestArticles('latestArticles', 'sidebar', 1, $arrParams);
    $hcms->addAsideWidget($objWidgetLatestArticles);
    
} catch (\Exception $e) {

    //Log Error
    $objLogger = new \HybridCMS\Helper\KLogger(
            LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
    $objLogger->logError($e->__toString() . "\n");
}

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/header.php');

/* include Header
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/HybridCMS/Modules/aws_signed_request.php');
?>

<!-- Primary Page Layout
================================================== -->
<div class="container">
    <div class="row">
        <article class="col-xs-12 col-md-7" id="mainContent">
            <h1>Amazon-API</h1>
            
            <?php
                $public_key = '';
                $private_key = '';
                $associate_tag = '';

                // generate signed URL
                $request = aws_signed_request('de', array(
                        'Operation' => 'ItemLookup',
                        'ItemId' => 'B015SECMSU',
                        'ResponseGroup' => 'Large'), 
                        $public_key, 
                        $private_key, 
                        $associate_tag);
                
                try 
                {
                
                    // do request (you could also use curl etc.)
                    $response = file_get_contents($request);
                    
                    echo "<pre>";
                    print_r($response);
                    echo "</pre>";                    

                    if ($response === FALSE) 
                    {
                        echo "Request failed.\n";
                    } 
                    else 
                    {

                        echo "Request succesful.\n";

                        // parse XML
                        $pxml = simplexml_load_string($response);
                        
                        echo "<pre>";
                        print_r($pxml);
                        echo "</pre>";

                        if ($pxml === FALSE) 
                        {
                            echo "Response could not be parsed.\n";
                        } 
                        else 
                        {
                            
                            //get Sale Price if Available
                            if(true === isset($pxml->Items->Item->Offers->Offer->OfferListing->SalePrice->Amount)) 
                            {                
                                echo '(int)$pxml->Items->Item->Offers->Offer->OfferListing->SalePrice->Amount: ' . 
                                        (int)$pxml->Items->Item->Offers->Offer->OfferListing->SalePrice->Amount . "<br/>";                 
                            }                     

                            //get Offer Price
                            if(true === isset($pxml->Items->Item->Offers->Offer->OfferListing->Price->Amount)) 
                            {                
                                echo '$pxml->Items->Item->Offers->Offer->OfferListing->Price->Amount: ' .
                                        (int)$pxml->Items->Item->Offers->Offer->OfferListing->Price->Amount . "<br/>";                    
                            }                    

                            //get third Party Price if Available
                            if(true === isset($pxml->Items->Item->OfferSummary->LowestNewPrice->Amount)) 
                            {                
                                echo '$pxml->Items->Item->OfferSummary->LowestNewPrice->Amount: ' . 
                                        (int)$pxml->Items->Item->OfferSummary->LowestNewPrice->Amount . "<br/>";                
                            }                             
                        }
                    }
                } 
                catch (\Exception $e) 
                {
                    echo $e;
                }
            ?>
                 
        </article>

        <?php
        /* include Footer
         * ================================================== */
        require_once($_SERVER['DOCUMENT_ROOT'] . '/sidebar.php');
        ?>  
    </div><!-- end section.row -->
</div><!-- end div.container -->

<?php
/* include Footer
  ================================================== */
require_once($_SERVER['DOCUMENT_ROOT'] . '/footer.php');
?>