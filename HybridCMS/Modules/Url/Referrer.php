<?php

namespace HybridCMS\Modules\Url;

/**
 * class Refferer - Class to handle Referrers from the current domain
 *
 * @package Modules
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Referrer extends \HybridCMS\Modules\Url\Url {
    
    /**
     * __construct
     */
    public function __construct($referrer) {
        
        try {

            /** Parent Constructor */
            parent::__construct($referrer);                   

            
        } catch (\Exception $e) {
            
            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }


    /**
     * refIsInternal - returns true if the referrer is from the same host
     * @return boolean
     */
    public function refIsInternal() 
    {                        
        return $this->urlIsInternal();
    }
    
    /**
     * compares the refCompare variable with the acatual referrer
     * 
     * @param String $refCompare
     * @return boolean
     */
    public function userCameFrom($refCompare) {
        
        if (!self::isValidUrl($refCompare)) {
            throw new \Exception(
            "Error Processing Request: userCameFrom(),
                            refCompare is not a valid url.", 1);
        }
        
        return (stripos($this->getUrl(), $refCompare) !== false);
        
    }
}

?>