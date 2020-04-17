<?php

namespace HybridCMS\Plugins\Tldr;

/**
 * class Tldr
 *
 * @package Tldr
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class Tldr extends \HybridCMS\Plugins\Plugin\Plugin {
    
    /**
     * Select Box Label
     * @var String
     */
    private $label;
    
    /**
     * __construct
     * 
     */
    public function __construct($label) {

        try {
            
            //call parent constructor
            parent::__construct();
            
            $this->setLabel($label);

            /**
             * JS
             * 
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             * 6. $position
             * 7. $async
             */
            $objJSResource1 = new \HybridCMS\Page\Resources\JSResource(
                            'tldr',
                            '/HybridCMS/Plugins/Tldr/js/f.js',
                            5,
                            true,
                            true,
                            'footer',
                            true
            );            
            $this->addObjJSResource($objJSResource1);
            
            $objJSResource2 = new \HybridCMS\Page\Resources\JSResource(
                    'spinner', '/js/spin.js', 8, true, true, 'footer', true
            );
            $this->addObjJSResource($objJSResource2);              
            
            /**
             * CSS
             *
             * 1. $resourceName
             * 2. $resourcePath
             * 3. $priority
             * 4. $minify
             * 5. $autoActivate
             */
            $objCSSResource = new \HybridCMS\Page\Resources\CSSResource(
                    'tldr', //1
                    '/HybridCMS/Plugins/Tldr/css/f.css', //2
                    4, //3
                    true, //4
                    true //5
                    );
            $this->addObjCSSResource($objCSSResource);            
            
            
        } catch (Exception $e) {

            //Log Error
            $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, \HybridCMS\Helper\KLogger::ERR);
            $objLogger->logError($e->__toString() . "\n");

            throw $e;
        }
    }
    
    /**
     * setLabel
     * @param String $label
     * @throws \Exception
     */
    private function setLabel($label)
    {
        //check if $pollName is an String width 100 charekters length in maximum
        if (!is_string($label) || strlen($label) > 100 || strlen($label) === 0) 
        {
            throw new \Exception(
            'Error Processing Request: setLabel(),
                    label must be an String width 100 
                    charekters length in maximum.', 1);
        } 
        
        $this->label = $label;
    }
    
    /**
     * toString
     *
     * @param mixed[] $args
     * @return String
     */
    public function toString($args = array()) {

        //output-String
        $op = '';  
        $op .= '<div id="hyb_tldr">';
        $op .= "<label>" . htmlspecialchars($this->label) . " Ich will </label>";
        $op .= '<select>';
        $op .= '<option value="long" selected="selected">alle Details</option>';
        $op .= '<option value="short">es kurz und knapp</option>';        
        $op .= '</select>';
        $op .= '<span>.</span>';
        $op .= '</div>';
        
        return $op;
       
    }    
}
?>
