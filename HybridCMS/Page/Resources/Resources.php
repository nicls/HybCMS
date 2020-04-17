<?php

namespace HybridCMS\Page\Resources;

class Resources {

    //CSS Rsources
    private $arrObjCSSResources = array();
    private $arrObjActiveCSSResources = array();
    private $arrObjActiveCSSResourcesMinify = array();
    //JS Resources
    private $arrObjJSResources = array();
    private $arrObjActiveJSResourcesHead = array();
    private $arrObjActiveJSResourcesFooter = array();
    private $arrObjActiveJSResourcesMinifyHead = array();
    private $arrObjActiveJSResourcesMinifyFooter = array();
    private $arrObjActiveJSResourcesMinifyAsyncHead = array();
    private $arrObjActiveJSResourcesMinifyAsyncFooter = array();
    
    /**
     * __construct
     */
    public function __construct() { }

    /**
     * registerCSSResource
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     * @return Boolean
     */
    public function registerCSSResource(
            $resourceName,
            $resourcePath,
            $priority = 1,
            $minify = true,
            $autoActivate = true) {    

        try {

            //add Resources
            $objCSSRsource = new \HybridCMS\Page\Resources\CSSResource(
                            $resourceName,
                            $resourcePath,
                            $priority,
                            $minify,
                            $autoActivate);            

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }

        //check if autoactivate is set for this Recource
        if ($objCSSRsource->getAutoActivate() === true) {

            //add object direcly to $this->arrObjActiveCSSResources
            if ($objCSSRsource->getMinify() === true) {
  
                if(!$this->isRegistered($this->arrObjActiveCSSResourcesMinify, $objCSSRsource, 'arrObjActiveCSSResourcesMinify')) {
                    array_push($this->arrObjActiveCSSResourcesMinify, $objCSSRsource);
                }

                //else add object to $this->arrObjActiveCSSResources
            } else {
                if(!$this->isRegistered($this->arrObjActiveCSSResources, $objCSSRsource, 'arrObjActiveCSSResources')) {
                    array_push($this->arrObjActiveCSSResources, $objCSSRsource);                                       
                }
            }

            //Resource must be activated explicitly
        } else {

            //add Resource to $this->arrObjCSSResources
            if(!$this->isRegistered($this->arrObjCSSResources, $objCSSRsource, 'arrObjCSSResources')) {
                array_push($this->arrObjCSSResources, $objCSSRsource);
            }
        }
    }

//end function registerCSSResource

    /**
     * registerJSResource
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     * @param String $position - can be head or footer
     * @param Boolean $async
     * @return Boolean
     */
    public function registerJSResource(
            $resourceName,
            $resourcePath,
            $priority = 1,
            $minify = true,
            $autoActivate = true,
            $position = 'footer',
            $async = false) {

        try {

            //create JSResource
            $objJSResource = new \HybridCMS\Page\Resources\JSResource(
                            $resourceName,
                            $resourcePath,
                            $priority,
                            $minify,
                            $autoActivate,
                            $position,
                            $async);


            //check if resource is still registered
            if(!$this->isRegistered($this->arrObjJSResources, $objJSResource, 'arrObjJSResources')) {
                
                //add object to $this->arrObjJSResources
                array_push($this->arrObjJSResources, $objJSResource);

                //autoactivate Resource if required
                if ($objJSResource->getAutoActivate() === true) {
                    $this->activateResources(array($objJSResource->getResourceName()));
                }
            }
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * activateResources
     *
     * @param String[] $arrResourceNames
     * @return Integer - number of activated Resources
     */
    public function activateResources($arrResourceNames) {
               

        //check if $arrResourceNames is an array
        if (!is_array($arrResourceNames)) {
            throw new \Exception(
                    "Error Processing Request: activateResources(), arrResourceNames must be an array.", 1);
        }

        //indicates the number of activate Resources
        $numberOfActivatedResources = 0;

        foreach ($arrResourceNames as $resourceName) {

            //check if resourceName is alphanumeric
            if (!ctype_alnum($resourceName)) {
                throw new \Exception(
                        "Error Processing Request: activateResources(), resourceName mustbe alphanumeric.", 1);
            }

            /*
             * Handle CSSResources
             */
            foreach ($this->arrObjCSSResources as $objCSSResource) {

                //check if resourceName belongs to a CSSResource
                if ($objCSSResource->getResourceName() === $resourceName) {

                    //handle CSSResource that should be minified
                    if ($objCSSResource->getMinify() === true) {

                        array_push($this->arrObjActiveCSSResourcesMinify, $objCSSResource);

                        //handle CSSResources that should not be minified
                    } else {
                        array_push($this->arrObjActiveCSSResources, $objCSSResource);
                    }

                    //increment number of Activated Resources
                    $numberOfActivatedResources++;
                }
            }


            /*
             * handle JSResources
             */
            foreach ($this->arrObjJSResources as $objJSResource) {

                //check if resourceName belongs to a JSResource
                if ($objJSResource->getResourceName() === $resourceName) {

                    //get js-attributes
                    $async = $objJSResource->getAsync();
                    $minify = $objJSResource->getMinify();
                    $pos = $objJSResource->getPosition();

                    //constellation minify, async, head
                    if ($minify && $async && $pos == 'head')
                        array_push($this->arrObjActiveJSResourcesMinifyAsyncHead, $objJSResource);

                    else //constellationminify, async, footer
                    if ($minify && $async && $pos == 'footer')
                        array_push($this->arrObjActiveJSResourcesMinifyAsyncFooter, $objJSResource);

                    else //constellation minify, !async, head
                    if ($minify && !$async && $pos == 'head')
                        array_push($this->arrObjActiveJSResourcesMinifyHead, $objJSResource);

                    else //constellation minify, !async, footer
                    if ($minify && !$async && $pos == 'footer')
                        array_push($this->arrObjActiveJSResourcesMinifyFooter, $objJSResource);

                    else //constellation head
                    if ($pos == 'head')
                        array_push($this->arrObjActiveJSResourcesHead, $objJSResource);

                    else //constellation footer
                    if ($pos == 'footer')
                        array_push($this->arrObjActiveJSResourcesFooter, $objJSResource);

                    //increment number of activated Resources
                    $numberOfActivatedResources++;
                }
            }
        }
        return $numberOfActivatedResources;
    }


    /**
     * getActiveCSSResources
     *
     * @return CSSResources[] - all activated CSSResources
     */
    public function getActiveCSSResources() {         

        //minify $this->arrObjActiveCSSResourcesMinify
        $objCSSResourceMinified = $this->minifyCSSResources($this->arrObjActiveCSSResourcesMinify);

        //add minified CSSResources as regular CSSResource to $this->arrObjActiveCSSResources
        if ($objCSSResourceMinified)
            array_push($this->arrObjActiveCSSResources, $objCSSResourceMinified);           

        //sort CSSResource by priority
        $sortedCSSResources = $this->orderResourcesByPriority($this->arrObjActiveCSSResources);
        
        //return sorted and actve CSSResources
        return $sortedCSSResources;
    }


    /**
     * getActiveJSResources
     *
     * @return ActiveJSResource[] - all activated JSResources for a specifi position
     */
    public function getActiveJSResources($position) {

        try {
            /*
             * handle JSResource for the position 'head'
             */
            if ($position === 'head') {

                //minify JSResources Head
                $arrObjActiveJSResourceMinifiedHead = $this->minifyJSResources($this->arrObjActiveJSResourcesMinifyHead);
                $arrObjActiveJSResourceMinifiedAsyncHead = $this->minifyJSResources($this->arrObjActiveJSResourcesMinifyAsyncHead);

                //add minified JSResources as regular CSSResource to $this->arrObjActiveJSResourcesHead
                if ($arrObjActiveJSResourceMinifiedHead)
                    array_push($this->arrObjActiveJSResourcesHead, $arrObjActiveJSResourceMinifiedHead);
                if ($arrObjActiveJSResourceMinifiedAsyncHead)
                    array_push($this->arrObjActiveJSResourcesHead, $arrObjActiveJSResourceMinifiedAsyncHead);

                //sort JSResources by Priority
                $sortedJSResources = $this->orderResourcesByPriority($this->arrObjActiveJSResourcesHead);

                return $sortedJSResources;
            }

            /*
             * handle JSResource for the position 'footer'
             */
            else if ($position === 'footer') {

                //minify JSResources Footer
                $arrObjActiveJSResourceMinifiedFooter = $this->minifyJSResources($this->arrObjActiveJSResourcesMinifyFooter);
                $arrObjActiveJSResourceMinifiedAsyncFooter = $this->minifyJSResources($this->arrObjActiveJSResourcesMinifyAsyncFooter);

                //add minified JSResources as regular CSSResource to $this->arrObjActiveJSResourcesFooter
                if ($arrObjActiveJSResourceMinifiedFooter)
                    array_push($this->arrObjActiveJSResourcesFooter, $arrObjActiveJSResourceMinifiedFooter);
                if ($arrObjActiveJSResourceMinifiedAsyncFooter)
                    array_push($this->arrObjActiveJSResourcesFooter, $arrObjActiveJSResourceMinifiedAsyncFooter);

                //sort JSResource by Priority
                $sortedJSResources = $this->orderResourcesByPriority($this->arrObjActiveJSResourcesFooter);

                return $sortedJSResources;
            }

            else {
                throw new \Exception(
                        "Error Processing Request: getActiveJSResources(),
                            position must be head or footer.", 1);
            }
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }//end function getActiveJSResources



    /**
     * minifyCSSResources
     *
     * @param CSSResource[] $arrObjCSSResources
     * @return null|CSSResource
     * @throws \Exception
     */
    protected function minifyCSSResources($arrObjCSSResources) {

        //check if $arrObjCSSResources is an array
        if (!is_array($arrObjCSSResources)) {
            throw new \Exception(
                    "Error Processing Request: minifyCSSResources(),
                        arrObjCSSResources must be an array.", 1);

        //check if $arrObjCSSResources is of type CSSResource[]
        } else {
            foreach ($arrObjCSSResources as $objCSSResource) {
                if(!($objCSSResource instanceof \HybridCMS\Page\Resources\CSSResource)) {
                    throw new \Exception(
                            "Error Processing Request: minifyCSSResources(),
                                arrObjCSSResources must be of type CSSResource[].", 1);
                }
            }
        }

        //check if given CSSResources are greater than 0
        if (count($arrObjCSSResources) === 0) return null;

        try {

            //order Resources by priority
            $arrObjCSSResources = $this->orderResourcesByPriority($arrObjCSSResources);

            //set path for the minified Resource
            $pathMinify = '/min/f=';

            //set a new name for the minified Resource
            $resourceName = 'minified';

            //set the priority of the lowest priority in the set of CSSResources
            $priority = $arrObjCSSResources[0]->getPriority();

            //counter
            $cnt = 0;

            //minify Resources
            foreach ($arrObjCSSResources as $objCSSResource) {

                $pathTmp = $objCSSResource->getResourcePath();

                //cut of protocol and hostname
                if (stripos($pathTmp, 'http') === 0) {
                    $arrTmp = explode('/', $pathTmp);
                    $arrTmp = array_slice($arrTmp, 3);
                    $pathTmp = implode('/', $arrTmp);
                } else if (strpos($pathTmp, '/') === 0) {
                    $pathTmp = substr($pathTmp, 1);
                }

                $pathMinify .= $pathTmp;
                if ($cnt++ !== count($arrObjCSSResources) - 1)
                    $pathMinify .= ','; //remove seperator from last resource
            }

            //asign minified Resources
            $arrObjCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    $resourceName,
                    $pathMinify,
                    $priority,
                    false, //minify
                    false //autoactivate
                    );

            return $arrObjCSSResource;

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * minifyJSResources
     *
     * @param JSResource[] $arrObjJSResources
     * @return null|JSResource
     * @throws \Exception
     */
    protected function minifyJSResources($arrObjJSResources) {

        //check if $arrObjCSSResources is an array
        if (!is_array($arrObjJSResources)) {
            throw new \Exception(
                    "Error Processing Request: minifyJSResources(),
                        arrObjJSResources must be an array.", 1);

        //check if $arrObjCSSResources is of type CSSResource[]
        } else {
            foreach ($arrObjJSResources as $objJSResource) {
                if(!($objJSResource instanceof \HybridCMS\Page\Resources\JSResource)) {
                    throw new \Exception(
                            "Error Processing Request: minifyJSResources(),
                                arrObjJSResources must be of type JSResource[].", 1);
                }
            }
        }

        //check if JSResources was submitted
        if (count($arrObjJSResources) === 0) return null;

        //order Resources by priority
        $arrObjJSResources = $this->orderResourcesByPriority($arrObjJSResources);

        //set Path for the minified Resource
        $pathMinify = '/min/f=';

        //set a new resourceName for the minified JSResource
        $resourceName = 'minified';

        //get the priority of the JSResource with the lowest priority
        $priority = $arrObjJSResources[0]->getPriority();

        //get the position of the resources
        $position = $arrObjJSResources[0]->getPosition();

        //get the async Attribute from the JSresources
        $async = $arrObjJSResources[0]->getAsync();

        //counter
        $cnt = 0;

        //minify JSResources position head
        foreach ($arrObjJSResources as $objJSResource) {

            $pathTmp = $objJSResource->getResourcePath();

            //cut of protocol and hostname
            if (stripos($pathTmp, 'http') === 0) {
                $arrTmp = explode('/', $pathTmp);
                $arrTmp = array_slice($arrTmp, 3);
                $pathTmp = implode('/', $arrTmp);
            } else if (strpos($pathTmp, '/') === 0) {
                $pathTmp = substr($pathTmp, 1);
            }

            $pathMinify .= $pathTmp;
            if ($cnt++ !== count($arrObjJSResources) - 1)
                $pathMinify .= ','; //remove seperator from last resource
        }

        //asign minified Resource
        $arrObjJSResource = new \HybridCMS\Page\Resources\JSResource(
                        $resourceName,
                        $pathMinify,
                        $priority,
                        false, //the resource is minified
                        false, //autoactivation is done
                        $position,
                        $async);

        return $arrObjJSResource;
    }


    /**
     * orderResourcesByPriority - quicksort reosurces by Priority
     *
     * @param Resource[] $arrObjResources
     * @return Resource
     */
    public function orderResourcesByPriority($arrObjResources) {

        if (count($arrObjResources) <= 1)
            return $arrObjResources;

        $left_arr = array();
        $right_arr = array();

        $key = $arrObjResources[0]->getPriority();
        $arrKey = $arrObjResources[0];

        for ($i = 1; $i < count($arrObjResources); $i++) {
            if ($arrObjResources[$i]->getPriority() <= $key) {
                $left_arr[] = $arrObjResources[$i];
            } else {
                $right_arr[] = $arrObjResources[$i];
            }
        }

        $left_arr = $this->orderResourcesByPriority($left_arr);
        $right_arr = $this->orderResourcesByPriority($right_arr);

        return array_merge($left_arr, array($arrKey), $right_arr);
    }
    
    /**
     * 
     * @param Ressource[] $arrObjRessources
     * @param String $resourceName
     * @return boolean
     */
    private function isRegistered(&$arrObjRessources, &$objResource, $arrName) {
        
        $isRegistered = false;    
        $resourceName = $objResource->getResourceName();
        
        foreach ($arrObjRessources as &$objResource) {
            if($objResource->getResourceName() === $resourceName) {
                $isRegistered = true;
                break;
            }
        }
         
        return $isRegistered;
    }

}//end class

?>