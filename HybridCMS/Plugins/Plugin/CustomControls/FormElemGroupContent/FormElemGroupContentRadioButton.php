<?php

namespace HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentRadioButton represents the content of 
 * e.g. RadioButton
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentRadioButton 
{

    /**
     * Label of the Radio Button
     * @var String
     */
    private $label; 
    
    /**
     * Indicates if the radiobutton is checked 
     * @var Boolean
     */
    private $checked;
    
    /**
     * Class of an icon
     * @var String
     */
    private $iconClass;
    
    /**
     * Value of the RadioButton
     * @var String
     */
    private $value;
    
    /**
     * __construct
     */
    function __construct() 
    {
        $this->checked = false;
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
     * setValue
     * @param String $value
     */
    public function setValue($value) 
    {
        $pattern = '/^[a-zA-Z0-9\-\_]+$/';
        
        if(false === preg_match($pattern, $value))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setLabel(),                    
                    $label is not valid.', 1);  
        }
        
        $this->value = $value;
    }    

    /**
     * setChecked
     * @param Boolean $checked
     * @throws \InvalidArgumentException
     */
    public function setChecked($checked) {
        
        if(false === is_bool($checked)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setChecked(),                    
                    $checked is not valid.', 1); 
        }
        
        $this->checked = $checked;
    }
    
    /**
     * setIconClass
     * @param String $iconClass
     * @throws \InvalidArgumentException
     */
    public function setIconClass($iconClass) 
    {
        
        //Icon must match the http://fortawesome.github.io/ icon format
        $pattern = '/^[a-zA-Z][0-9\w\s\-_]*$/';
        
        if(false === is_string($iconClass)
           ||
           false === preg_match($pattern, $iconClass)) 
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setIconClass(),                    
                    $iconClass is not valid.', 1); 
        }
        
        $this->iconClass = $iconClass;
    }    

    public function getLabel() {
        return $this->label;
    }

    public function getChecked() {
        return $this->checked;
    }
    
    public function getIconClass() {
        return $this->iconClass;
    }

    public function getValue() {
        return $this->value;
    }
}
