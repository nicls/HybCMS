<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Description
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Description implements \HybridCMS\Page\PageSettings\IPageSetting {

    /*
     * Attributes
     */
    private $description;

    public function __construct($description) {
        $this->description = $description;
    }

    /**
     * setDescription
     *
     * @param String $description
     * @throws \Exception
     * @return void
     */
    public function setDescription($description) {

        //check if description is a String
        if(!is_string($description) || strlen($description) > 255) {
                throw new \Exception(
                    "Error Processing Request:
                        setDescription(), description is not valid.", 1);
        }

        $this->description = $description;
    }

    /**
     * toString
     *
     * @return type
     */
    public function toString() {
        return "<meta name='description' content='" . $this->description . "'>";
    }

}

?>