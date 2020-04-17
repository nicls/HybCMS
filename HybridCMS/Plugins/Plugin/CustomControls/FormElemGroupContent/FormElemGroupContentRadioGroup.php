<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentRadioGroup represents the content of  
 * a radio Button Group
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
abstract class FormElemGroupContentRadioGroup
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
     * @var FormElemRadioButton
     */
    protected $arrObjFormElemGroupContentRadioButton;
    
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
     * addObjFormElemRadioButton
     * @param FormElemGroupRadioButton $objFormElemRadioButton
     * @throws \InvalidArgumentException
     */
    public function addObjFormElemGroupContentRadioButton
            ($objFormElemGroupContentRadioButton)
    {
        if(false === $objFormElemGroupContentRadioButton instanceof
                \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
                    \FormElemGroupContentRadioButton) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    addObjFormElemRadioButton(),                    
                    $objFormElemRadioButton is not valid.', 1); 
        }        
        $this->arrObjFormElemGroupContentRadioButton[] = 
                $objFormElemGroupContentRadioButton;
    }    
        
    /**
     * getValue
     * @return String
     */
    public function getValue() {
        return $this->value;
    }    
    
    /**
     * getLabel
     * @return String
     */
    public function getLabel() {
        return $this->label;
    } 

    public function getArrObjFormElemGroupContentRadioButton() {
        return $this->arrObjFormElemGroupContentRadioButton;
    }
        
    /**
     * Validates the form-data sent by the user
     * @return Boolean
     */
    public abstract function valueIsValid();        
}
