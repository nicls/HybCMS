<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupEmailUniqueOrSessionEmail is the concrete implemenatation for an form 
 * Input-Field for a Email of an NOT existing or equal to $_SESSION['email] email
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupEmailUniqueOrSessionEmail extends
    \HybridCMS\Plugins\User\CustomControls\FormElemGroups\FormElemGroupEmail
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver)
    {
        //call constructor of parent class
        parent::__construct($objContent, $objFormStateObserver);                    
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
            else if(false === $this->objContent->valueIsUniqueOrSameAsSessionValue() )
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