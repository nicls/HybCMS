<?php

namespace HybridCMS\AsideWidgets;

/**
 * class WidgetSMTwoClickButton
 *
 * @package WidgetSMTwoClickButton
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class WidgetSMTwoClickButton extends AsideWidget {
    /**
     * attributes
     */
    private $objSMB;

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
            
            //Social Media 2-Click Buttons            
            $this->objTCB = new \HybridCMS\Plugins\SMTwoClickButton\SMTwoClickButton(true, true, true, 'Artikel hier teilen.');
            
            global $hcms;
            
            if($hcms) { $hcms->loadPlugin($this->objTCB); }            
            
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

            $op = '';
        
            if(isset($this->objTCB)) { 
               
                $op .= '<article class="widget c12">';
                $op .= '<h3>Diesen Artikel teilen</h3>';    
                
                //initialised globally
                $op .= $this->objTCB->toString(); 
            
                //close article-tag
                $op .= '</article>';
            }
            
            return $op;
    }

}

?>
