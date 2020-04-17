<?php

namespace HybridCMS\Util;

/**
 * class LogDebug - Uses kLogger to log a debugging message.
 *
 * @package Modules
 * @version 1.0
 * @author Claas Kalwa
 * @copyright 2013 Claroweb.de
 */
class LogDebug
{
    /**
     * Log Debug-message
     * @param String $msg
     */
    public static function logDebug($msg)
    {        
        //Log DEBUG
        $objLogger = new \HybridCMS\Helper\KLogger(LOGFILE_DIR, 
                \HybridCMS\Helper\KLogger::DEBUG);
        $objLogger->logDebug($msg . "\n");        
    }
}