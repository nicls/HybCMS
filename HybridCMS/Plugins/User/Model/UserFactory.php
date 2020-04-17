<?php

namespace HybridCMS\Plugins\User\Model;

/**
 * class UserFactory
 *
 * @package User
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UserFactory 
{

    /**
     * Creates an Controller pending on submitted $type
     * @param String $type
     * @return \HybridCMS\Plugins\User\Controller\class
     * @throws \Exception
     */
    public static function create($type) 
    {
        if($type === 'registered')
        {
            $className = 'UserRegistered';
        } 
        else if($type === 'unregistered')
        {
            $className = 'UserUnregistered';
        }
        else 
        {
            $className = 'UserOpenId';
        }
        
        $class = '\\HybridCMS\\Plugins\User\\Model\\' . $className;
        if (!class_exists($class)) {
            throw new \Exception(
            'Error Processing Request: create(),
                               class does not exist.', 1);
        }
        
        return new $class($type);
    }
}