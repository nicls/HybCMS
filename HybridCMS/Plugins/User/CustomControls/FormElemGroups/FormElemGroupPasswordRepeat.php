<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupPasswordRepeat is the concrete implemenatation for an form 
 * Input-Field for a PasswordRepeat
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupPasswordRepeat
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroups
        \FormElemGroupPassword
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct($objContent, $objFormStateObserver);
        
        $this->setFieldName('hyb_user_passwordRepeat');  
    }     
    
    /**
     * validateContent
     * @overwrite validateContent from class FormElemGroup     
     */
    public function validateContent()
    {
        //check if value is set
        $value = $this->objContent->getValue();
        $valueIsSet = (false === empty($value));
                    
        //check if value has an error
        if(true === $valueIsSet || false === $this->isOptional)            
        {                        
            if(false === $valueIsSet)
            {
                $this->setGroupHasError(true); 
            }
            else if(false === $this->objContent
                    ->passwordRepeatIsEqualToPassword())
            {
                $this->setGroupHasWarning(true); 
            }
            else
            {
                $this->setGroupHasSuccess(true); 
            }
        }        
    }     
}

?>