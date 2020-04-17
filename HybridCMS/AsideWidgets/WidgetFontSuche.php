<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetFontSuche
 *
 * @package AsideWidgets
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetFontSuche extends AsideWidget {

    /**
     * attributes
     */
    private $objFontSuche;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in order of other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams) {

        //check if objPlugin is not null
        if (!(isset($arrParams['objPlugin']))) {
            throw new \Exception(
            "Error Processing Request: __construct(), objPlugin is null", 1);
        }

        //check if objPlugin is instance of FontSuche
        if (!($arrParams['objPlugin'] instanceof \HybridCMS\Plugins\FontSuche\FontSuche)) {
            throw new \Exception(
            "Error Processing Request: __construct(), Object is not an instance of class FontSuche", 1);
        }

        $this->objFontSuche = &$arrParams['objPlugin'];


        try {
            //set hcms to global
            global $hcms;

            //load CSS and Javascript Resources
            $hcms->loadPlugin($this->objFontSuche);


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

        $op .= '<article class="widget c12">';

        //set headline
        if (isset($this->headline)) {
            $op .= '<h3>' . htmlspecialchars($this->headline) . '</h3>';
        }

        $op .= $this->objFontSuche->toString();

        //close article-tag
        $op .= '</article>';

        return $op;
    }

}

?>
