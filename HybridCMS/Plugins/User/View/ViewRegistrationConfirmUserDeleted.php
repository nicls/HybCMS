<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistrationConfirmUserDeleted
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistrationConfirmUserDeleted {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Der Benutzer wurde erfolgreich gelöscht.';
    }
    
}