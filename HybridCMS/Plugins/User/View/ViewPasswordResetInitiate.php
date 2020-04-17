<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPasswordResetInitiate that show the following form-elements to
 * the user:
 * 
 * input:  email
 * button: reset password
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPasswordResetInitiate extends \HybridCMS\Plugins\User\View\ViewUser
{        
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) 
    {        
        //call constructor of parent class
        parent::__construct($arrSettings);        
    }  
    
    /**
     * toString
     * @return string
     */
    public function toString() 
    {
        $op = '';

        //open form
        $op .= '<form role="form" action="/reset-password.html" method="POST">';
        
        #################################
        #### Add email Inputfield #######
        #################################                    
        //FormElemGroupEmailExistingAndRegistered
        $op .= $this->toStringFormElemGroup
                ('objFormElemGroupEmailExistingAndRegistered');   
        
        $op .= '<div>';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_resetPassword" type="submit" '
                . 'value="Passwort zurücksetzen" />';
        
        $op .= '<div />';

        //close from
        $op .= '</form>';
        
        return $op;
    }  
}

?>