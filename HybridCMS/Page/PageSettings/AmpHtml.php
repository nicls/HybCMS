<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class AmpHtml - AMP Meta Tags
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class AmpHtml implements \HybridCMS\Page\PageSettings\IPageSetting {

    private $ampPage;

    /**
     * __construct
     *
     * @param String $ampPage
     * @throws \Exception
     */
    public function __construct($ampPage, $showIndex = false) {

        //remove index.abc from url
        if(!$showIndex) $ampPage = preg_replace("/index\..*/i", '', $ampPage);
        
        try {

            //assign cononical
            $this->setAmpPage($ampPage);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }

    }

    /**
     * setAmpPage
     *
     * @param String $ampPage
     * @throws \Exception
     * @return void
     */
    public function setAmpPage($ampPage) {

        //check if url of canonical is valid
        if(\HybridCMS\Helper\Helper::isValidUrl($ampPage)) {
            $this->ampPage = $ampPage;
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
        return "<link "                 
                . "rel='amphtml' href='" 
                . $this->ampPage . "'/>";
    }

}// end of class

?>