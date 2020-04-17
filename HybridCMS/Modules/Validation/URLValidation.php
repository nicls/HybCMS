<?php

namespace HybridCMS\Modules\Validation;

/**
 * URL validation functions
 *
 * @package Validation of URLs
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class URLValidation {

    /**
     * checks if url is valid    
     * @param String $url
     * @return Boolean
     */
    public static function isValidUrl($url) {

        return isset($url) 
            &&
            is_string($url) 
            &&
            preg_match(
                    '~^((?P<scheme>[^:/?#]+):(//))?((\\3|//)?'
                    . '(?:(?P<user>[^:]+):(?P<pass>[^@]+)@)?'
                    . '(?P<host>[^/?:#]*))(:(?P<port>\\d+))?'
                    . '(?P<path>[^?#]*)(\\?(?P<query>[^#]*))?'
                    . '(#(?P<fragment>.*))?~u', $url);
    }

}
