<?php

namespace HybridCMS\Plugins\User\View\Dashboard;

/**
 * class ViewDashboardDeleteProfile
 *
 * @package HybridCMS\Plugins\User\View\Dashboard
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewDashboardDeleteProfile extends \HybridCMS\Plugins\User\View\ViewUser
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
        
        $op .= '<h1>Profil löschen</h1>';        

        //open form
        $op .= '<form role="form" action="/user/dashboard/delete-profile.html" method="POST">';
        
        $op .= '<div class="row">';
        $op .= '<input class="btn btn-success btn-block btn-lg" '
                . 'name="hyb_user_deleteProfile" type="submit" '
                . 'value="Account löschen" />';
        $op .= '</div>';
        
        //close from
        $op .= '</form>';

        return $op;
    }
}
?>