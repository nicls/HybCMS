<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetLatestArticles - This class holds all AsideWidgets for different Positions
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetTwitterButton extends AsideWidget {
    /**
     * attributes
     */

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        try {

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


        //set hcms to global
        global $hcms;
        
        //load TwitterButton Plugin
        $objTwitterButton = new \HybridCMS\Plugins\TwitterButton\TwitterButton();
        $hcms->loadPlugin($objTwitterButton);
        return $objTwitterButton->toString();
    }

}

?>
