<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPasswordResetInvalidConfirmationLink that shows a message to the 
 * user, that the link he invoked is not valid anymore.
 *  
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPasswordResetInvalidConfirmationLink {
        
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
        $op .= 'Der von Ihnen aufgerufene Link ist nicht mehr gültig';
        
        return $op;
    }
    
}

?>