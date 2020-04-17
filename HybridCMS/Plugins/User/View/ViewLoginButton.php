<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewLoginButton
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewLoginButton {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {
              
    }    
    
    /**
     * toString
     * @return string
     */
    public function toString() 
    {
        $op = '';
        $op .= '<form class="col-xs-2 col-md-2" method="POST" action="/login.html">';
        $op .= '<input type="hidden" name="ref" value="'. htmlentities(HYB_CURRURL) .'" />';
        $op .= '<input type="submit" class="btn btn-success" value="einloggen" />';  
        $op .= '</form>';
        
        return $op;
    }    
}
?>