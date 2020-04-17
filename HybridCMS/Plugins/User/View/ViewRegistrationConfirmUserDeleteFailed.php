<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistrationConfirmUserDeleteFailed
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistrationConfirmUserDeleteFailed {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Der von Ihnen angefragte Benutzeraccount existiert nicht.';
    }
    
}

?>