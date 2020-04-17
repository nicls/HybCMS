<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewLogedIn
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewLogedIn {
    
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
        return "Login erfolgreich!";
    }    
}
?>