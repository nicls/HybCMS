<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentYoutubeChannelName represents the content of a youtubeChannelName-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentYoutubeChannelName 
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
        
        $this->setLabel('Youtube Channel Name:');
        $this->setErrorMsg('Bitte gib eine gültigen Youtube Channel Namen ein.');
        $this->setPlaceholder('Youtube Channel Namen eingeben.');       
        
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
                ::isValidYoutubeChannelName($this->value);
                  
        return $ret;
    }
}
?>
