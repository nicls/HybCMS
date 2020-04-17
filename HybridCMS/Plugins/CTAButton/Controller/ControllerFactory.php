<?php

namespace HybridCMS\Plugins\CTAButton\Controller;

/**
 * class ControllerFactory
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ControllerFactory implements \HybridCMS\Plugins\Plugin\IControllerFactory
{

    /**
     * Creates an Controller pending on submitted $type
     * @param String $type
     * @return \HybridCMS\Plugins\User\Controller\class
     * @throws \Exception
     */
    public static function create($type, $arrParams = array()) {
        
        $class = '\\HybridCMS\\Plugins\CTAButton\\Controller\\' . $type;
        if (!class_exists($class)) {
            throw new \Exception(
            'Error Processing Request: create(),
                               class does not exist.', 1);
        }
        
        return new $class($arrParams);
    }

}
