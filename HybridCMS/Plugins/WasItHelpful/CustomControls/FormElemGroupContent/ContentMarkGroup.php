<?php

namespace HybridCMS\Plugins\WasItHelpful\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentGender represents the content of 
 * group of gender-radio-button
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class ContentMarkGroup
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroupContent
        \FormElemGroupContentRadioGroup
{
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Wie hilfreich ist dieser Artikel:'); 
        
        //create Radio Button Mark 5
        $objRadioButtonMark5 = 
                new \HybridCMS\Plugins\Plugin\CustomControls
                    \FormElemGroupContent\FormElemGroupContentRadioButton(); 
        
        $objRadioButtonW->setChecked(false);
        //$objRadioButtonW->setIconClass('fa fa-female');
        $objRadioButtonW->setLabel('Überhaupt nicht hilfreich.');
        $objRadioButtonW->setValue('mark5');
        
        //create Radio Button Mark 4
        $objRadioButtonMark4 = 
                new \HybridCMS\Plugins\Plugin
                    \CustomControls\FormElemGroupContent
                        \FormElemGroupContentRadioButton();               
        
        $objRadioButtonM->setChecked(false);
        //$objRadioButtonM->setIconClass('fa fa-male');
        $objRadioButtonM->setLabel('Nicht sehr hilfreich.');
        $objRadioButtonM->setValue('mark4');  
        
        //add RadioButtons
        $this->addObjFormElemGroupContentRadioButton($objRadioButtonMark5);
        $this->addObjFormElemGroupContentRadioButton($objRadioButtonMark4);
        
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
