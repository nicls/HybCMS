<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupFactory creates an FormElemGroup based on the params
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupFactory {
    
    public static function create(&$objFormElemGroupContent, &$objFormStateObserver) 
    {
        $paramClassNamePath = get_class($objFormElemGroupContent);
        $arrParamClassNamePathChunks = explode('\\', $paramClassNamePath);                  
            
        $paramClassName = array_pop($arrParamClassNamePathChunks);            
            
        $classNameToCreate = str_replace('FormElemGroupContent', 'FormElemGroup', $paramClassName);
        $class = '\\HybridCMS\\Plugins\\User\\CustomControls\\FormElemGroups\\' 
                . $classNameToCreate;
        if (false === class_exists($class)) {
            throw new \Exception(
            'Error Processing Request: create(),
                               class does not exist.', 1);
        }
        
        $objFormElemGroup = new $class($objFormElemGroupContent, $objFormStateObserver);
            
        return $objFormElemGroup;
    }
}
