<?php

namespace HybridCMS\Content\Strings;

/**
 * class Strings
 *
 * @package Content\Strings
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class Strings 
{
    /**
     * Object containing all Strings 
     * @var SimpleXMLElement
     */
    private $objSimpleXMLElement;
    
    /**
     * __construct
     * @param String $xmlFilePath
     * @throws \Exception
     */
    public function __construct($xmlFilePath) 
    {
        if(false === is_string($xmlFilePath))
        {
            throw new \Exception(
            "Error Processing Request:
                        __construct(), 
                        xmlFilePath is not a string.", 1);
        }
        
        if(false === file_exists($xmlFilePath))
        {
            throw new \Exception(
            "Error Processing Request:
                        __construct(), 
                        filedoes not exist: " . htmlspecialchars($xmlFilePath), 1); 
        }
        
        $this->objSimpleXMLElement = simplexml_load_file($xmlFilePath);
    }
        
    /**
     * printName echos the value of a given stringName
     * @param String $stringName
     * @throws \Exception
     */
    public function getString($stringName)
    {        
        assert(false === empty($this->objSimpleXMLElement));              
        
        $stringValue = $this->objSimpleXMLElement->$stringName;                   
        
        return trim($stringValue);
    }
}