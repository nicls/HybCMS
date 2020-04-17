<?php

namespace HybridCMS\Page\PageSettings;

/**
 * class Title
 *
 * @package Page\PageSettings
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Title implements \HybridCMS\Page\PageSettings\IPageSetting {

    /**
     * @var String
     */
    private $title;

    /**
     * __construct
     *
     * @param String $title
     * @param Integer $maxLength
     * @param String $prepend
     */
    public function __construct($title, $prepend = '', $maxLength = 160) {

        //add prepending String
        if (strlen($prepend) > 0) {
            $title = $this->buildTitle($title, $prepend, $maxLength);
        }

        try {

            $this->setTitle($title, $maxLength);

        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(
                    LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * setTitle
     *
     * @param String $title
     * @throws \Exception
     * @return void
     */
    public function setTitle($title, $maxLength) {

        //check if title is a String
        if(!is_string($title)) {
                throw new \Exception(
                    "Error Processing Request:
                        setTitle(), title must be a String.", 1);
        }

        //check if maxLength is an Integer
        if(!is_numeric($maxLength)) {
                throw new \Exception(
                    "Error Processing Request:
                        setTitle(), maxLength must be an Integer. ( " 
                        . htmlspecialchars($maxLength) . ")", 1);
        }

        //check if length of title is valid
        if(!strlen($title) > $maxLength) {
                throw new \Exception(
                    "Error Processing Request:
                        setTitle(), title must be " . $maxLength 
                        . " characters in maximum.", 1);
        }

        $this->title = $title;
    }

    /**
     * buildTitle
     *
     * @param String $title
     * @param String $prepend
     * @param Integer $maxLength
     * @return String
     */
    private function buildTitle($title, $prepend, $maxLength) {

        //check if title + prepend fits into maxLength
        if (strlen($title) + strlen($prepend) <= $maxLength) {
            return $title . $prepend;
        } else {
            return $title;
        }
    }

    /**
     * function toString
     * @return string
     */
    public function toString() {
        return '<title>' . $this->title . '</title>';
    }

}

?>