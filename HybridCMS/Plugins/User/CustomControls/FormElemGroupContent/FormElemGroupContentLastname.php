<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentLastname represents the content of a lastname-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentLastname 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentInput 
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Nachname:');
        $this->setErrorMsg('Bitte gib einen gültigen Nachnamen ein.');
        $this->setPlaceholder('Nachname eingeben.');       
        
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
        //twitterName was submitted
        $ret = false === empty($this->value)
               &&
               true === \HybridCMS\Modules\Validation\UserDataValidation
                ::isValidLastname($this->value);
                  
        return $ret;
    }
}
?>
