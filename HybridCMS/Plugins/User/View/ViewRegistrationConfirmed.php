<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistrationConfirmed
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistrationConfirmed {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Registrierung erfolgreich abgeschlossen.';
    }
}