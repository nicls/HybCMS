<?php

namespace HybridCMS\Plugins\User\View\Dashboard;

/**
 * class ViewDashboardUpdatePassword
 *
 * @package HybridCMS\Plugins\User\View\Dashboard
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewDashboardUpdatePassword extends \HybridCMS\Plugins\User\View\ViewUser
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
     * Builds the formated registration formular
     * @return string Formated registration formular
     */
    public function toString() {
        
        $op = '';

        //open form
        $op .= '<form role="form" action="/user/dashboard/password.html" method="POST">';

        //open conatainer needed data
        $op .= '<div class="row">';        
        
        //Password Login
        $op .= $this->toStringFormElemGroup('objFormElemGroupPasswordLogin'); 
        
        //Add password Inputfield                   
        $op .= $this->toStringFormElemGroup('objFormElemGroupPassword');
        
        //Add passwordRepeat Inputfield               
        $op .= $this->toStringFormElemGroup('objFormElemGroupPasswordRepeat');                      
        
        $op .= '</div>';
        
        $op .= '<div class="row">';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_updatePassword" '
                . 'type="submit" value="Passwort ändern" />';
        $op .= '</div>';
        
        //close from
        $op .= '</form>';

        return $op;
        
    }
}
?>