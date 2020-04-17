<?php

namespace HybridCMS\AsideWidgets;

/**
 * class AsideWidget - This class represents an AsideWidget for a position
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class AsideWidget {

    /**
     * Attributes
     */
    protected $widgetName; //the name of thius widget
    protected $positionName; //indicates a name for a position
    protected $priority; //indicates the priority in order of other AsideWidgets
    protected $headline; //headline of the widget
    protected $headlineUrl; //url to link the headline
    protected $anchorname; //anchorname to copy and paste shown under headline

    /**
     * __construct
     *
     * @param positionName:String
     */

    protected function __construct($widgetName, $positionName, $priority) {
        try {

            $this->setWidgetName($widgetName);
            $this->setPositionName($positionName);
            $this->setPriority($priority);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * toString - returns the AsideWidget as String for output on html-pages
     * @return String
     */
    public abstract function toString($args);

    /**
     * setWidgetName - sets the name of the current AsideWidget.
     * Only alphanumeric Characters are allowed.
     *
     * @param widgetName:String
     * @return void
     */
    public function setWidgetName($widgetName) {
        //check if widgetName has only letters and numbers
        if (ctype_alnum($widgetName)) {
            $this->widgetName = $widgetName;
        } else {
            throw new \Exception(
            "Error Processing Request: setWidgetName(), widgetName has a wrong format.", 1);
        }
    }

    /**
     * setPositionName - sets the name of the position. Only Alphanumeric Charakters are allowed
     *
     * @param positionName:String
     * @return void
     */
    public function setPositionName($positionName) {

        //check if positionName has only letters and numbers
        if (ctype_alnum($positionName)) {
            $this->positionName = $positionName;
        } else {
            throw new \Exception(
            "Error Processing Request: setPositionName(), positionName has a wrong format.", 1);
        }
    }

    /**
     * setPriority - sets the priority of the current AsideWidget in order of
     * other AsideWidgets.
     *
     * @param priority:Integer
     * @return void
     */
    public function setPriority($priority) {
        if (is_int($priority)) {
            $this->priority = $priority;
        } else {
            throw new \Exception(
            "Error Processing Request: setPriority(), priority has a wrong format.", 1);
        }
    }

    /**
     * setHeadline - sets the headline of the widget.
     *
     * @param headline:String
     * @return void
     */
    public function setHeadline($headline) {

        //check headline 
        if (preg_match('/^[a-zA-Z0-9\-_\s,\.!:ÖÄÜöüäß%€]+$/', $headline)) {
            $this->headline = $headline;
        } else {
            throw new \Exception(
            "Error Processing Request: setHeadline(), headline is not valid.", 1);
        }
    }

    /**
     * setHeadlineUrl
     * @param String $url
     * @throws \Exception
     */
    public function setHeadlineUrl($url) {
        //check if URL is valid
        if (!\HybridCMS\Modules\Url\Url::isValidURL($url)) {
            throw new \Exception(
            "Error Processing Request: setHeadlineUrl(),
                        url is not valid.", 1);
        }

        //set url
        $this->headlineUrl = $url;
    }
    
    /**
     * setAnchorname - sets anchorname of the current AsideWidget.
     *
     * @param anchorname:String
     * @return void
     */
    public function setAnchorname($anchorname) 
    {        
        //check if $anchorlink is valid
        if (1 === preg_match('/^[a-zA-Z0-9\-]+$/', $anchorname)) 
        {
            $this->anchorname = $anchorname;
        } 
        else 
        {
            throw new \Exception(
            "Error Processing Request: setAnchorname(), "
                    . "anchorname has a wrong format.", 1);
        }
    }    

    /**
     * getter
     */
    public function getPositionName() {
        return $this->positionName;
    }

    public function getPriority() {
        return $this->priority;
    }

    public function getWidgetName() {
        return $this->widgetName;
    }

    public function getHeadline() {
        return $this->headline;
    }

    public function getHeadlineUrl() {
        return $this->headlineUrl;
    }

}

?>
