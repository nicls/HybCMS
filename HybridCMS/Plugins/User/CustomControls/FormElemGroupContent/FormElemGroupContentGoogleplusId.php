<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentGoogleplusId represents the content of a googleplusId-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentGoogleplusId 
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
        
        $this->setLabel('Google Plus ID:');
        $this->setErrorMsg('Bitte gib eine gültige Google Plus ID ein.');
        $this->setPlaceholder('Google Plus ID eingeben.');       
        
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
               true === \HybridCMS\Modules\Validation\SMValidation
                ::isValidGoogleplusId($this->value);
                  
        return $ret;
    }
}
?>
