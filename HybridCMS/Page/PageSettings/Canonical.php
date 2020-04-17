<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Canonical
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Canonical implements \HybridCMS\Page\PageSettings\IPageSetting {

    private $canonical;

    /**
     * __construct
     *
     * @param String $canonical
     * @throws \Exception
     */
    public function __construct($canonical, $showIndex = false) {

        //remove index.abc from url
        if(!$showIndex) $canonical = preg_replace("/index\..*/i", '', $canonical);
        
        try {

            //assign cononical
            $this->setCanonical($canonical);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }

    }

    /**
     * setCanonical
     *
     * @param String $canonical
     * @throws \Exception
     * @return void
     */
    public function setCanonical($canonical) {

        //check if url of canonical is valid
        if(\HybridCMS\Helper\Helper::isValidUrl($canonical)) {
            $this->canonical = $canonical;
        } else {
                throw new \Exception(
                    "Error Processing Request:
                        __construct(), url is not valid.", 1);
        }
    }

    /**
     * toString
     *
     * @return String
     */
    public function toString() 
    {
        $itemprop = " ";
        if(HYB_HOMEPAGE === $this->canonical)
        {
            $itemprop = " itemprop='url' ";
        }
        
        return "<link" 
                . htmlspecialchars($itemprop) 
                . "rel='canonical' href='" 
                . $this->canonical . "'/>";
    }

}// end of class

?>