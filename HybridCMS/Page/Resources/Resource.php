<?php

namespace HybridCMS\Page\Resources;

/**
 * class Resource - abstract class of a Resource (JSResource & CSSResource)
 *
 * @package Page\Resource
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class Resource {

    /**
     * Path of the Resource
     * @var String
     */
    protected $resourcePath;

    /**
     * $resourceName indicates an unique name of the resource
     * @var String
     */
    protected $resourceName;

    /**
     * $priority indicates the prority in order of other resources
     * @var Integer
     */
    protected $priority;

    /**
     * indicates wether the resource should me minified
     * @var Boolen
     */
    protected $minify;

    /**
     * indicates if the resource will be activated automaticly
     * @var Boolean
     */
    protected $autoActivate;

    /**
     * __construct
     *
     * @param String $resourceName
     * @param String $resourcePath
     * @param Integer $priority
     * @param Boolean $minify
     * @param Boolean $autoActivate
     */
    public function __construct(
            $resourceName,
            $resourcePath,
            $priority = 1,
            $minify = true,
            $autoActivate = true) {
                     

        try {

            //set attributes
            $this->setResourcePath($resourcePath);
            $this->setResourceName($resourceName);
            $this->setPriority($priority);
            $this->setMinify($minify);
            $this->setAutoActivate($autoActivate);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setAutoactive
     *
     * @param Boolean $autoActivate
     * @throws \Exception
     * return void
     */
    public function setAutoActivate($autoActivate) {
#
        //check if $autoActivate is a Boolean
        if(!is_bool($autoActivate)) {
            throw new \Exception(
                    "Error Processing Request: setMinify(), minify must be a Boolean.", 1);
        }

        $this->autoActivate = $autoActivate;
    }

    /**
     * setMinify
     *
     * @param Boolean $minify
     * @throws \Exception
     * return void
     */
    public function setMinify($minify) {

        //check if $minify is a Boolean
        if(!is_bool($minify)) {
            throw new \Exception(
                    "Error Processing Request: setMinify(), minify must be a Boolean.", 1);
        }

        $this->minify = $minify;
    }

    /**
     * setPriority
     *
     * @param Integer $priority
     * @throws \Exception
     * return void
     */
    public function setPriority($priority) {

        //check if $priority is a Boolean
        if(!is_numeric($priority)) {
            throw new \Exception(
                    "Error Processing Request: setPriority(), priority must be an Integer.", 1);
        }

        $this->priority = $priority;
    }

    /**
     * setResourceName
     *
     * @param String $resourceName
     * @throws \Exception
     * return void
     */
    public function setResourceName($resourceName) {

        //check if resorcePath is alphanumeric
        if(!ctype_alnum($resourceName)) {
            throw new \Exception(
                    "Error Processing Request: setResourceName(), resourceName must be an alphanumeric String.", 1);
        }

        $this->resourceName = $resourceName;
    }

    /**
     * setResourcePath
     *
     * @param String $resourcePath
     * @throws \Exception
     * return void
     */
    public function setResourcePath($resourcePath) {

        //check if resorcePath is a String
        if(!is_string($resourcePath)) {
            throw new \Exception(
                    "Error Processing Request: setResourcePath(), resourcePath must be a String.", 1);
        }

        try {

            //set normalised resourcePath
            $this->resourcePath = $this->normalizeResourcePath($resourcePath);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * normalizeResourcePath - builds an valid url if protocoll an hostname is missing
     *
     * @param type $resourcePath
     * @return String
     */
    protected function normalizeResourcePath($resourcePath) {

        //check if resorcePath is a String
        if(!is_string($resourcePath)) {
            throw new \Exception(
                    "Error Processing Request: normalizeResourcePath(), resourcePath must be a String.", 1);
        }

        //check if protocol and hostname is missig
        if($resourcePath[0] === '/') {
            $resourcePath = HYB_PROTOCOL . HYB_HOST_NAME . $resourcePath;
        }

        //check if url is valid
        if(!\HybridCMS\Helper\Helper::isValidURL($resourcePath)){
            throw new \Exception(
                    "Error Processing Request: normalizeResourcePath(), resourcePath is not a valid Url.", 1);
        }

        return $resourcePath;
    }

    /**
     * getter
     */
    public function getResourcePath() { return $this->resourcePath; }
    public function getResourceName() { return $this->resourceName; }
    public function getPriority() { return $this->priority; }
    public function getMinify() { return $this->minify; }
    public function getAutoActivate() { return $this->autoActivate; }

}

?>