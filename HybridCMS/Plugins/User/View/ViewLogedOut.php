<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewLogedOut
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewLogedOut {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
   
    /**
     * toString
     * @return string
     */
    public function toString() 
    {
        return "Sie wurden erfolgreich ausgelogged!";
    }
    
}

?>