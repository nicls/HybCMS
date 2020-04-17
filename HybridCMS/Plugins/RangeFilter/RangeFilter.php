<?php

namespace HybridCMS\Plugins\RangeFilter;

/**
 * class RangeFilter
 *
 * @package Plugins
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class RangeFilter extends \HybridCMS\Plugins\Plugin\Plugin 
{      
    /**
     * __construct
     * 
     */
    public function __construct() {

        try {

            //call parent constructor
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
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                    'rangeFilter', 
                    '/HybridCMS/Plugins/RangeFilter/js/f.js', 
                    10, 
                    false, 
                    true, 
                    'footer', 
                    false
            );
            $this->addObjJSResource($objJSResource);
            
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
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                    'rangeFilterInit', 
                    '/HybridCMS/Plugins/RangeFilter/js/init.js', 
                    11, 
                    false, 
                    true, 
                    'footer', 
                    false
            );
            $this->addObjJSResource($objJSResource);  
            
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
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                    'spinner', '/js/spin.js', 8, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource);            

            /**
             * CSS
             *
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             */
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    'fontSize', //1
                    '/HybridCMS/Plugins/RangeFilter/css/f.css', //2
                    4, //3
                    true, //4
                    true //5
            );
            $this->addObjCSSResource($objCSSResource);
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) 
    {
        return '';
    }

}

?>
