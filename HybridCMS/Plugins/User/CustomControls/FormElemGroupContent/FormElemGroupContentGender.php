<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentGender represents the content of 
 * group of gender-radio-button
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentGender 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentRadioGroup
{
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Geschlecht:'); 
        
        //create Gender w
        $objRadioButtonW = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentRadioButton(); 
        
        $objRadioButtonW->setChecked(true);
        $objRadioButtonW->setIconClass('fa fa-female');
        $objRadioButtonW->setLabel('Frau:');
        $objRadioButtonW->setValue('w');
        
        //create Gender m
        $objRadioButtonM = 
                new \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                \FormElemGroupContentRadioButton();               
        
        $objRadioButtonM->setChecked(false);
        $objRadioButtonM->setIconClass('fa fa-male');
        $objRadioButtonM->setLabel('Mann:');
        $objRadioButtonM->setValue('m');  
        
        //add RadioButtons
        $this->addObjFormElemGroupContentRadioButton($objRadioButtonW);
        $this->addObjFormElemGroupContentRadioButton($objRadioButtonM);
        
        if(false === empty($value))
        {
            $this->setValue($value);
        }
    }    
        
    public function valueIsValid() 
    {
        //aboutme was submitted
        $ret = false === empty($this->value)
               &&
               true === \HybridCMS\Modules\Validation\UserDataValidation
                ::isValidGender($this->value);
                  
        return $ret;
    }

}
