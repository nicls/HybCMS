<?php

namespace HybridCMS\Plugins\Plugin;

/**
 * class Plugin
 *
 * @package Plugin
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class Plugin {

    /**
     * CSSResources for the current Plugin
     * @var CSSResource
     */
    protected $arrObjCSSResources;

    /**
     * JSResources for the current Plugin
     * @var JSResource
     */
    protected $arrObjJSResources;

    /**
     * __construct
     */
    public function __construct() {
        $this->arrObjCSSResources = array();
        $this->arrObjJSResources = array();
    }

    /**
     * addObjCSSResource
     * @return void
     */
    protected function addObjCSSResource($objCSSResource) {
      
            
        //check if $objCSSResource is instance of CSSResource
        if ($objCSSResource instanceof \HybridCMS\Page\Resources\CSSResource) {
            array_push($this->arrObjCSSResources, $objCSSResource);
        } else {
            throw new \Exception(
                    "Error Processing Request: addObjCSSResource(),
                       objCSSResource must be of type CSSResource.", 1);
        }
        
    }

    /**
     * addObjJSResource
     * @return void
     */
    protected function addObjJSResource($objJSResource) {
            
        //check if objJsResource is instance of JSResource
        if ($objJSResource instanceof \HybridCMS\Page\Resources\JSResource) {
            array_push($this->arrObjJSResources, $objJSResource);
        } else {
            throw new \Exception(
                    "Error Processing Request: addObjJSResource(),
                       objJSResource must be of type JSResource.", 1);
        }
    }

    /**
     * getter
     */
    public function getArrObjCSSResources() { return $this->arrObjCSSResources; }
    public function getArrObjJSResources() { return $this->arrObjJSResources; }

}

?>