<?php

namespace HybridCMS\Plugins\Comments\Controller;

/**
 * ControllerFactory created and returns a requested Controller-Instance
 *
 * @package Comments
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerFactory implements \HybridCMS\Plugins\Plugin\IControllerFactory
{
    /**
     * Creates an Controller pending on submitted $type
     * @param String $type
     * @return \HybridCMS\Plugins\Comments\Controller\class
     * @throws \Exception
     */
    public static function create($type, $arrParams = array()) 
    {    
        $class = '\\HybridCMS\\Plugins\Comments\\Controller\\' . $type;
        if (false === class_exists($class)) {
            throw new \Exception(
            'Error Processing Request: create(),
                               class does not exist.', 1);
        }
        
        return new $class($arrParams);
    }

}