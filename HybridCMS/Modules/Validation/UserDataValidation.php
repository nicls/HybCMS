<?php

namespace HybridCMS\Modules\Validation;

/**
 * Validation class belonging all user-data 
 *
 * @package Validation
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UserDataValidation extends \HybridCMS\Modules\Validation\SMValidation {

    /**
     * check if the username is valid
     * @param String $username
     * @return Boolean
     */
    public static function isValidUsername($username) 
    {
        $username = trim($username);
        
        //$pattern = "/^[a-zA-Z0-9öäüÖÄÜß_-\s]{3,20}$/";
        $pattern = "/^[A-Za-z0-9öäüÖÄÜß]+(?:[ _\-][A-Za-z0-9öäüÖÄÜß]+)*$/";
        $ret = isset($username) 
            &&
            is_string($username) 
            &&
            strlen($username) >= 3
            &&
            strlen($username) <= 20
            && 
            preg_match($pattern, $username);
                    
        return $ret;
    }

    /**
     * validates the firstname
     * @param String $firstname
     * @return Boolean
     */
    public static function isValidFirstname($firstname) {
        $pattern = "/^[a-zA-ZöäüÖÄÜß\s\-]{1,35}$/";
        
        return isset($firstname)
            &&
            is_string($firstname) 
            && 
            preg_match($pattern, $firstname);
    }

    /**
     * validates the lastname
     * @param String $lastname
     * @return Boolean
     */
    public static function isValidLastname($lastname) {
        $pattern = "/^[a-zA-ZöäüÖÄÜß\s\-]{1,35}$/";
        
        return isset($lastname)
            &&
            is_string($lastname) 
            && 
            preg_match($pattern, $lastname);
    }
    
    /**
     * Validates the geender
     * @param String $gender
     * @return Boolean
     */
    public static function isValidGender($gender) {
        $pattern = "/^(w|m){1}$/";
        
        return isset($gender)
            &&
            is_string($gender) 
            && 
            preg_match($pattern, $gender);
    }    

    /**
     * Password filter that matches the NSA Password filter DLL ENPASFILT.DLL. 
     * @param String $password
     * @return Boolean
     */
    public static function isValidPassword($password) {

        //At least 1 small-case letter 
        //At least 1 Capital letter 
        //At least 1 digit.
        //At least 1 special character. 
        //Length should be between 8-72 characters. 
        //Spaces allowed.
        //The sequence of the characters is not important.     
        $pattern = "/(?-i)(?=^.{8,72}$)((?!.*\s)(?=.*[A-Z])(?=.*[a-z]))((?=(.*\d){1,})(?=(.*\W){1,}))^.*$/";

        $ret = isset($password)
            && 
            is_string($password) 
            && 
            preg_match($pattern, $password);
                    
        return $ret;
    }
    
    /**
     * Validates a üasswordHash that is hashed with phppass
     * http://www.openwall.com/phpass/
     * @param String $passwordHash
     * @return Boolean
     */
    public static function isValidPasswordHash($passwordHash) {
  
        $ret = isset($passwordHash)
            && 
            is_string($passwordHash) 
            && 
            strlen($passwordHash) >= 20;
                    
        return $ret;
    }    

    /**
     * Checks if email is valid
     * @param type $email
     * @return Boolean
     */
    public static function isValidEmail($email) {
        return isset($email) && filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    /**
     * Checks if About me text is valid.
     * @param type $aboutme
     * @return Boolean
     */
    public static function isValidAboutme($aboutme) {
        return isset($aboutme) && is_string($aboutme) && strlen($aboutme) <= 500;
    }
}

?>