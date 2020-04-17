<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentInput represents the content of a 
 * e.g. TwitterName, FacebookUrl and Website
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentButton
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContent 
{

    /**
     * Value of the contenth
     * @var String
     */
    protected $value;

    /**
     * __construct
     */
    function __construct($value) 
    {        
        $this->setValue($value);
    }

    /**
     * setValue
     * @param String $value
     */
    public function setValue($value) 
    {
        $this->value = $value;
    }

    /**
     * getValue
     * @return String
     */
    public function getValue() {
        return $this->value;
    }
    
    /**
     * Validates the form-data sent by the user
     * @return Boolean
     */
    public function valueIsValid()
    {
        $ret = false;
        
        if(false === empty($this->value)
           &&
           true === is_string($this->value))
        {
            $ret = true;
        }
        
        return $ret;
    }    
}

?>
