<?php

namespace HybridCMS\Plugins\Comptable\Admin;

/**
 * class Comptable
 *
 * @package Comptable
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
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
                            'bookmarks',
                            '/HybridCMS/Plugins/Comptable/js/bookmarks.js',
                            3,
                            true,
                            true,
                            'footer',
                            true
            );
            $this->addObjJSResource($objJSResource);

            //Add CSSResource
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                            'bookmarks',
                            '/HybridCMS/Plugins/Comptable/css/bookmarks.css');
            $this->addObjCSSResource($objCSSResource);            
        
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }   
    }
}

?>