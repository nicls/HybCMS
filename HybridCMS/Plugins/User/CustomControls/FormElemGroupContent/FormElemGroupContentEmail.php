<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentUsername represents the content of a user-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentEmail extends
    \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentInput 
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('E-Mail:');
        $this->setErrorMsg('Bitte gib eine gültigie E-Mail ein.');
        $this->setPlaceholder('E-Mail eingeben.');       
        
        if(false === empty($value))
        {
            $this->setValue($value);
        }
    }

    /**
     * validates the value sent by the client
     */
    public function valueIsValid() 
    {
        //Email was submitted
        $ret = false === empty($this->value)
               &&
               true === \HybridCMS\Modules\Validation\UserDataValidation
                ::isValidEmail($this->value);
                  
        return $ret;
    }
}
?>
