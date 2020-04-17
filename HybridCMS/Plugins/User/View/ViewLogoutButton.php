<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewLogoutButton
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewLogoutButton {
    
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
        $op .= '<form class="col-xs-2 col-md-2" method="POST" action="/login.html">';
        $op .= '<input type="hidden" name="hyb_user_logout" value="logout" />';
        $op .= '<input type="submit" class="btn btn-danger" value="ausloggen" />'; 
        $op .= '<a class="btn btn-default add-left-10" href="/user/dashboard/" '
                . 'title="Profileinstellugnen" rel="nofollow">'
                . '<i class="fa fa-cogs"></i></a>';
        $op .= '</form>';
        
        return $op;
    }    
}
?>