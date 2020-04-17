<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentTwitterName represents the content of a twitter-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentTwitterName 
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
        
        $this->setLabel('TwitterName:');
        $this->setErrorMsg('Bitte gib einen gültigen Twitternamen ein.');
        $this->setPlaceholder('Twitter-Name eingeben.');       
        
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
                ::isValidTwitterUsername($this->value);
                  
        return $ret;
    }
}
?>
