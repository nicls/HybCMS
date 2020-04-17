<?php

namespace HybridCMS\Plugins\WasItHelpful\CustomControls\FormElemGroups;

/**
 * The concrete implemenatation for an form radio button group
 *
 * @package CustomControls
 * @author Claas Kalwa
 * @copyright 2014 Claroweb.de
 */
class FormElemGroupMark
    extends \HybridCMS\Plugins\Plugin\CustomControls\FormElemGroups
        \FormElemGroupRadio
{
    
    /**
     * __construct
     * @param FormElemGroupInput $objContent
     */
    function __construct($objContent, $objFormStateObserver) {
        
        //call constructor of parent class
        parent::__construct();
        
        $this->setIsOptional(false);
        $this->setFieldName('hyb_user_gender'); 
        
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
                    \ContentMarkGroup))
        {
            throw new \InvalidArgumentException(
            'Error Processing Request:
                    setObjContent(),                    
                    $objContent is not an instance of ContentMarkGroup.', 1);
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