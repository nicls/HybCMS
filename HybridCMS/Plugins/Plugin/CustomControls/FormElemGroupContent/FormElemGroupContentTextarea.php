<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentInput represents the content of a 
 * e.g. TwitterName, FacebookUrl and Website
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupContentTextarea
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContent 
{
    /**
     * Value of the contenth
     * @var String
     */
    protected $value;

    /**
     * Placeholder that is a hint to the value-format
     * @var String
     */
    protected $placeholder;

    /**
     * Label of the form element
     * @var String
     */
    protected $label;        

    /**
     * __construct
     */
    function __construct() {}

    /**
     * setValue
     * @param String $value
     */
    public function setValue($value) 
    {
        $this->value = $value;
    }

    /**
     * setPlaceholder
     * @param String $placeholder
     * @throws \InvalidArgumentException
     */
    public function setPlaceholder($placeholder) 
    {                
        $pattern = '/^[0-9a-zA-Z\-_\.,:\(\)\!\?äöüÄÖÜß\s]$/';
        
        if(false === is_string($placeholder)
           ||
           false === preg_match($pattern, $placeholder)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setPlaceholder(),                    
                    $placeholder is not valid.', 1); 
        }
        
        $this->placeholder = $placeholder;
    }

    /**
     * setLabel
     * @param String $label
     * @throws \InvalidArgumentException
     */
    public function setLabel($label) 
    {
        $pattern = '/^[0-9a-zA-Z\-_\.,:\(\)\!\?äöüÄÖÜß\s]$/';
        
        if(false === is_string($label)
           ||
           false === preg_match($pattern, $label)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setLabel(),                    
                    $label is not valid.', 1); 
        }
        
        $this->label = $label;
    }
        
    /**
     * getValue
     * @return String
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * getPlaceholder
     * @return String
     */
    public function getPlaceholder() {
        return $this->placeholder;
    }

    /**
     * getLabel
     * @return String
     */
    public function getLabel() {
        return $this->label;
    } 
    
    /**
     * Validates the form-data sent by the user
     * @return Boolean
     */
    public abstract function valueIsValid();    
}

?>
