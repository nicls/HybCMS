<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewNewEmailConfirmed
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewNewEmailConfirmed {
    
    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Ihre neue E-Mail Adresse ist nun bestätigt.';
    }
}