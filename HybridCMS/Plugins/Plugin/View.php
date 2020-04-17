<?php

namespace HybridCMS\Plugins\Plugin;

/**
 * class View
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class View {
    
    protected $arrSettings;
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) 
    {              
        $this->arrSettings = $arrSettings;
    }      
    
    /**
     * toString
     * @return string
     */
    public abstract function toString($arrParams = array());  
}
?>