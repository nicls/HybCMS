<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewPublicUserProfileDoesNotExist
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewPublicUserProfileDoesNotExist 
{

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}    
    
    
    public function toString() 
    {
        return 'Der aufgerufene Benutzer existiert nicht.';
    }
}