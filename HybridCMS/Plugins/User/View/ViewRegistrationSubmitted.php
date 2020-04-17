<?php

namespace HybridCMS\Plugins\User\View;

/**
 * class ViewRegistration
 *
 * @package View
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ViewRegistrationSubmitted {

    /**
     * __construct
     * @throws \Exception
     */
    public function __construct($arrSettings = array()) {}

//end __construct

    /**
     * Builds the formated registration formular
     * @return string Formated registration formular
     */
    public function toString() {

        $op = 'Registration Submitted!';

        return $op;
    }
}

?>