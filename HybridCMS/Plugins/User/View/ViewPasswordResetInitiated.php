<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPasswordResetInitiated that shows a message to the user that he 
 * will get an email with a link to follow to reset his password.
 * 
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPasswordResetInitiated {  
        
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
        $op .= '<p>Sie erhalten nun eine Email zum zurücksetzen Ihre Passwords.</p>';
        
        return $op;
    }
    
}

?>