<?php

namespace HybridCMS\Util;

/**
 * class VarCheck - validates HTTP Arguments from function 
 * Parameters
 *
 * @package Util
 * @version 1.0
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class VarCheck
{
    /**
     * Check if a passed variable isset and not empty
     * @param mixed $var
     * @return Booelan
     */
    public static function issetAndNotEmpty(&$var)
    {
        return isset($var) && !empty($var);
    }
}