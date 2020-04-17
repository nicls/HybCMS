<?php

namespace HybridCMS\Plugins\User\View\Dashboard;

/**
 * class ViewDashboardDeletedProfile
 *
 * @package HybridCMS\Plugins\User\View\Dashboard
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewDashboardDeletedProfile extends \HybridCMS\Plugins\User\View\ViewUser
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

        $op .= 'Ihr Account wurde erfolgreich gelöscht.';

        return $op;
    }
}
?>