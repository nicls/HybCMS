<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistrationConfirmFailed
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistrationConfirmFailed {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Das aufgerufene Benutzerkonto existiert nicht.';
    }
}