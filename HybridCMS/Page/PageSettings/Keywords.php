<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Keywords
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Keywords implements \HybridCMS\Page\PageSettings\IPageSetting {

    /*
     * Attributes
     */
    private $arrKeywords;

    public function __construct($arrKeywords) {
        $this->arrKeywords = $arrKeywords;
    }

    /**
     * setArrKeywords
     *
     * @param String[] $arrKeywords
     * @throws \Exception
     * @return void
     */
    public function setArrKeywords($arrKeywords) {

        //check if $arrKeywords is an array
        if(!is_array($arrKeywords)) {
                throw new \Exception(
                    "Error Processing Request:
                        setArrKeywords(), arrKeywords must be an array.", 1);
        }
        
        //check if $arrKeywords is an array of Strings
        foreach ($arrKeywords as $keyword) {
            if(!is_string($keyword)) {
                throw new \Exception(
                    "Error Processing Request:
                        setArrKeywords(), arrKeywords must be an array of strings.", 1);
            }
        }

        $this->arrKeywords = $arrKeywords;
    }

    /**
     * toString
     *
     * @return type
     */
    public function toString() {
        return "<meta name='keywords' content='" . implode(',', $this->arrKeywords) . "'>";
    }

}

?>