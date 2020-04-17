<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentWebsite represents the content of a website-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentWebsite 
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
        
        $this->setLabel('Webseite:');
        $this->setErrorMsg('Bitte gib eine gültige Url ein.');
        $this->setPlaceholder('Website eingeben.');       
        
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
               true === \HybridCMS\Modules\Validation\URLValidation
                ::isValidUrl($this->value);
                  
        return $ret;
    }
}
?>
