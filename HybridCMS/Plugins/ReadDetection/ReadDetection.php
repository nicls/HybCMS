<?php

namespace HybridCMS\Plugins\ReadDetection;

/**
 * class ToTop
 *
 * @package ToTop
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ReadDetection extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * __construct
     * 
     */
    public function __construct() {

        //call parent constructor
        parent::__construct();

        /**
         * JS jQuery Viewport Plugin
         * 
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         * 6. $position
         * 7. $async
         */
        $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                        'viewport',
                        '/js/viewport.js',
                        5,
                        false,
                        true,
                        'footer',
                        false
        );            
        $this->addObjJSResource($objJSResource1);   
        
        /**
         * JS Readdetection
         * 
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         * 6. $position
         * 7. $async
         */
        $objJSResource2 = new \HybridCMS\Page\Resources\JSResource(
                        'readDetection',
                        '/HybridCMS/Plugins/ReadDetection/js/f.js',
                        6,
                        true,
                        true,
                        'footer',
                        true
        );            
        $this->addObjJSResource($objJSResource2);     
        
        /**
         * JS init Readdetection
         * 
         * 1. $resourceName
         * 2. $resourcePath
         * 3. $priority
         * 4. $minify
         * 5. $autoActivate
         * 6. $position
         * 7. $async
         */
        $objJSResource3 = new \HybridCMS\Page\Resources\JSResource(
                        'initReadDetection',
                        '/HybridCMS/Plugins/ReadDetection/js/init.js',
                        6,
                        true,
                        true,
                        'footer',
                        true
        );            
        $this->addObjJSResource($objJSResource3);         
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
        return $op;
       
    }    
}
?>
