<?php

namespace HybridCMS\Modules\Validation;

/**
 * Validation class belonging all user-data 
 *
 * @package Validation
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class SMValidation {
    
    /**
     * Checks if Twitter Username is valid
     * @param String $twUsername
     * @return Boolean
     */
    public static function isValidTwitterUsername($twUsername) {
        $pattern = "/^[a-zA-Z0-9_]{1,15}$/";
        
        return isset($twUsername) 
            && 
            preg_match($pattern, $twUsername);
    }
    
    /**
     * Checks a Google Plus id
     * @param Integer $googleplusId
     * @return Boolean
     */
    public static function isValidGoogleplusId($googleplusId) {
                
        $ret = isset($googleplusId) 
            && 
            is_numeric($googleplusId) 
            && 
            30 >= strlen($googleplusId);
                    
            return $ret;            
    }
    
    /**
     * Validates a facebook url
     * @param String $facebookUrl
     * @return Boolean
     */
    public static function isValidFacebookUrl($facebookUrl) {
        $pattern = "/^(http\:\/\/|https\:\/\/)?(?:www\.)?facebook\.com\/"
                . "(?:(?:\w\.)*#!\/)?(?:pages\/)?(?:[\w\-\.]*\/)*([\w\-\.]*)/";
        
        return isset($facebookUrl) 
            &&
            preg_match($pattern, $facebookUrl);
    }
    
    /**
     * Validates a youtube Channel name
     * @param String $ytChannelName
     */
    public static function isValidYoutubeChannelName($ytChannelName) {
        $pattern = "/^[a-zA-Z]{1,30}$/";
        
        $ret = isset($ytChannelName) 
            && 
            preg_match($pattern, $ytChannelName);            
        
        return $ret;
    }

}

?>