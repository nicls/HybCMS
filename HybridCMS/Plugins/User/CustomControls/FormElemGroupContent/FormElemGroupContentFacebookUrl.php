<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentFacebookUrl represents the content of a facebookurl-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentFacebookUrl 
    extends \HybridCMS\Plugins\User\CustomControls
        \FormElemGroupContent\FormElemGroupContentInput 
{
    
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Facebook-Url:');
        $this->setErrorMsg('Bitte gib eine gültige Facebook-Url ein.');
        $this->setPlaceholder('Facebook-Url eingeben.');       
        
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
                ::isValidFacebookUrl($this->value);
                  
        return $ret;
    }
}
?>
