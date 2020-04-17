<?php

namespace HybridCMS\Util;

/**
 * class HttpArgs - preprocess and returns HTTP Arguments from POST and GET
 *
 * @package Util
 * @version 1.0
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class HttpArgs
{
    /**
     * Returns a trimmed post-value or null if the key does not exists
     * @param String $key
     * @return String
     * @throws \InvalidArgumentException
     */
    public static function postValOrNull($key)
    {               
        if(true === empty($key))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    postValOrNull(), $key is null.', 1);
        }
        
        if(false === is_string($key))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    postValOrNull(), $key is not a string.', 1);
        }  
        
        if(true === isset($_POST[$key]))
        {
            return trim($_POST[$key]);
        }        
               
        return null;
    }
}