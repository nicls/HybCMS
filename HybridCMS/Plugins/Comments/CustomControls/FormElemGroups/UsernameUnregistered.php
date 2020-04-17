<?php

namespace HybridCMS\Plugins\Comments\CustomControls\FormElemGroups;

/**
 * class UsernameUnregistered is the concrete implemenatation for an form 
 * Input-Field for a Username
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class UsernameUnregistered extends 
    \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups\FormElemGroupInput
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct();
        
        $this->setIsOptional(false);
        $this->setInputIcon('<i class="fa fa-user"></i>');
        $this->setFieldName('hyb_comments_usernameUnregistered');
        $this->setInputType('text');          
        
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
                 \HybridCMS\Plugins\Comments\CustomControls\FormElemGroupContent
                        \ContentUsernameUnregistered))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of 
                    ContentUsernameUnregistered.', 1);
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
            //ensures if the username is not in use by an registered user
            else if(false === $this->objContent->valueIsUnique() )
            {
                $this->setGroupHasWarning(true); 
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