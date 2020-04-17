<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSiegelKlimaHosting - Just shows a Siegel of Linotype Partnership
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetSiegelKlimaHosting extends AsideWidget {
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


        $output = '';
        
        $output .= '<article class="widget nobackground noborder c12 add-top-20">';
        $output .= '<img class="center" src="/images/klimaneutrales-hosting-206x67.png" alt="Schriftgestaltung.com ist klimaneutral gehostet" height="67" width="206" />';
        $output .= '</article>';      
        
        return $output;
    }

}

?>
