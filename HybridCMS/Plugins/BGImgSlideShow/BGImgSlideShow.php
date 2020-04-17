<?php

namespace HybridCMS\Plugins\BGImgSlideShow;

/**
 * class BGImgSlideShow
 *
 * @package BGImgSlideShow
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 * @version 0.0.1
 */
class BGImgSlideShow extends \HybridCMS\Plugins\Plugin\Plugin 
{    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
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
                    'bgImgSlideShow',
                    '/HybridCMS/Plugins/BGImgSlideShow/js/f.js',
                    10,
                    false,
                    true,
                    'footer',
                    true);            
            $this->addObjJSResource($objJSResource1);                      

            /**
             * CSS
             *
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             */
            $objCSSResource = 
                new \HybridCMS\Page\Resources\CSSResource(
                    'bgImgSlideShow',
                    '/HybridCMS/Plugins/BGImgSlideShow/css/f.css');
            $this->addObjCSSResource($objCSSResource);                    
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