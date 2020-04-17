<?php

namespace HybridCMS\Plugins\News\Admin;

/**
 * class News
 *
 * @package News
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class Admin extends \HybridCMS\Plugins\Plugins {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct() {
        
        try {
            
            //call constructor of parent class
            parent::__construct();

            //Add JSResources
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                            'news',
                            '/HybridCMS/Plugins/News/js/f.js',
                            3,
                            true,
                            true,
                            'footer',
                            true
            );
            $this->addObjJSResource($objJSResource);

            //Add CSSResource
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                            'news',
                            '/HybridCMS/Plugins/News/css/f.css');
            $this->addObjCSSResource($objCSSResource);            
        
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }   
    }
}

?>