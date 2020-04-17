<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSchriftKaufen - Widget with Buttons to visit the Font-Shop
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetSchriftKaufen extends AsideWidget {

    /**
     * attributes
     */
    private $fontName;
    private $linkSchnitte;
    private $linkFamily;
    private $familySize;
    //Plugin SchriftKaufenButtons
    private $objSchriftKaufenButtons;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in relation to other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        //check if objPlugin is not null
        if (!(isset($arrParams['objPlugin']))) {
            throw new \Exception(
            "Error Processing Request: __construct(), fontName is null", 1);
        }

        //check if objPlugin is not null
        if (!($arrParams['objPlugin'] instanceof \HybridCMS\Plugins\SchriftKaufenButtons\SchriftKaufenButtons)) {
            throw new \Exception(
            "Error Processing Request: __construct(), Object is not an instance of class SchriftKaufenButtons", 1);
        }
        
        $this->objSchriftKaufenButtons = &$arrParams['objPlugin'];


        try {
            //set hcms to global
            global $hcms;

            //load CSS and Javascript Resources
            $hcms->loadPlugin($this->objSchriftKaufenButtons);


            //call the parent's contructor
            parent::__construct($widgetName, $positionName, $priority);
        } catch (\Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }

    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) {

        //check if $args is of type array
        if (!is_array($args)) {
            throw new \Exception(
            "Error Processing Request: toString(),
                        args must be an array.", 1);
        }

        return $this->objSchriftKaufenButtons->toString(array('position' => 'sidebar'));
    }

}

?>
