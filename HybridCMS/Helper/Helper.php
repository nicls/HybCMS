<?php

namespace HybridCMS\Helper;

/**
 * class Helper - global Helper functions
 *
 * @package Helper
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
abstract class Helper {

    /**
     * isValidURL - checks if a given url is valid
     *
     * @param url:String
     * @return Boolean
     */
    public static function isValidURL($url) {
        //return preg_match('@^(http|https)\://[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(:[a-zA-Z0-9]*)?/?([a-zA-Z0-9\-\._\?\,\'/\\\+&amp;%\$#\=~])*[^\.\,\)\(\s]$@i', $url);
        return preg_match('{(http|https):\/\/[\w\-_]+(\.[\w\-_]+)+([\w\-\|\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?$}', $url);
    }

    /**
     * checks the rolename of the current user who is logged in
     * @param String $rolename
     * @throws \Exception
     */
    public static function isUser($rolename) 
    {
        if (false === isset($_SESSION['rolename'])) 
        {
            throw new \Exception("\$_SESSION[\'rolename\'] is not defined.");
        }

        return $rolename === $_SESSION['rolename'];
    }

    /**
     * Converts a string into a css class-name
     * @param String $string
     * @return String
     * @throws \Exception
     */
    public static function mkClassName($string) {
        if (!is_string($string)) {
            throw new \Exception(
            "Error Processing Request: mkClassName(),
                    'string must be a String.'", 1);
        }
        $string = strtolower($string);
        $a = array('ä', 'ö', 'ü', 'ß', ' ', '-', '.', '(', ')', '&', '?');
        $b = array('ae', 'oe', 'ue', 'ss', '_', '_', '', '', '', '-', '');
        $string = str_replace($a, $b, $string);
     
        return $string;
    }

    /**
     * trim Strings In Array
     * @param String[] $arrStrings
     * @throws \Exception
     */
    public static function trimStringsInArray(&$arrStrings) {

        if (!is_array($arrStrings)) {
            throw new \Exception(
            "Error Processing Request: trimStringsInArray(),
                    'arrStrings must be a array.'", 1);
        }

        foreach ($array as &$value) {

            if (!is_string($value)) {
                throw new \Exception(
                "Error Processing Request: trimStringsInArray(),
                        'arrStrings must be a String.'", 1);
            }

            $value = trim($value);
        }
    }

    /**
     * Generate a random key from /dev/random
     */
    public static function generateKey($bit_length = 128) {

        $key = null;

        //open file
        $fp = fopen('/dev/random', 'rb');

        if (false === $fp) {
            throw new \Exception(
            "Error Processing Request: generateKey(),
                        'Opening file failed.'", 1);
        }

        $key = substr(
                base64_encode(
                        fread($fp, ($bit_length + 7) / 8)
                        ), 0, (($bit_length + 5) / 6) - 2);
        
        //close file
        fclose($fp);

        return $key;
    }
    
    /**
     * generates a randon Password
     * @param Integer $length Length of the passwords
     * @return String
     */
    public static function generateRandomPassword() {

        $wordsLower = "abcdefghijklmnopqrstuvwxyz";
        $wordsUpper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits = '0123456789';
        $specialChars = '§$%&()?';
        
        $chars = str_shuffle($wordsLower) 
                . str_shuffle($wordsUpper)
                . str_shuffle($digits)
                . str_shuffle($specialChars);
        
        return str_shuffle($chars);
    }    
}

//end class
?>