<?php

namespace HybridCMS\Plugins\AmazonPrices;

/**
 * class AmazonPrices
 *
 * @package AmazonPrices
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 * @version 0.0.1
 */
class AmazonPrices extends \HybridCMS\Plugins\Plugin\Plugin 
{    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() 
    {
        
        try 
        {            
            //call constructor of parent class
            parent::__construct();

            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource1 = 
                new \HybridCMS\Page\Resources\JSResource(
                    'AmazonPricesAPI',
                    'https://www.gstatic.com/charts/loader.js',
                    18,
                    false,
                    true,
                    'footer',
                    false);            
            $this->addObjJSResource($objJSResource1);     
            
            
            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource2 = 
                new \HybridCMS\Page\Resources\JSResource(
                    'AmazonPricesInit',
                    '/HybridCMS/Plugins/AmazonPrices/js/f.js',
                    19,
                    true,
                    true,
                    'footer',
                    true);            
            $this->addObjJSResource($objJSResource2);     
                              
        } 
        catch (Exception $e) 
        {           
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }   
    }    
}
?>
