<?php

namespace HybridCMS\Plugins\User\CustomControls\FormElemGroups;

/**
 * class FormElemGroupTwitterName is the concrete implemenatation for an form 
 * Input-Field for a TwitterName
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupTwitterName 
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
        
        $this->setIsOptional(true);
        $this->setInputIcon('<i class="fa fa-twitter"></i>');
        $this->setFieldName('hyb_user_twitterName');
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
                 \HybridCMS\Plugins\User\CustomControls\FormElemGroupContent
                    \FormElemGroupContentTwitterName))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of FormElemGroupContentTwitterName.', 1);
        }
        
        parent::setObjContent($objContent);                             
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