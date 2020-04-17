<?php

namespace HybridCMS\Modules\Validation;

/**
 * String validation functions for user inupt
 *
 * @package Validation of Strings
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class StringValidation {
    
     /**
      * Checks if md5 token is valid
      * @param String $md5Token
      * @return Boolean
      */
     public static function isValidMd5Token($md5Token) {
         return isset($md5Token) 
            && ctype_alnum($md5Token) 
            && strlen($md5Token) <= 32;
     }
     
}

?>