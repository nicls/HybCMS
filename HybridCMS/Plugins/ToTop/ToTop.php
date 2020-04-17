<?php

namespace HybridCMS\Plugins\ToTop;

/**
 * class ToTop
 *
 * @package ToTop
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class ToTop extends \HybridCMS\Plugins\Plugin\Plugin {
    
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
                            'toTop',
                            '/HybridCMS/Plugins/ToTop/js/f.js',
                            5,
                            true,
                            true,
                            'footer',
                            true
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
                    'toTop', //1
                    '/HybridCMS/Plugins/ToTop/css/f.css', //2
                    4, //3
                    false, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource);            
            
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
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
    public function toString($args = array()) {

        //output-String
        $op = '';        
        $op .= '<span id="toTopContainer">';
        $op .= '<div id="toTop"><a href="#top"><i class="fa fa-chevron-circle-up"></i></a></div>';
        $op .= '</span>';
        
        return $op;
       
    }    
}
?>
