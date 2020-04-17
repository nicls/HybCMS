<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPasswordReseted that shows a message to the user that his password 
 * was resetted.
 *  
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPasswordReseted {
        
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
        $op = '';
        $op .= 'Ihr Password wurde ergolgreich zurückgesetzt.';
        
        return $op;
    }
    
}

?>