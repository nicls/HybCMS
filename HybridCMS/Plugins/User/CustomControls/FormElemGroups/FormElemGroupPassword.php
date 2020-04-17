<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupPassword is the concrete implemenatation for an form 
 * Input-Field for a Password
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupPassword
    extends \HybridCMS\Plugins\User\CustomControls\FormElemGroups
        \FormElemGroupInput
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct();
        
        $this->setIsOptional(false);
        $this->setInputIcon('<i class="fa fa-key"></i>');
        $this->setFieldName('hyb_user_password');
        $this->setInputType('password');          
        
        //attach obersver
        $this->registerObserver($objFormStateObserver);
        
        //attach Content
        $this->setObjContent($objContent);         

    }
    
    /**
     * setObjContent
     * @param FormElemGroupInputContent $objFormElemGroupInputContent
     * @throws \InvalidArgumentException
     */
    public function setObjContent($objContent) 
    {
        if(false === ($objContent instanceof
                 \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                        \FormElemGroupContentPassword))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of FormElemGroupContentPassword.', 1);
        }
        
        parent::setObjContent($objContent);                             
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
            if(false === $this->objContent->valueIsValid())
            {
                $this->setGroupHasError(true); 
            }
            else
            {
                $this->setGroupHasSuccess(true); 
            }
        }        
    }     

    /**
     * Returns the TwitterInputGroup as String
     * @return String
     */
    public function toString() {
        
        return parent::toString();
    }

}

?>