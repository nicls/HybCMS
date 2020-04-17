<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent;

/**
 * Represents the content of a Checkbox Group
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupContentCheckboxGroup
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContent 
{
    /**
     * Value of the contenth
     * @var String
     */
    protected $value;

    /**
     * Label of the form element
     * @var String
     */
    protected $label;           
    
    /**
     *
     * @var FormElemGroupCheckbox
     */
    protected $arrObjFormElemGroupContentCheckbox;
    
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
     * addObjFormElemGroupContentCheckbox
     * @param FormElemGroupContentCheckbox $objFormElemGroupContentCheckbox
     * @throws \InvalidArgumentException
     */
    public function addObjFormElemGroupContentCheckbox
            ($objFormElemGroupContentCheckbox)
    {
        if(false === $objFormElemGroupContentCheckbox instanceof
                \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
                    \FormElemGroupContentCheckbox) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    addObjFormElemGroupContentCheckbox(),                    
                    $objFormElemGroupContentCheckbox is not valid.', 1); 
        }        
        $this->arrObjFormElemGroupContentCheckbox[] = 
                $objFormElemGroupContentCheckbox;
    }    
        
    function getValue() {
        return $this->value;
    }

    function getLabel() {
        return $this->label;
    }

    function getArrObjFormElemGroupContentCheckbox() {
        return $this->arrObjFormElemGroupContentCheckbox;
    }
    
    /**
     * Validates the form-data sent by the user
     * @return Boolean
     */
    public abstract function valueIsValid();        
}
