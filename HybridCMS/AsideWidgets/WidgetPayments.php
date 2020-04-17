<?php

namespace HybridCMS\AsideWidgets;

/**
 * class Payment - Just shows the payment options
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetPayments extends AsideWidget {
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


        $op = '';
        
        $op .= '<article class="widget c6">';
        
        //set headline
        if(isset($this->headline)) {
            $op .= '<h3>'. htmlspecialchars($this->headline) .'</h3>';
        }
        
        $op .= '<a href="https://schriftgestaltung.com/linotype/bezahlmoeglichkeiten" target="_blank" rel="nofollow" title="Bezahlmöglichkeiten" />';
        $op .= '<img src="/images/bezahlmoeglichkeiten-214x68.png" alt="Bezahlmöglichkeiten" />';
        $op .= '</a>';
        
        //close article-tag
        $op .= '</article>';          
             
        return $op;
    }

}

?>
