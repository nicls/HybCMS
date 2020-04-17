<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroupContent;

/**
 * class FormElemGroupContentAboutme represents the content of a aboutme-input-field
 *
 * @package FormElemGroupContent
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupContentAboutme 
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
        \FormElemGroupContentTextarea
{
    /**
     * __construct
     */
    function __construct($value) 
    {
        //call constructor of parent class
        parent::__construct();
        
        $this->setLabel('Über mich:');
        $this->setPlaceholder('Über Dich...');    
        
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
                ::isValidAboutme($this->value);
                  
        return $ret;
    }

}
