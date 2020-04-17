<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetCTABox - Widget with Buttons and image
 *
 * @package WidgetCTABox
 * @author Claas Kalwa
 * @copyright 2016 Claroweb.de
 */
class WidgetCTABox extends AsideWidget {
    
    /**
     * FooterShot 
     * @var \HybridCMS\Plugins\FooterShot
     */
    private $objFooterShot;

    /**
     * __construct
     *
     * @param widgetName:String - name of the widget
     * @param positionName - name of the widget-position
     * @param priority - priority of the current widget in relation to other widgets
     * @param arrParams 
     */
    public function __construct($widgetName, $positionName, $priority, $arrParams)
    {
        
        //call the parent's contructor
        parent::__construct($widgetName, $positionName, $priority);        

        //check if objPlugin is not null
        if (false === isset($arrParams['objFooterShot'])) {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "objFooterShot is not given", 1);
        }

        if (false === ($arrParams['objFooterShot'] instanceof 
                \HybridCMS\Plugins\FooterShot\FooterShot)) 
        {
            throw new \Exception(
            "Error Processing Request: __construct(), "
                    . "Object is not an instance of class FooterShot.", 1);
        }        
        
        $this->objFooterShot = $arrParams['objFooterShot'];
        
        //set hcms to global
        global $hcms;

        //load CSS and Javascript Resources
        $hcms->loadPlugin($arrParams['objFooterShot']);
    }     

    /**
     * toString
     *
     * @param Integer[] $args - array to pass the imgHeight and imgWidth of a thumb.
     * imgScale indicates wether the image should be scaled or cropped
     *
     * @return String
     */
    public function toString($args = array()) 
    {

        //check if $args is of type array
        if (!is_array($args)) {
            throw new \Exception(
            "Error Processing Request: toString(),
                        args must be an array.", 1);
        }

        $op = "";
        $op .= '<article class="widget">';
        
        $op .= $this->objFooterShot->toString();
        $op .= '</article>';
        
        return $op;
    }

}

?>
