<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewNewEmailConfirmFailed
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewNewEmailConfirmFailed {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Die E-Mail Adresse wurde bereits bestätigt.';
    }
}