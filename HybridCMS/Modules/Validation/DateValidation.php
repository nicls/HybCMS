<?php

namespace HybridCMS\Modules\Validation;

/**
 * Validation class belonging all user-data 
 *
 * @package Validation
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class DateValidation {

     /**
      * Validates unix timestamp
      * @param Integer $timestamp has to be a Unix Timestamp
      * @return Boolean
      */
     public static function isValidTimestamp($timestamp) {
         return isset($timestamp)
            &&
            is_numeric($timestamp) 
            && 
            $timestamp > 1000000000 //Sun, 09 Sep 2001 01:46:40 GMT
            &&
            $timestamp < 5000000000; //Fri, 11 Jun 2128 08:53:20 GMT
     }
}

?>